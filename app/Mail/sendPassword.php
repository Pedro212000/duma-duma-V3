<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendPassword extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $password; // <- add this
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct($password, $email)
    {
        $this->password = $password;
        $this->email = $email;
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Account Password',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.sendPassword',
            with: [
                'password' => $this->password,
                'email' => $this->email
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
