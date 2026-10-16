<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public ContactMessage $contactMessage) {}

    /**
     * Get the message envelope, replying to the sender so a reply from the inbox answers them.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->contactMessage->email, $this->contactMessage->name)],
            subject: 'Dregozone — New contact message: '.$this->contactMessage->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new-contact-message',
            with: [
                'receivedAt' => $this->contactMessage->created_at->timezone('Europe/London')->format('d M Y, H:i T'),
                'adminUrl' => route('admin.contact-messages.index', ['search' => $this->contactMessage->email]),
            ],
        );
    }
}
