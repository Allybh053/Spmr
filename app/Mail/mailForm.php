<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Mail\Mailables\Attachment;


class mailForm extends Mailable
{
    use Queueable, SerializesModels;
    public $request;
    /**
     * Create a new message instance.
     */
    public function __construct($request)
    {
        $this->data = $request;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Mail Relay Request',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $content = new Content(
            view: 'emails.emailnotification',
            with: ['data' => $this->data]
        );
        // Attach files to the email
        if ($this->data->hasFile('attachment')) {
            $files = $this->data->file('attachment');
            foreach ($files as $file) {
                $attachment = new Attachment(
                    file: $file->getRealPath(),
                    options: [
                        'as' => $file->getClientOriginalName(),
                        'mime' => $file->getClientMimeType(),
                    ]
                );
                $content->attach($attachment);
            }
        }

        return $content;
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        // Attach files to the email
        if ($this->data->hasFile('attachment')) {
            $files = $this->data->file('attachment');
            foreach ($files as $file) {
                $attachment = new Attachment(
                    file: $file->getRealPath(),
                    options: [
                        'as' => $file->getClientOriginalName(),
                        'mime' => $file->getClientMimeType(),
                    ]
                );
                $attachments[] = $attachment;
            }
        }

        return $attachments;
    }

}
