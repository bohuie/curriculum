<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\OutcomeMap;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     */
    public function store(Request $request): RedirectResponse
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

        // get users name for last_modified_user
        $user = User::find(Auth::id());
        $course->last_modified_user = $user->name;
        $course->save();

        return redirect()->back()->with('success', 'Your answers have been saved successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(OutcomeMap $outcomeMap)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(OutcomeMap $outcomeMap)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OutcomeMap $outcomeMap)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(OutcomeMap $outcomeMap)
    {
        //
    }
}
