<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyProgramOwnerMail extends Mailable
{
    use Queueable, SerializesModels;

    public $program_title;

    public $program_dept;

    public $user_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $program_title, string $user_name)
    {
        $this->program_title = $program_title;   // program title (ex. Bachelor of Computer Science)
        $this->user_name = $user_name;           // Inviting Collaborator's name
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this->markdown('emails.notifyProgramOwner', [ // pass public variables (set in __construct) to notifyProgramAdmin.blade
            'program_title' => $this->program_title,
            'user_name' => $this->user_name,
        ])
            ->subject('Program Collaboration Invitation');  // set subject to Invitation to Collaborate, see Mail docs for more info.
    }
}
