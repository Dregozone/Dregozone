<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Database Backup</title>
</head>
<body>
    <p>Hello,</p>

    <p>Please find attached the latest database backup for {{ config('app.name') }}.</p>

    <p>Generated: {{ now()->format('d M Y, H:i') }} UTC</p>

    <p>Please store this backup securely and delete this email once the archive has been safely stored.</p>
</body>
</html>