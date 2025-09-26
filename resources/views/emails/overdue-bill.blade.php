<x-mail::message>
# Overdue Bills Reminder

Hello {{ $recipient->name }},

You have **{{ count($overdueBills) }}** overdue bill(s) with a total amount of **BDT {{ number_format($totalOverdueAmount, 2) }}**.

## Overdue Bills Details

@foreach($overdueBills as $bill)
---

**{{ $bill->billCategory->name }}** - {{ $bill->flat->flat_number }} ({{ $bill->building->name }})
- **Bill Month:** {{ \Carbon\Carbon::createFromFormat('Y-m', $bill->bill_month)->format('F Y') }}
- **Amount:** BDT {{ number_format($bill->amount + $bill->due_amount, 2) }}
- **Due Date:** {{ \Carbon\Carbon::parse($bill->due_date)->format('d M Y') }}
- **Days Overdue:** {{ \Carbon\Carbon::parse($bill->due_date)->diffInDays(now()) }} days

@endforeach

---

<x-mail::button :url="url('/house-owner/bills/overdue')">
View All Overdue Bills
</x-mail::button>

**Important:** Please make the payment as soon as possible to avoid further penalties. Late payment may result in additional charges.

If you have already made the payment, please contact us with the transaction details.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
