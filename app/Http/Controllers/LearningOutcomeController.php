<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\LearningOutcome;
use Illuminate\Http\Request;
use Throwable;

class LearningOutcomeController extends Controller
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
        // try to update CLOs
        try {
            $courseId = $request->input('course_id');
            $currentCLOs = $request->input('current_l_outcome');
            $currentShortPhrases = $request->input('current_l_outcome_short_phrase');
            $newCLOs = $request->input('new_l_outcomes');
            $newShortPhrases = $request->input('new_short_phrases');
            // get the course
            $course = Course::find($courseId);
            // get the saved CLOs for this course
            $clos = $course->learningOutcomes;
            // update current clos
            foreach ($clos as $clo) {
                if (array_key_exists($clo->l_outcome_id, $currentCLOs)) {
                    // save Clo, l_outcome and ShortPhrase
                    $clo->l_outcome = $currentCLOs[$clo->l_outcome_id];
                    $clo->clo_shortphrase = $currentShortPhrases[$clo->l_outcome_id];
                    $clo->save();
                } else {
                    // remove clo from course
                    $clo->delete();
                }
            }
            // add new clos
            if ($newCLOs) {
                foreach ($newCLOs as $index => $newCLO) {
                    $newLearningOutcome = new LearningOutcome;
                    $newLearningOutcome->l_outcome = $newCLO;
                    $newLearningOutcome->clo_shortphrase = $newShortPhrases[$index];
                    $newLearningOutcome->course_id = $courseId;
                    $newLearningOutcome->save();
                }
            }

            $request->session()->flash('success','Your course learning outcomes were updated successfully!');
        } catch (Throwable $exception) {
            $request->session()->flash('error', 'There was an error updating your course learning outcomes');
        } finally {
            return redirect()->route('courseWizard.step1', $request->input('course_id'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LearningOutcome  $learningOutcome
     * @return \Illuminate\Http\Response
     */
    public function show(LearningOutcome $learningOutcome)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LearningOutcome  $learningOutcome
     * @return \Illuminate\Http\Response
     */
    public function edit( $l_outcome_id)
    {
        //
        $lo = LearningOutcome::where('l_outcome_id', $l_outcome_id)->first();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LearningOutcome  $learningOutcome
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $l_outcome_id)
    {
        //
        $this->validate($request, [
            'l_outcome'=> 'required',
            ]);

        $lo = LearningOutcome::where('l_outcome_id', $l_outcome_id)->first();
        $lo->l_outcome = $request->input('l_outcome');
        $lo->clo_shortphrase = $request->input('title');
        
        if($lo->save()){
            $request->session()->flash('success', 'Course learning outcome updated');
        }else{
            $request->session()->flash('error', 'There was an error updating the course learning outcome');
        }
        
        return redirect()->route('courseWizard.step1', $request->input('course_id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LearningOutcome  $learningOutcome
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $l_outcome_id)
    {
        //
        $lo = LearningOutcome::where('l_outcome_id', $l_outcome_id)->first();

        if($lo->delete()){
            $request->session()->flash('success','Course learning outcome has been deleted');
        }else{
            $request->session()->flash('error', 'There was an error deleting the course learning outcome');
        }
        return redirect()->route('courseWizard.step1', $request->input('course_id'));
    }
}
