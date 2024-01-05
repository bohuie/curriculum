<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyInstructorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $course_code;

    public $course_num;

    public $course_title;

    public $user_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $course_code, string $course_num, string $course_title, string $user_name)
    {
        $this->course_code = $course_code;    // course code (ex. COSC)
        $this->course_num = $course_num;      // course num (ex. 121)
        $this->course_title = $course_title;  // course title (ex. Intro to Computer Science)
        $this->user_name = $user_name;        // Inviting Collaborator's name
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this->markdown('emails.notifyInstructor', [ // pass public variables (set in __construct) to notifyInstructor.blade
            'course_code' => $this->course_code,
            'course_num' => $this->course_num,
            'course_title' => $this->course_title,
            'user_name' => $this->user_name,
        ])
            ->subject('Course Collaboration Invitation');  // set subject to Invitation to Collaborate, see Mail docs for more info.
    }
}
