<?php

namespace App\Http\Controllers;

use App\Models\CourseProgram;
use App\Models\Program;
use App\Models\ProgramLearningOutcome;
use Illuminate\Http\Request;

class ProgramLearningOutcomeController extends Controller
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
        //
        $this->validate($request, [
            
            'plo'=> 'required', 
            ]);

        $plo = new ProgramLearningOutcome;
        $plo->pl_outcome = $request->input('plo');
        $plo->plo_shortphrase = $request->input('title');
        $plo->program_id = $request->input('program_id');

        CourseProgram::where('program_id', $request->input('program_id'))->update(['map_status' => 0]);

        if($request->has('category')){
            $plo->plo_category_id = $request->input('category');
        }
        
        if($plo->save()){
            // update courses 'updated_at' field
            $program = Program::find($request->input('program_id'));
            $program->touch();

            $request->session()->flash('success', 'New program learning outcome saved');
        }else{
            $request->session()->flash('error', 'There was an error adding the program learning outcome');
        }
        
        return redirect()->route('programWizard.step1', $request->input('program_id'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProgramLearningOutcome  $programLearningOutcome
     * @return \Illuminate\Http\Response
     */
    public function show(ProgramLearningOutcome $programLearningOutcome)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProgramLearningOutcome  $programLearningOutcome
     * @return \Illuminate\Http\Response
     */
    public function edit(ProgramLearningOutcome $programLearningOutcome)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProgramLearningOutcome  $programLearningOutcome
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $programLearningOutcome)
    {
        //
        //
        $this->validate($request, [
            'plo'=> 'required',

            ]);

        $plo = ProgramLearningOutcome::where('pl_outcome_id', $programLearningOutcome)->first();
        $plo->pl_outcome = $request->input('plo');
        $plo->plo_shortphrase = $request->input('title');

        if($request->has('category')){
            $plo->plo_category_id = $request->input('category');
        }
        
        
        if($plo->save()){
            // update courses 'updated_at' field
            $program = Program::find($request->input('program_id'));
            $program->touch();

            $request->session()->flash('success', 'Program learning outcome updated');
        }else{
            $request->session()->flash('error', 'There was an error updating the program learning outcome');
        }

        return redirect()->route('programWizard.step1', $request->input('program_id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProgramLearningOutcome  $programLearningOutcome
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $programLearningOutcome)
    {
        //
        $plo = ProgramLearningOutcome::where('pl_outcome_id', $programLearningOutcome);
        
        if($plo->delete()){
            // update courses 'updated_at' field
            $program = Program::find($request->input('program_id'));
            $program->touch();
            
            $request->session()->flash('success','Program learning outcome has been deleted');
        }else{
            $request->session()->flash('error', 'There was an error deleting the program learning outcome');
        }

        return redirect()->route('programWizard.step1',$request->input('program_id'));
}
}