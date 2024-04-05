<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\LearningActivity;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class LearningActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(): RedirectResponse
    {
        //
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // try update student assessment methods
        try {
            $courseId = $request->input('course_id');
            $currentActivities = $request->input('current_l_activities');
            $newActivities = $request->input('new_l_activities');
            // get the course
            $course = Course::find($courseId);
            // case: delete all teaching and learning activities
            if (! $currentActivities && ! $newActivities) {
                Course::find($courseId)->learningActivities()->delete();
            }
            // get the saved assessment methods for this course
            $learningActivities = $course->learningActivities;
            // update current assessment methods
            foreach ($learningActivities as $learningActivity) {
                if (array_key_exists($learningActivity->l_activity_id, $currentActivities)) {
                    // save learning activity
                    $learningActivity->l_activity = $currentActivities[$learningActivity->l_activity_id];
                    $learningActivity->save();
                } else {
                    // remove learning activity from course
                    $learningActivity->delete();
                }
            }
            // add new learning activities
            if ($newActivities) {
                foreach ($newActivities as $index => $newActivity) {
                    $newLearningActivity = new LearningActivity;
                    $newLearningActivity->l_activity = $newActivity;
                    $newLearningActivity->course_id = $courseId;
                    $newLearningActivity->save();
                }
            }
            // update courses 'updated_at' field
            $course = Course::find($request->input('course_id'));
            $course->touch();

            // get users name for last_modified_user
            $user = User::find(Auth::id());
            $course->last_modified_user = $user->name;
            $course->save();

            $request->session()->flash('success', 'Your teaching and learning activities were updated successfully!');

        } catch (Throwable $exception) {
            // flash error message if something goes wrong
            $request->session()->flash('error', 'There was an error updating your teaching and learning activities');

        } finally {
            // return back to teaching and learning activities step
            return redirect()->route('courseWizard.step3', $request->input('course_id'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(LearningActivity $learningActivity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(LearningActivity $learningActivity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LearningActivity $learningActivity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LearningActivity  $learningActivity
     */
    public function destroy(Request $request, $l_activity_id): RedirectResponse
    {
        //
        $la = learningActivity::where('l_activity_id', $l_activity_id)->first();
        $course_id = $request->input('course_id');

        if ($la->delete()) {
            // update courses 'updated_at' field
            $course = Course::find($course_id);
            $course->touch();

            // get users name for last_modified_user
            $user = User::find(Auth::id());
            $course->last_modified_user = $user->name;
            $course->save();

            $request->session()->flash('success', 'Teaching/learning activity has been deleted');
        } else {
            $request->session()->flash('error', 'There was an error deleting the teaching/learning activity');
        }

        return redirect()->route('courseWizard.step3', $request->input('course_id'));
    }
}
