<x-mail::message>
# Congratulations, {{ $vendor['full_name'] ?? 'Vendor' }}!

We're excited to inform you that your operator account has been successfully **approved** by our administrator.

You now have full access to your dashboard and can start managing your activities on our platform.

<x-mail::button :url="route('frontend.login')">
Login Now
</x-mail::button>

**Important:** If you ever forget your password, simply click the **"Forgot your password?"** link on the login page to reset it.

If you have any questions or need any assistance, please don’t hesitate to contact our support team – we're here to help!

Thanks, and welcome aboard!  
**The {{ config('app.name') }} Team**
</x-mail::message>
