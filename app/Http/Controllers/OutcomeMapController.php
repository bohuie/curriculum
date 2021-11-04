<?php

namespace App\Http\Controllers;

use App\Models\ProgramLearningOutcome;
use App\Models\LearningOutcome;
use App\Models\Course;
use App\Models\OutcomeMap;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutcomeMapController extends Controller
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
        $this->validate($request, [
            'map' => 'required',
            ]);

        $outcomeMap = $request->input('map');
        
        // dd($outcomeMap);

        foreach ($outcomeMap as $cloId => $ploToScaleIds) {
            foreach (array_keys($ploToScaleIds) as $ploId) {
                DB::table('outcome_maps')->updateOrInsert(
                    ['pl_outcome_id' => $ploId, 'l_outcome_id' => $cloId],
                    ['map_scale_id' => $outcomeMap[$cloId][$ploId]]
                );
            }
        }
        // update courses 'updated_at' field
        $course = Course::find($request->input('course_id'));
        $course->touch();

        return redirect()->back()->with('success', 'Your answers have been saved successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OutcomeMap  $outcomeMap
     * @return \Illuminate\Http\Response
     */
    public function show(OutcomeMap $outcomeMap)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OutcomeMap  $outcomeMap
     * @return \Illuminate\Http\Response
     */
    public function edit(OutcomeMap $outcomeMap)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OutcomeMap  $outcomeMap
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OutcomeMap $outcomeMap)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OutcomeMap  $outcomeMap
     * @return \Illuminate\Http\Response
     */
    public function destroy(OutcomeMap $outcomeMap)
    {
        //
    }
}
