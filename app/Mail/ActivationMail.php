<?php

namespace App\Mail;

use App\Models\User; // Import the User model
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $activationCode; // Add the activation code property
    public $user; // Add the user property

    /**
     * Create a new message instance.
     */
    public function __construct($user, $activationCode) // Accept the activation code in the constructor
    {
        $this->user = $user; // Store the user object
        $this->activationCode = $activationCode; // Store the activation code
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Activation Mail', // Subject of the email
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.activation', // Specify the Blade view for the email content
            with: [
                'activation_code' => $this->activationCode, // Pass the activation code
                'user' => $this->user, // Pass the user if needed
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
