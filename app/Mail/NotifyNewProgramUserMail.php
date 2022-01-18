<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PhpParser\Node\Expr\Cast\String_;

class NotifyNewProgramUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $program_title, $user_name, $pass;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(String $program_title, String $user_name, String $pass)
    {
        $this->program_title = $program_title;   // program title (ex. Bachelor of Computer Science)
        $this->user_name = $user_name;           // Inviting Collaborator's name
        $this->pass = $pass;                     // password for new user
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.notifyNewProgramUser', [ // pass public variables (set in __construct) to notifyProgramAdmin.blade
            'program_title' => $this->program_title,
            'user_name' => $this->user_name,
            'pass' => $this->pass,
            ])
        ->subject('Program Collaboration Invitation');  // set subject to Invitation to Collaborate, see Mail docs for more info.
    }
}