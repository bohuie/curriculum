<?php

namespace App\Http\Controllers;

use App\Models\AssessmentMethod;
use App\Models\Course;
use Illuminate\Http\Request;
use Throwable;

class AssessmentMethodController extends Controller
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
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // try update student assessment methods
        try {
            $courseId = $request->input('course_id');
            $currentMethods = $request->input('current_a_methods');
            $currentWeights = $request->input('current_weights');
            $newMethods = $request->input('new_a_methods');
            $newWeights = $request->input('new_weights');
            // get the course
            $course = Course::find($courseId);
            // get the saved assessment methods for this course
            $assessmentMethods = $course->assessmentMethods;
            // update current assessment methods
            foreach ($assessmentMethods as $assessmentMethod) {
                if (array_key_exists($assessmentMethod->a_method_id, $currentMethods)) {
                    // save assessment method weight and title
                    $assessmentMethod->a_method = $currentMethods[$assessmentMethod->a_method_id];
                    $assessmentMethod->weight = $currentWeights[$assessmentMethod->a_method_id];
                    $assessmentMethod->save();
                } else {
                    // remove assessment method from course
                    $assessmentMethod->delete();
                }
            }
            // add new assessment methods
            if ($newMethods) {
                foreach ($newMethods as $index => $newMethod) {
                    $newAssessmentMethod = new AssessmentMethod;
                    $newAssessmentMethod->a_method = $newMethod;
                    $newAssessmentMethod->weight = $newWeights[$index];
                    $newAssessmentMethod->course_id = $courseId;
                    $newAssessmentMethod->save();
                }
            }

            $request->session()->flash('success','Your student assessments methods were updated successfully!');
        // flash error message if something goes wrong
        } catch (Throwable $exception) {
            $request->session()->flash('error', 'There was an error updating your student assessment methods');
        // return back to student assessment methods step
        } finally {
            return redirect()->route('courseWizard.step2', $request->input('course_id'));
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AssessmentMethod  $assessmentMethod
     * @return \Illuminate\Http\Response
     */
    public function show(AssessmentMethod $assessmentMethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AssessmentMethod  $assessmentMethod
     * @return \Illuminate\Http\Response
     */
    public function edit($assessmentMethod)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AssessmentMethod  $assessmentMethod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $a_method_id)
    {
        //
        $this->validate($request, [
            'a_method'=> 'required',
            'weight'=> 'required',
            ]);

        $am = AssessmentMethod::where('a_method_id', $a_method_id)->first();

        $totalWeight = AssessmentMethod::where('course_id', $request->input('course_id'))->sum('weight');
        if($totalWeight + $request->input('weight') - $am->weight > 100){
            return redirect()->route('courseWizard.step2', $request->input('course_id'))->with('error', 'The total weight of all assessments will exceed 100%');
        }


        $am->a_method = $request->input('a_method');
        $am->weight = $request->input('weight');
        //$am->course_id = $request->input('course_id');


        if($am->save()){
            $request->session()->flash('success', 'Student assessment method updated');
        }else{
            $request->session()->flash('error', 'There was an error updating the student assessment method');
        }


        return redirect()->route('courseWizard.step2', $request->input('course_id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AssessmentMethod  $assessmentMethod
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $a_method_id)
    {
        $am = AssessmentMethod::where('a_method_id', $a_method_id)->first();
        $course_id = $request->input('course_id');


        if($am->delete()){
            $request->session()->flash('success','Student assessment method has been deleted');
        }else{
            $request->session()->flash('error', 'There was an error deleting the student assessment method');
        }
        return redirect()->route('courseWizard.step2', $course_id);
    }

}
