<x-mail::message>
<x-slot name="header">
    <a href="{{ url('/') }}">
        <img src="{{ asset('admin_assets/logo/logo1.png') }}" alt="Logo" style="max-width: 180px;">
    </a>
</x-slot>

# Confirmation Code

Do not share your OTP with anyone  

**{{ $data['code'] }}**

Thanks,<br>
{{ config('app.name') }}

</x-mail::message>
