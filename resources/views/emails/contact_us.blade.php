@component('mail::message')
# New Contact Us Message

**Name:** {{ $data['full_name'] }}  
**Email:** {{ $data['email'] }}

**Message:**

{{ $data['message'] }}

Thanks,  
{{ config('app.name') }}
@endcomponent
