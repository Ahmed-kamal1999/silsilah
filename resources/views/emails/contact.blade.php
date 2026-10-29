<!DOCTYPE html>
<html lang="ar" dir="rtl">
<body style="font-family: Tahoma, Arial, sans-serif; line-height:1.8; color:#0A1640">
    <h2>{{ __('site.mail.subject', [], 'ar') }}</h2>
    <p>{{ __('site.mail.intro', [], 'ar') }}</p>
    <table cellpadding="6" style="border-collapse:collapse">
        <tr><td><strong>{{ __('site.contact.form.name', [], 'ar') }}</strong></td><td>{{ $contact->name }}</td></tr>
        <tr><td><strong>{{ __('site.contact.form.email', [], 'ar') }}</strong></td><td>{{ $contact->email }}</td></tr>
        <tr><td><strong>{{ __('site.contact.form.phone', [], 'ar') }}</strong></td><td dir="ltr">{{ $contact->phone }}</td></tr>
        <tr><td><strong>{{ __('site.contact.form.message', [], 'ar') }}</strong></td><td>{{ $contact->message ?: '—' }}</td></tr>
    </table>
</body>
</html>
