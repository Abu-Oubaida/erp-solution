<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reminder</title>
</head>
<body>
<h2>Hello {{ $user->name }},</h2>

<p>Good Day!</p>

{!! @$body !!}

<p><strong>N:B:</strong> This is an automated system-generated email. Hence, no need to reply.</p>

<p>Thanks,<br>
    Regards,<br>
    {{ $company->company_name }} | {{ config('app.name') }} Team</p>
</body>
</html>
