# Multi-Tenant Property Bill Management System

[![Watch the video](https://raw.githubusercontent.com/jahid012/flat_and_bill_management_system/master/preview.jpg)](https://drive.google.com/file/d/1mI9yIEMRHBUkZHHMoky5wEdVKMAsA9LY/view?usp=sharing)


### Multi-Tenancy Implementation
This system uses a **column-based multi-tenancy approach** instead of database-level or domain-based separation.

- **Tenant Isolation**: Data is segregated by `house_owner_id` across all tenant-related models.  
- **TenantScoped Trait**: Ensures queries are automatically filtered for the authenticated house owner.  
- **Global Scopes**: Enforces data isolation consistently, removing the need for manual filtering.


## Local Development Setup

### Prerequisites
- PHP 8.3+
- Composer
- MySQL 8.0+
- XAMPP/WAMP/Laragon (recommended for Windows)

### Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/jahid012/flat_and_bill_management_system.git
   cd flat_and_bill_management_system
   ```

2. **Install Dependencies**
   ```bash
   composer install
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
- URL: `http://127.0.0.1:8000/admin/login`
- Email: `admin@gmail.com`
- Password: `123456`

**House Owner (Test Account):**
- URL: `http://127.0.0.1:8000/house-owner/login`
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

Route::prefix('admin')->middleware(['role:admin'])

Route::prefix('house-owner')->middleware(['role:house_owner'])
```

#### 3. Event-Driven Architecture
Bills trigger automatic notifications:
```php
BillCreated → SendBillCreatedNotification → BillCreatedNotification (Queued)
BillPaid → SendBillPaidNotification → BillPaidNotification (Queued)
```

#### 4. Queue Jobs
- `ProcessOverdueBills` - Updates overdue bill statuses and sends alerts
- All notifications implement `ShouldQueue` for better performance

### Database Optimizations

#### Indexes
Indexes have been added to improve multi-tenant queries:
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
- **Global Scopes**: Ensures tenant filtering at the model level
- **Eager Loading**: Avoids N+1 query issues
- **Computed Columns**: Example → `total_amount` as a computed field
- **Selective Fields**: Controllers only fetch the fields they need

### Security Features

#### Multi-Guard Authentication
- Separate guards for admins and house owners
- Role-based middleware for route protection
- Session isolation between guards

#### Data Protection
- Automatic tenant isolation (no cross-tenant data leaks)
- Mass assignment protection with `$fillable`
- CSRF protection enabled
- SQL injection prevention via Eloquent bindings

## Development Workflows

### Testing Multi-Tenancy
```bash
php artisan tinker
>>> App\Models\Building::count()
# Should only return results for the logged-in house owner
```

### Database Operations
```bash
# Reset with seed data
php artisan migrate:fresh --seed

# Run queues with debug info
php artisan queue:work --verbose
```

### Adding New Tenant-Scoped Models
1. Add TenantScoped trait
2. Include `house_owner_id` in $fillable
3. Add a foreign key in migration
4. Update factory to `include house_owner_id`

## Performance

### Query Optimization
- Tenant Scoping reduces dataset size by ~80%
- Indexes improve search speed on tenant + status columns
- Eager Loading preloads bills with flat, tenant, and category relations
- Pagination applied for large datasets

### Caching Strategy
- Tenant-specific cache keys to avoid leaks
- Bill summaries cached per tenant
- Session caching for user permissions

### Background Processing
- Queue Workers for async email notifications
- Scheduled Jobs process overdue bills hourly
- Broadcasting used for real-time bill updates