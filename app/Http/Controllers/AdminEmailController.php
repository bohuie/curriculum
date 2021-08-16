<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\TemplateEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AdminEmailController extends Controller
{
    /*
    SMTP SERVER WORKS LOCALLY ON VPN!

    Mail docs: https://laravel.com/docs/8.x/mail
    Laravel queries: https://laravel.com/docs/8.x/queries

    */

    public function __construct()
    {
        $this->middleware('auth');

    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        if(Gate::denies('admin-privilege')){ // This Gate checks if user is an admin
            return redirect(route('home'));  //   and redirects to home if they are not (security)
        }
        return view('pages.email');          // If they are, return email.blade page
    }

    public function send(Request $request){

        $subject = $request->input('email_subject');     // Receive the Subject input from email.blade page
        $title = $request->input('email_title');         // Receive the Title input from email.blade page
        $body = $request->input('email_body');           // Receive the Body input from email.blade page
        $role_id = $request->input('email_recipients');  // Receive the Role input from email.blade page
        $signature = $request->input('email_signature'); // Receive the Signature input from email.blade page

        if(is_null($signature)){
            $signature = "";
        }


        // Query the role_user table for all user_id's that have role_id matching the role above.
        $user_ids = DB::table('role_user')->where('role_id', $role_id)->get()->map(function($user) {
            return $user->user_id; // Map the results so that we only retrieve the use_id and remove irrelevant fields
        });
        
        // Query all the users from users table that have a user_id matching the ones from the role_user query. 
        $email_recipients = DB::table('users')->whereIn('id', $user_ids)->get();
        
        foreach ($email_recipients as $recipient) {  // Loop over recipient emails and send each one a separate email.
            // Pass subject, title, recipient name, body, and signature to TemplateEmail.blade
            Mail::to($recipient->email)->send(new TemplateEmail($subject, $title, $recipient->name, $body, $signature));  
        }
        
        return redirect()->back()->with('success', 'Email has been sent.');  // Green popup dialogue that confirms email has sent.
    }
}
