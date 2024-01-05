<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TemplateEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;

    public $title;

    public $recipient_name;

    public $body;

    public $signature;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $email_subject, string $email_title, string $recipient_name, string $email_body, string $email_signature)
    {
        $this->subject = $email_subject;          // email subject (from form)
        $this->title = $email_title;              // email title (from form)
        $this->recipient_name = $recipient_name;  // email recipient name (not from form)
        $this->body = $email_body;                // email body (from form)
        $this->signature = $email_signature;      // email signature (from form)
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this->markdown('emails.emailTemplate', [ // pass public variables (set in __construct) to emailTemplate.blade
            'title' => $this->title,
            'name' => $this->recipient_name,
            'body' => $this->body,
            'signature' => $this->signature,
        ])
            ->subject($this->subject);  // set subject to subject variable, see Mail docs for more info.
    }
}
