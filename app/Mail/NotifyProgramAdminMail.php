<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyProgramAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $program_title, $program_dept, $user_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(String $program_title, String $program_dept, String $user_name)
    {
        $this->program_title = $program_title;   // program title (ex. Bachelor of Computer Science)
        $this->program_dept = $program_dept;     // program department (ex. Computer Science)
        $this->user_name = $user_name;           // Inviting Collaborator's name
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.notifyProgramAdmin', [ // pass public variables (set in __construct) to notifyProgramAdmin.blade
            'program_title' => $this->program_title,
            'program_dept' => $this->program_dept,
            'user_name' => $this->user_name,
            ])
        ->subject('Program Collaboration Invitation');  // set subject to Invitation to Collaborate, see Mail docs for more info.
    }
}
