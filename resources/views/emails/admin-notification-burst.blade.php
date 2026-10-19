<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Burst of {{ $description }}</title>
</head>
<body>
    <p>More than {{ $limit }} {{ $description }} have arrived on {{ config('app.name') }} within {{ $windowMinutes }} minutes.</p>

    <p>Individual emails for {{ $description }} are paused for the rest of this {{ $windowMinutes }}-minute window, so this may be spam.</p>

    @if ($adminUrl)
        <p><a href="{{ $adminUrl }}">Review them in the admin area</a></p>
    @endif
</body>
</html>
