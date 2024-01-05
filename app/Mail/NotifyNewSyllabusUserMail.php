<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyNewSyllabusUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $syllabus_code;

    public $syllabus_num;

    public $syllabus_title;

    public $user_name;

    public $pass;

    public $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $syllabus_code, string $syllabus_num, string $syllabus_title, string $user_name, string $pass, string $email)
    {
        $this->syllabus_code = $syllabus_code;    // syllabus code (ex. COSC)
        $this->syllabus_num = $syllabus_num;      // syllabus num (ex. 121)
        $this->syllabus_title = $syllabus_title;  // syllabus title (ex. Intro to Computer Science)
        $this->user_name = $user_name;            // Inviting Collaborator's name
        $this->pass = $pass;                      // temporary password
        $this->email = $email;                    // email for new user
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this->markdown('emails.notifyNewSyllabusUser', [ // pass public variables (set in __construct) to notifySyllabusUser.blade
            'syllabus_code' => $this->syllabus_code,
            'syllabus_num' => $this->syllabus_num,
            'syllabus_title' => $this->syllabus_title,
            'user_name' => $this->user_name,
            'pass' => $this->pass,
            'email' => $this->email,
        ])
            ->subject('Syllabus Collaboration Invitation');  // set subject to Invitation to Collaborate, see Mail docs for more info.
    }
}
