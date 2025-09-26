@extends('layouts.app')

@section('title', 'Edit Bill')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Bill</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.bills.index') }}">Bills</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.bills.show', $bill) }}">{{ $bill->bill_number }}</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Bill: {{ $bill->bill_number }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="POST" action="{{ route('admin.bills.update', $bill) }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Current Bill Status -->
                                <div class="alert alert-info">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Status:</strong><br>
                                            <span class="badge 
                                                @if($bill->status === 'paid') badge-success 
                                                @elseif($bill->status === 'partially_paid') badge-info
                                                @elseif($bill->status === 'overdue') badge-danger 
                                                @else badge-warning @endif">
                                                {{ ucfirst(str_replace('_', ' ', $bill->status)) }}
                                            </span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Previous Due:</strong><br>
                                            BDT {{ number_format($bill->previous_due, 2) }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Paid Amount:</strong><br>
                                            BDT {{ number_format($bill->paid_amount ?? 0, 2) }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Outstanding:</strong><br>
                                            BDT {{ number_format($bill->total_amount - ($bill->paid_amount ?? 0), 2) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="building_id">Building <span class="text-danger">*</span></label>
                                            <select class="form-control" id="building_id" name="building_id" required>
                                                <option value="">Select Building</option>
                                                @foreach($buildings as $building)
                                                    <option value="{{ $building->id }}" 
                                                            {{ old('building_id', $bill->flat->building_id) == $building->id ? 'selected' : '' }}>
                                                        {{ $building->name }} - {{ $building->city }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="flat_id">Flat <span class="text-danger">*</span></label>
                                            <select class="form-control @error('flat_id') is-invalid @enderror" 
                                                    id="flat_id" name="flat_id" required>
                                                <option value="">Select Flat</option>
                                                @foreach($buildings as $building)
                                                    @if($building->flats->count() > 0)
                                                        <optgroup label="{{ $building->name }}">
                                                            @foreach($building->flats as $flat)
                                                                <option value="{{ $flat->id }}" 
                                                                        data-building="{{ $building->id }}"
                                                                        {{ old('flat_id', $bill->flat_id) == $flat->id ? 'selected' : '' }}>
                                                                    {{ $flat->flat_number }} 
                                                                    @if($flat->currentTenant)
                                                                        ({{ $flat->currentTenant->name }})
                                                                    @else
                                                                        (Vacant)
                                                                    @endif
                                                                </option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('flat_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bill_category_id">Bill Category <span class="text-danger">*</span></label>
                                            <select class="form-control @error('bill_category_id') is-invalid @enderror" 
                                                    id="bill_category_id" name="bill_category_id" required>
                                                <option value="">Select Category</option>
                                                @foreach($billCategories as $category)
                                                    <option value="{{ $category->id }}" 
                                                            {{ old('bill_category_id', $bill->bill_category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('bill_category_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bill_month">Bill Month <span class="text-danger">*</span></label>
                                            <input type="month" class="form-control @error('bill_month') is-invalid @enderror" 
                                                   id="bill_month" name="bill_month" 
                                                   value="{{ old('bill_month', $bill->bill_month) }}" required>
                                            @error('bill_month')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="amount">Bill Amount ($) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                                                   id="amount" name="amount" value="{{ old('amount', $bill->amount) }}" 
                                                   placeholder="Enter bill amount" min="0" required>
                                            @error('amount')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Total amount will be calculated including previous due: BDT {{ number_format($bill->previous_due, 2) }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="due_date">Due Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                                   id="due_date" name="due_date" 
                                                   value="{{ old('due_date', $bill->due_date->format('Y-m-d')) }}" required>
                                            @error('due_date')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3" 
                                              placeholder="Any additional notes about this bill...">{{ old('notes', $bill->notes) }}</textarea>
                                    @error('notes')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Total Calculation Display -->
                                <div class="card card-light">
                                    <div class="card-header">
                                        <h3 class="card-title">Bill Calculation</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong>Bill Amount:</strong><br>
                                                $<span id="display-amount">{{ number_format($bill->amount, 2) }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Previous Due:</strong><br>
                                                BDT {{ number_format($bill->previous_due, 2) }}
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Total Amount:</strong><br>
                                                $<span id="display-total">{{ number_format($bill->amount + $bill->previous_due, 2) }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Outstanding:</strong><br>
                                                $<span id="display-outstanding">{{ number_format($bill->total_amount - ($bill->paid_amount ?? 0), 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($bill->status === 'paid')
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Warning:</strong> This bill has been marked as paid. Editing may affect payment records.
                                    </div>
                                @endif
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Bill
                                </button>
                                <a href="{{ route('admin.bills.show', $bill) }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <a href="{{ route('admin.flats.show', $bill->flat) }}" class="btn btn-info">
                                    <i class="fas fa-home"></i> View Flat
                                </a>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const previousDue = {{ $bill->previous_due }};
    const paidAmount = {{ $bill->paid_amount ?? 0 }};

    
    $('#building_id, #flat_id, #bill_category_id').select2({
        theme: 'bootstrap4'
    });

    
    $('#building_id').on('change', function() {
        const buildingId = $(this).val();
        const flatSelect = $('#flat_id');
        
        flatSelect.find('option').hide();
        flatSelect.val('').trigger('change');
        
        if (buildingId) {
            flatSelect.find(`option[data-building="${buildingId}"]`).show();
        } else {
            flatSelect.find('option').show();
        }
    });

    
    $('#building_id').trigger('change');

    
    $('#amount').on('input', function() {
        const amount = parseFloat($(this).val()) || 0;
        const total = amount + previousDue;
        const outstanding = total - paidAmount;
        
        $('#display-amount').text(amount.toFixed(2));
        $('#display-total').text(total.toFixed(2));
        $('#display-outstanding').text(outstanding.toFixed(2));
    });

    
    $('form').on('submit', function(e) {
        let isValid = true;
        const requiredFields = ['flat_id', 'bill_category_id', 'bill_month', 'amount', 'due_date'];
        
        requiredFields.forEach(function(field) {
            const input = $(`[name="${field}"]`);
            if (!input.val() || (typeof input.val() === 'string' && !input.val().trim())) {
                input.addClass('is-invalid');
                isValid = false;
            } else {
                input.removeClass('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            toastr.error('Please fill in all required fields.');
        }
    });

    
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });

    
    $('#due_date').on('change', function() {
        const selectedDate = new Date($(this).val());
        const today = new Date();
        
        if (selectedDate < today) {
            toastr.warning('Due date is in the past. This bill may be marked as overdue.');
        }
    });

    
    $('#bill_month').on('change', function() {
        const originalMonth = '{{ $bill->bill_month }}';
        const newMonth = $(this).val();
        
        if (newMonth !== originalMonth) {
            toastr.info('Changing bill month may affect due calculations.');
        }
    });
});
</script>
@endpush