<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StandardsOutcomeMapController extends Controller
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {

        $this->validate($request, [
            'map' => 'required',
        ]);

        $outcomeMap = $request->input('map');
        foreach ($outcomeMap as $courseId => $standardToScaleIds) {
            foreach (array_keys($standardToScaleIds) as $standardId) {
                DB::table('standards_outcome_maps')->updateOrInsert(
                    ['standard_id' => $standardId, 'course_id' => $courseId],
                    ['standard_scale_id' => $outcomeMap[$courseId][$standardId]]
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
