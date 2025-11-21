@component('mail::message')
# Hello {{ $data['operator']->first_name }},

@if($data['withdrawalRequest']->status === 'approved')
Your payout request has been **approved** and is being processed.  
@endif

@if($data['withdrawalRequest']->status === 'processed')
Your payout request has been **successfully processed**.

**Payout Details:**  
- Operator Name: {{ $data['operator']->full_name }}  
- Amount Paid: ${{ number_format($data['withdrawalRequest']->amount ?? 0, 2) }}  
- Date: {{ \Carbon\Carbon::now()->format('F j, Y, g:i A') }}  

The amount has been transferred to your registered payout account.  
@endif

@if($data['withdrawalRequest']->status === 'rejected')
Unfortunately, your payout request has been **rejected**.  
@if($data['withdrawalRequest']->admin_notes)
**Reason:** {{ $data['withdrawalRequest']->admin_notes }}
@endif
@endif

@if($data['withdrawalRequest']->status === 'pending')
Your payout request is currently **pending**. We’ll notify you once it’s processed.  
@endif

Thanks for being a valued partner,  
{{ config('app.name') }}
@endcomponent
