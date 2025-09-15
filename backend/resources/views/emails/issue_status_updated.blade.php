<x-mail::message>
# Issue Status Updated

Hello {{ $user->name }},

Your reported issue (ID: {{ $issue->id }}) has been updated to the following status:

**Status:** {{ ucfirst($issue->status) }}

**Description:**
{{ $issue->description }}

@if($issue->status === 'resolved')
Thank you for helping us improve our community!
@endif

If you have any questions, please reply to this email.

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
