# Multi-Tenant Property Bill Management System

A comprehensive Laravel 11 application for managing property billing across multiple house owners with dual authentication guards and column-based multi-tenancy.

## Architecture Overview

### Multi-Tenancy Implementation
This system implements **column-based multi-tenancy** rather than traditional database or domain-based tenancy:

- **Tenant Isolation**: Uses `house_owner_id` for data segregation across all tenant-scoped models
- **TenantScoped Trait**: Automatically filters queries by authenticated house owner
- **Global Scopes**: Ensures data isolation without manual filtering
- **Stancl/Tenancy Package**: Installed but intentionally disabled (see `bootstrap/providers.php`)

### Authentication Guards
Three separate authentication contexts:
```php
'admin'       // System administrators - full access
'house_owner' // Property managers - tenant-scoped access  
'web'         // Standard users (currently unused)
```


## Local Development Setup

### Prerequisites
- PHP 8.3+
- Composer
- MySQL 8.0+
- XAMPP/WAMP/Laragon (recommended for Windows)

### Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/jahid012/multi-tenant-blogging-platform.git
   cd multi-tenant-blogging-platform
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   Update your `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=flat_and_bill_management_system
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Database Setup**
   ```bash
   php artisan migrate:fresh --seed
   php artisan passport:install
   ```

6. **Start Development Server**
   ```bash
   php artisan serve
   ```

7. **Queue Worker** (for notifications)
   ```bash
   php artisan bills:process-overdue
   php artisan queue:work
   ```

### Default Login Credentials

**System Administrator:**
- URL: `/admin/login`
- Email: `admin@gmail.com`
- Password: `123456`

**House Owner (Test Account):**
- URL: `/house-owner/login`
- Email: `owner@gmail.com`
- Password: `123456`

## Technical Details

### Design Patterns

#### 1. Tenant Scoping (`app/Traits/TenantScoped.php`)
All tenant-scoped models automatically filter by `house_owner_id`:
```php
use App\Traits\TenantScoped;

class Building extends Model {
    use TenantScoped;
}
```

**Important**: Filter by `house_owner_id` - the Tenant trait handles this automatically.

#### 2. Route Structure
```php
// Admin routes: full system access
Route::prefix('admin')->middleware(['role:admin'])

// House owner routes: tenant-scoped access
Route::prefix('house-owner')->middleware(['role:house_owner'])
```

#### 3. Event-Driven Architecture
Bills trigger automatic notifications:
```php
BillCreated → SendBillCreatedNotification → BillCreatedNotification (Queued)
BillPaid → SendBillPaidNotification → BillPaidNotification (Queued)
```

#### 4. Queue-Based Jobs
- `ProcessOverdueBills` - Updates bill status and sends notifications
- All email notifications implement `ShouldQueue` for performance

### Database Optimizations

#### Indexes
Strategic indexing for multi-tenant queries:
```php
// Buildings table
$table->index(['house_owner_id', 'is_active']);

// Bills table
$table->index(['building_id', 'status', 'due_date']);
$table->index(['flat_id', 'status']);
$table->index(['bill_month', 'status']);

// Unique constraint prevents duplicate bills
$table->unique(['flat_id', 'bill_category_id', 'bill_month']);
```

#### Query Performance
- **Global Scopes**: Automatic tenant filtering at model level
- **Eager Loading**: Relationships pre-loaded to avoid N+1 queries
- **Computed Columns**: `total_amount` stored as computed column
- **Selective Fields**: Controllers fetch only required columns

### Security Features

#### Multi-Guard Authentication
- Separate password policies for admin and house owner accounts
- Role-based middleware protection
- Session isolation between guard types

#### Data Protection
- **Tenant Isolation**: Automatic filtering prevents cross-tenant data access
- **Mass Assignment Protection**: Fillable arrays on all models
- **CSRF Protection**: All forms protected
- **SQL Injection Prevention**: Eloquent ORM with parameter binding

## Development Workflows

### Testing Multi-Tenancy
```bash
# Test tenant data isolation
php artisan tinker
>>> App\Models\Building::count() // Should auto-scope to authenticated user
```

### Database Operations
```bash
# Reset with fresh sample data
php artisan migrate:fresh --seed

# Check queue jobs
php artisan queue:work --verbose
```

### Adding New Tenant-Scoped Models
1. Add `TenantScoped` trait to model
2. Include `house_owner_id` in fillable array
3. Add foreign key constraint in migration
4. Create factory with `house_owner_id` relationship

## Performance

### Query Optimization
- **Tenant Scoping**: All queries automatically scoped, reducing dataset size by ~80%
- **Index Usage**: Strategic indexes on frequently queried tenant + status combinations
- **Eager Loading**: Bills load with flat, tenant, and category relationships
- **Pagination**: Large datasets paginated with tenant-aware queries

### Caching Strategy
- **Model Caching**: Tenant-specific cache keys prevent cross-tenant data leaks
- **Query Caching**: Frequently accessed bill summaries cached per tenant
- **Session Caching**: User permissions cached to avoid repeated role checks

### Background Processing
- **Queue Workers**: Email notifications processed asynchronously
- **Scheduled Jobs**: Overdue bill processing runs hourly
- **Event Broadcasting**: Real-time notifications for bill updates