<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyInstructorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $course_code, $course_num, $course_title, $user_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(String $course_code, String $course_num, String $course_title, String $user_name)
    {
        $this->course_code = $course_code;    // course code (ex. COSC)
        $this->course_num = $course_num;      // course num (ex. 121)
        $this->course_title = $course_title;  // course title (ex. Intro to Computer Science)
        $this->user_name = $user_name;        // Inviting Collaborator's name
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
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
