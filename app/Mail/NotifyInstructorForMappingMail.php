<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyInstructorForMappingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $course_code, $course_num, $course_title, $program, $program_user_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(String $program, String $program_user_name, String $course_code, String $course_num, String $course_title)
    {
        $this->program = $program;
        $this->program_user_name = $program_user_name;
        $this->course_code = $course_code;    // course code (ex. COSC)
        $this->course_num = $course_num;      // course num (ex. 121)
        $this->course_title = $course_title;  // course title (ex. Intro to Computer Science)
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.notifyInstructorForMapping', [ // pass public variables (set in __construct) to notifyInstructor.blade
            'program' => $this->program,
            'program_user_name' => $this->program_user_name,
            'course_code' => $this->course_code,            
            'course_num' => $this->course_num,
            'course_title' => $this->course_title,
            ])
        ->subject('Course Mapping Invitation');  // set subject to Invitation to Collaborate, see Mail docs for more info.
    }
}
