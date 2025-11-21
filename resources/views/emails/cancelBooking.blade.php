@component('mail::message')
# Hello {{ $data['operator']->first_name }},

We want to inform you that **{{ $data['traveler']->full_name }}** has canceled their booking for your safari:

**Safari Title:** {{ $data['booking']->safari->title }}  
**Booking Dates:** {{ \Carbon\Carbon::parse($data['booking']->check_in_date)->format('F j, Y') }} to {{ \Carbon\Carbon::parse($data['booking']->check_out_date)->format('F j, Y') }}  

@if($data['booking']->payment_type === 'pay_in_full')
**Payment Type:** Paid in Full  
**Total Paid:** ${{ number_format($data['booking']->total_price, 2) }}
@else
**Payment Type:** Deposit Paid  
**Total Amount:** ${{ number_format($data['booking']->total_price, 2) }}
@endif


Thanks,  
{{ config('app.name') }}
@endcomponent
