@component('mail::message')
# Hello {{ $data['traveler']['first_name'] }},

Thank you for booking the safari: **{{ $data['booking']['safari']['title'] }}**.

Your booking has been successfully confirmed!

@if($data['booking']['payment_type'] === 'pay_in_full')
**Payment Type:** Paid in Full  
**Full Payment:** ${{ number_format($data['booking']['total_price'], 2) }}
@else
**Payment Type:** Deposit Paid  

**Total Price:** ${{ number_format($data['booking']['total_price'], 2) }}  
**Deposit Payment:** ${{ number_format($data['booking']['deposit_amount'], 2) }}  
**Balance Payment:** ${{ number_format($data['booking']['total_price'] - $data['booking']['deposit_amount'], 2) }}  
**Next Deposit Due Date:** {{ \Carbon\Carbon::parse($data['booking']['next_deposit_date'])->format('F j, Y') }}
@endif

**Check-in Date:** {{ \Carbon\Carbon::parse($data['booking']['check_in_date'])->format('F j, Y') }}  
**Check-out Date:** {{ \Carbon\Carbon::parse($data['booking']['check_out_date'])->format('F j, Y') }}  
**Number of Adults:** {{ $data['booking']['no_of_adults'] }}  
**Number of Children:** {{ $data['booking']['no_of_children'] }}  

@component('mail::button', ['url' => $data['receiptUrl']])
View Your Payment Receipt
@endcomponent

We hope you have a wonderful safari experience!

Thanks,  
{{ config('app.name') }}
@endcomponent
