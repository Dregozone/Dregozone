<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>New sign-up</title>
</head>
<body>
    <p>Someone has just signed up to {{ config('app.name') }}.</p>

    <p>
        <strong>Name:</strong> {{ $user->name }}<br>
        <strong>Email:</strong> {{ $user->email }}<br>
        <strong>Signed up:</strong> {{ $signedUpAt }}<br>
        <strong>Total users:</strong> {{ $totalUsers }}
        @if ($referrer)
            <br><strong>Referrer:</strong> {{ $referrer }}
        @endif
    </p>
</body>
</html>
