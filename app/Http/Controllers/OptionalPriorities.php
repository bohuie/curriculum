<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseOptionalPriorities;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

//use App\Models\OptionalPriorities;

class OptionalPriorities extends Controller
{
    //
    /**
     * Store all new optional PLOs to table.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'course_id' => 'required',
        ]);

        $course_id = $request->input('course_id');
        $optionalPLOs_op_ids = $request->input('optionalItem');

        // Check If Any PLO's have been selected
        if ($optionalPLOs_op_ids != null) {
            // Get op_id's from optionalPLOs
            //$optionalPLOs_op_ids = DB::table('optional_priorities')->whereIn('optional_priority',$optionalPLOs)->pluck('op_id');

            // Delete all OptionalPLO's not checked (Selected).
            DB::table('course_optional_priorities')->whereNotIn('op_id', $optionalPLOs_op_ids)->where('course_id', $course_id)->delete();

            // Loop to insert them to the table
            foreach ($optionalPLOs_op_ids as $optionalPLO) {
                CourseOptionalPriorities::updateOrCreate(['course_id' => $course_id, 'op_id' => $optionalPLO]);
            }

            if (count($optionalPLOs_op_ids) == CourseOptionalPriorities::where('course_id', $course_id)->count()) {
                $request->session()->flash('success', 'Alignment to UBC/Ministry priorities updated.');
            } else {
                $request->session()->flash('error', 'There was an error updating the alignment to UBC/Ministry priorities.');
            }
        } else {
            // Remove Any PLO's based on their course ID
            DB::table('course_optional_priorities')->where('course_id', $course_id)->delete();
            $request->session()->flash('success', 'Alignment to UBC/Ministry priorities updated.');
        }
        // update courses 'updated_at' field
        $course = Course::find($course_id);
        $course->touch();

        // get users name for last_modified_user
        $user = User::find(Auth::id());
        $course->last_modified_user = $user->name;
        $course->save();

        return redirect()->route('courseWizard.step6', $request->input('course_id'));
    }
}
