@component('mail::message')
# Hello {{ $data['operator']->first_name }},

{{ $data['traveler']->full_name }} has completed the **balance payment** for your safari:

**Safari Title:** {{ $data['booking']->safari->title }}  
**Payment Type:** Manual Balance Payment  
**Manually Paid:** ${{ number_format($data['booking']->next_deposit_amount, 2) }}  
**Total Paid :** ${{ number_format($data['booking']->total_price, 2) }}

You can view the receipt using the link below:

@component('mail::button', ['url' => $data['receiptUrl']])
View Receipt
@endcomponent

Best regards,  
{{ config('app.name') }}
@endcomponent