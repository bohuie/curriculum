<?php

namespace App\Http\Controllers;

use App\Models\CourseProgram;
use App\Models\Program;
use App\Models\ProgramLearningOutcome;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

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
        // validate request data 
        $this->validate($request, [
            'program_id'=> 'required',
        ]);

        try {
            $programId = $request->input('program_id');
            // get this program
            $program = Program::find($programId);
            // get the current plos, their shortphrases and categories
            $currentPLOs = $request->input('current_pl_outcome');
            $currentPLOShortphrases = $request->input('current_pl_outcome_short_phrase');
            $currentPLOCategories = $request->input('current_plo_category');
            // get the new plos, their shortphrases and their categories
            $newPLOs = $request->input('new_pl_outcome');    
            $newPLOShortphrases = $request->input('new_pl_outcome_short_phrase');
            $newPLOCategories = $request->input('new_plo_category');
            // get the saved plos for this program
            $plos = $program->programLearningOutcomes;
            // update current plos
            foreach ($plos as $plo) {
                if (array_key_exists($plo->pl_outcome_id, $currentPLOs)) {
                    // save and update plo 
                    $plo->pl_outcome = $currentPLOs[$plo->pl_outcome_id];
                    $plo->plo_shortphrase = $currentPLOShortphrases[$plo->pl_outcome_id];
                    $plo->plo_category_id = $currentPLOCategories[$plo->pl_outcome_id];
                    $plo->save();
                } else {
                    // remove plo from program 
                    $plo->delete();
                }
            }
            // add new plos
            if ($newPLOs) {
                foreach ($newPLOs as $index => $plo) {
                    $newPLO = new ProgramLearningOutcome;
                    $newPLO->pl_outcome = $plo;
                    $newPLO->plo_shortphrase = $newPLOShortphrases[$index];
                    $newPLO->plo_category_id = $newPLOCategories[$index];
                    $newPLO->program_id = $programId;
                    $newPLO->save();
                }
                // new unmapped plos added, update map_status
                CourseProgram::where('program_id', $request->input('program_id'))->update(['map_status' => 0]);
            }
            // update which user last modified this program
            $user = User::find(Auth::id());
            $program->last_modified_user = $user->name;
            $program->save();
            $request->session()->flash('success','Your PLO categories were updated successfully!');
        } catch (Throwable $exception) {
            $message = 'There was an error updating your program learning outcomes';
            Log::error($message . ' ...\n');
            Log::error('Code - ' . $exception->getCode());
            Log::error('File - ' . $exception->getFile());
            Log::error('Line - ' . $exception->getLine());
            Log::error($exception->getMessage());
            $request->session()->flash('error', $message);
        } finally { 
            return redirect()->route('programWizard.step1', $request->input('program_id'));
        }

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

        $program = Program::find($request->input('program_id'));
        // get users name for last_modified_user
        $user = User::find(Auth::id());
        $program->last_modified_user = $user->name;
        $program->save();

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

        $program = Program::find($request->input('program_id'));
        // get users name for last_modified_user
        $user = User::find(Auth::id());
        $program->last_modified_user = $user->name;
        $program->save();
        
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