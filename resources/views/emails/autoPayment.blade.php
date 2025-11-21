@component('mail::message')
# Payment Successful 🎉

Hi {{ $data['traveler']->full_name }},

We’re happy to let you know that your balance payment for **{{ $data['booking']->safari->title }}** has been successfully processed.

**Amount Paid:** ${{ number_format($data['booking']->next_deposit_amount, 2) }}  
**Booking ID:** {{ $data['booking']->id }}

@isset($data['receiptUrl'])
@component('mail::button', ['url' => $data['receiptUrl']])
View Receipt
@endcomponent
@endisset

We look forward to your amazing safari experience!

Thanks,<br>
{{ config('app.name') }}
@endcomponent
