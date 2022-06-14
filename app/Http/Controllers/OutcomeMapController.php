<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\OutcomeMap;
use App\Models\User;
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

    public function index()
    {
        //
        return redirect()->back();
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
}
