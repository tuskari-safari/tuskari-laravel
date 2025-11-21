<x-mail::message>
# Your Issue Has Been Resolved

Dear {{ $issue->user->full_name }},

We're pleased to inform you that your reported issue has been resolved.

**Issue Details:**
- **Issue Type:** {{ $issue->issue_type ?? 'General' }}
- **Description:** {{ $issue->issue_description }}
- **Reported On:** {{ $issue->created_at->format('M d, Y') }}
- **Resolved On:** {{ now()->format('M d, Y') }}

Thank you for bringing this to our attention. Your feedback helps us improve our services.

If you have any further questions or concerns, please don't hesitate to contact us.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
