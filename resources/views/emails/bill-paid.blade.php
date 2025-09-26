<x-mail::message>
# Payment Received

Hello,

We have received payment for the bill of **{{ $flat->flat_number }}** in **{{ $building->name }}**.

## Payment Details

- **Category:** {{ $category->name }}
- **Bill Month:** {{ \Carbon\Carbon::createFromFormat('Y-m', $bill->bill_month)->format('F Y') }}
- **Bill Amount:** BDT {{ number_format($bill->amount, 2) }}
@if($bill->due_amount > 0)
- **Previous Due:** BDT {{ number_format($bill->due_amount, 2) }}
@endif
- **Total Amount:** BDT {{ number_format($bill->amount + $bill->due_amount, 2) }}
- **Paid Amount:** BDT {{ number_format($bill->paid_amount, 2) }}
- **Payment Date:** {{ \Carbon\Carbon::parse($bill->paid_date)->format('d M Y') }}
- **Payment Status:** {{ ucfirst($bill->status) }}

@if($bill->payment_method)
- **Payment Method:** {{ $bill->payment_method }}
@endif

@if($bill->transaction_id)
- **Transaction ID:** {{ $bill->transaction_id }}
@endif

@if($tenant)
**Tenant:** {{ $tenant->name }}  
**Contact:** {{ $tenant->phone }}
@endif

@if($bill->payment_notes)
**Payment Notes:** {{ $bill->payment_notes }}
@endif

<x-mail::button :url="url('/house-owner/bills/' . $bill->id)">
View Payment Receipt
</x-mail::button>

@if($bill->status === 'paid')
**Thank you!** The bill has been fully paid.
@else
**Note:** This is a partial payment. Remaining balance: BDT {{ number_format(($bill->amount + $bill->due_amount) - $bill->paid_amount, 2) }}
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
