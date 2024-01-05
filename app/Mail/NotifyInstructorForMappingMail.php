<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyInstructorForMappingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $course_code;

    public $course_num;

    public $course_title;

    public $program;

    public $program_user_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $program, string $program_user_name, string $course_code, string $course_num, string $course_title)
    {
        $this->program = $program;
        $this->program_user_name = $program_user_name;
        $this->course_code = $course_code;    // course code (ex. COSC)
        $this->course_num = $course_num;      // course num (ex. 121)
        $this->course_title = $course_title;  // course title (ex. Intro to Computer Science)
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this->markdown('emails.notifyInstructorForMapping', [ // pass public variables (set in __construct) to notifyInstructor.blade
            'program' => $this->program,
            'program_user_name' => $this->program_user_name,
            'course_code' => $this->course_code,
            'course_num' => $this->course_num,
            'course_title' => $this->course_title,
        ])
            ->subject('Invitation to map '.$this->course_title.' to '.$this->program);  // set subject to Invitation to Collaborate, see Mail docs for more info.
    }
}
