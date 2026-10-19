<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>New contact message</title>
</head>
<body>
    <p>A new contact message has arrived on {{ config('app.name') }}. Reply to this email to answer the sender.</p>

    <p>
        <strong>Name:</strong> {{ $contactMessage->name }}<br>
        <strong>Email:</strong> {{ $contactMessage->email }}<br>
        <strong>Subject:</strong> {{ $contactMessage->subject }}<br>
        <strong>Type:</strong> {{ str_replace('_', ' ', $contactMessage->type) }}<br>
        <strong>Received:</strong> {{ $receivedAt }}
        @foreach (array_filter($contactMessage->metadata ?? []) as $label => $value)
            <br><strong>{{ ucfirst(\Illuminate\Support\Str::snake($label, ' ')) }}:</strong> {{ $value }}
        @endforeach
    </p>

    <p>{!! nl2br(e($contactMessage->message)) !!}</p>

    <p><a href="{{ $adminUrl }}">View it in the admin area</a></p>
</body>
</html>
