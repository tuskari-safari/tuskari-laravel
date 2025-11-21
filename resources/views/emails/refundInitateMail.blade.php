@component('mail::message')
# Refund Initiated

Hi {{ $data['traveler']->full_name }},

We’re writing to let you know that your booking for  
**{{ $data['booking']->safari->title }}** has been cancelled.

Your refund has been initiated and will be processed to your original payment method.  
Please allow a few business days for the amount to reflect in your account.

**Booking ID:** {{ $data['booking']->id }}  
**Refund Amount:** ${{ number_format($data['booking']->total_price, 2) }}

If you have any questions, feel free to contact our support team.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
