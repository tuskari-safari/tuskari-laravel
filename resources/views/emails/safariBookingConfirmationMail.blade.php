@component('mail::message')
# Hello {{ $data['operator']['first_name'] }},

You have a new booking for your safari: **{{ $data['booking']['safari']['title'] }}**.

**Booked by:** {{ $data['traveler']['full_name'] }}

@if($data['booking']['payment_type'] === 'pay_in_full')
**Payment Type:** Paid in Full  
**Full Payment:** ${{ number_format($data['booking']['total_price'], 2) }}
@else
**Payment Type:** Deposit Paid  

**Total Price:** ${{ number_format($data['booking']['total_price'], 2) }}  
**Deposit Payment:** ${{ number_format($data['booking']['deposit_amount'], 2) }}  
**Balance Payment:** ${{ number_format($data['booking']['next_deposit_amount'], 2) }}  
**Next Deposit Due Date:** {{ \Carbon\Carbon::parse($data['booking']['next_deposit_date'])->format('F j, Y') }}
@endif

**Booking Dates:** {{ \Carbon\Carbon::parse($data['booking']['check_in_date'])->format('F j, Y') }} to {{ \Carbon\Carbon::parse($data['booking']['check_out_date'])->format('F j, Y') }}  

**Number of Adults:** {{ $data['booking']['no_of_adults'] }}  
**Number of Children:** {{ $data['booking']['no_of_children'] }}

Thanks,  
{{ config('app.name') }}
@endcomponent
