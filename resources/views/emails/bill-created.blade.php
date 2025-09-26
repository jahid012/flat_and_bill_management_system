<x-mail::message>
# New Bill Created

Hello,

A new bill has been created for **{{ $flat->flat_number }}** in **{{ $building->name }}**.

## Bill Details

- **Category:** {{ $category->name }}
- **Bill Month:** {{ \Carbon\Carbon::createFromFormat('Y-m', $bill->bill_month)->format('F Y') }}
- **Amount:** BDT {{ number_format($bill->amount, 2) }}
@if($bill->due_amount > 0)
- **Previous Due:** BDT {{ number_format($bill->due_amount, 2) }}
- **Total Amount:** BDT {{ number_format($bill->amount + $bill->due_amount, 2) }}
@endif
- **Due Date:** {{ \Carbon\Carbon::parse($bill->due_date)->format('d M Y') }}

@if($tenant)
**Tenant:** {{ $tenant->name }}  
**Contact:** {{ $tenant->phone }}
@endif

@if($bill->notes)
**Notes:** {{ $bill->notes }}
@endif

<x-mail::button :url="url('/house-owner/bills/' . $bill->id)">
View Bill Details
</x-mail::button>

**Important:** Please ensure payment is made before the due date to avoid late fees.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
