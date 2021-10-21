<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Program;
use App\Models\User;
use App\Models\Role;
use App\Models\Course;
use App\Models\CourseProgram;
use App\Models\CourseUser;
use App\Models\MappingScale;
use App\Models\MappingScaleProgram;
use App\Models\OutcomeMap;
use App\Models\PLOCategory;
use App\Models\ProgramLearningOutcome;
use App\Models\ProgramUser;
use Illuminate\Support\Facades\DB;
use PDF;
use Response;

class ProgramController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //
        $programs = User::join('program_users', 'users.id', '=', 'program_users.user_id')
                ->join('programs', 'program_users.program_id', "=", 'programs.program_id')
                ->select('programs.program_id','programs.program', 'programs.faculty', 'programs.level', 'programs.department', 'programs.status')
                ->where('program_users.user_id','=',Auth::id())
                ->get();

        $user = User::where('id', Auth::id())->first();
        return view('programs.index')->with('user', $user)->with('programs', $programs);
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
            'program'=> 'required',
            'level'=> 'required',
            'faculty'=> 'required',
            ]);

        $program = new Program;
        $program->program = $request->input('program');
        $program->level = $request->input('level');
        $program->department = $request->input('department');
        $program->faculty = $request->input('faculty');
        $program->status = -1;

        $programUser = new ProgramUser;
        $programUser->user_id = $request->input('user_id');
        
        if($program->save()){
            $request->session()->flash('success', 'New program added');
        }else{
            $request->session()->flash('error', 'There was an error Adding the program');
        }

        $programUser->program_id = $program->program_id;
        // assign the creator of the program the owner permission
        $programUser->permission = 1;
        $programUser->save();
        
        // $adminRole = Role::where('role','administrator')->first();
        // $user = User::where('id', Auth::id())->first();

        // if($user->hasRole('administrator') == false){
        //     $user->roles()->attach($adminRole);
        // }
        
        return redirect()->route('home');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $program_id)
    {
        //
        $this->validate($request, [
            'program'=> 'required',
            'level'=> 'required',
            'faculty'=> 'required',
            ]);

        $program = Program::where('program_id', $program_id)->first();
        $program->program = $request->input('program');
        $program->level = $request->input('level');
        $program->department = $request->input('department');
        $program->faculty = $request->input('faculty');

        if($program->save()){
            $request->session()->flash('success', 'Program updated');
        }else{
            $request->session()->flash('error', 'There was an error updating the program');
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $program_id)
    {
        // find the program to delete
        $program = Program::find($program_id);
        // find the current user
        $currentUser = User::find(Auth::id());
        //get the current users permission level for the program delete
        $currentUserPermission = $currentUser->programs->where('program_id', $program_id)->first()->pivot->permission;
        // if the current user own the program, then try to delete it
        if ($currentUserPermission == 1) {
            if($program->delete()){
                $request->session()->flash('success','Program has been deleted');
            }else{
                $request->session()->flash('error', 'There was an error deleting the program');
            }
        } else {
            $request->session()->flash('error','You do not have permission to delete this program');
        }
        return redirect()->route('home');
    }

    public function submit(Request $request, $program_id)
    {
        //
        $p = Program::where('program_id', $program_id)->first();
        $p->status = 1;
        
        if($p->save()){
            $request->session()->flash('success','Program settings have been submitted');
        }else{
            $request->session()->flash('error', 'There was an error submitting the program settings');
        }

        return redirect()->route('home');
    }

    public function pdf(Request $request, $program_id) {

        $user = User::where('id',Auth::id())->first();

        //header
        $faculties = array("Faculty of Arts and Social Sciences", "Faculty of Creative and Critical Studies", "Okanagan School of Education", "School of Engineering", "School of Health and Exercise Sciences", "Faculty of Management", "Faculty of Science", "Faculty of Medicine", "College of Graduate Studies", "School of Nursing", "School of Social Work", "Other");
        $departments = array("Community, Culture and Global Studies", "Economics, Philosophy and Political Science", "History and Sociology", "Psychology", "Creative Studies", "Languages and World Literature", "English and Cultural Studies", "Biology", "Chemistry", "Computer Science, Mathematics, Physics and Statistics", "Earth, Environmental and Geographic Sciences", "Other" );
        $levels = array("Undergraduate", "Graduate", "Other");
        $user = User::where('id',Auth::id())->first();
        $programUsers = ProgramUser::join('users','program_users.user_id',"=","users.id")
                                ->select('users.email','program_users.user_id','program_users.program_id')
                                ->where('program_users.program_id','=',$program_id)->get();

        //
        $program = Program::where('program_id', $program_id)->first();

        //progress bar
        $ploCount = ProgramLearningOutcome::where('program_id', $program_id)->count();
        $msCount = MappingScale::join('mapping_scale_programs', 'mapping_scales.map_scale_id', "=", 'mapping_scale_programs.map_scale_id')
                                    ->where('mapping_scale_programs.program_id', $program_id)->count();
        //
        $courseCount = CourseProgram::where('program_id', $program_id)->count();
        //
        $mappingScales = MappingScale::join('mapping_scale_programs', 'mapping_scales.map_scale_id', "=", 'mapping_scale_programs.map_scale_id')
                                    ->where('mapping_scale_programs.program_id', $program_id)->get();

        // get all the courses this program belongs to
        $programCourses = $program->courses;

        // get all of the required courses this program belongs to
        $requiredProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->where('course_programs.course_required', 1)->get();

        // get all categories for program
        $ploCategories = PLOCategory::where('program_id', $program_id)->get();
        // get plo categories for program
        $ploProgramCategories = PLOCategory::where('p_l_o_categories.program_id', $program_id)->join('program_learning_outcomes', 'p_l_o_categories.plo_category_id', '=', 'program_learning_outcomes.plo_category_id')->get();
        // get all plo's
        $allPLO = ProgramLearningOutcome::where('program_id', $program_id)->get();
        // get plo's for the program 
        $plos = DB::table('program_learning_outcomes')->leftJoin('p_l_o_categories', 'program_learning_outcomes.plo_category_id', '=', 'p_l_o_categories.plo_category_id')->where('program_learning_outcomes.program_id', $program_id)->get();
        // get UnCategorized PLO's
        $unCategorizedPLOS = DB::table('program_learning_outcomes')->leftJoin('p_l_o_categories', 'program_learning_outcomes.plo_category_id', '=', 'p_l_o_categories.plo_category_id')->where('program_learning_outcomes.program_id', $program_id)->where('program_learning_outcomes.plo_category_id', null)->get();

        // returns the number of Categories that contain at least one PLO
        $numCatUsed = 0;
        $uniqueCategories = array();
        foreach ($ploProgramCategories as $ploInCategory) {
            if (!in_array($ploInCategory->plo_category_id, $uniqueCategories)) {
                $uniqueCategories[] += $ploInCategory->plo_category_id;
                $numCatUsed++;
            }
        }
        
        // plosPerCategory returns the number of plo's belonging to each category
        // used for setting the colspan in the view
        $plosPerCategory = array();
        foreach($ploProgramCategories as $ploCategory) {
            $plosPerCategory[$ploCategory->plo_category_id] = 0;
        }
        foreach($ploProgramCategories as $ploCategory) {
            $plosPerCategory[$ploCategory->plo_category_id] += 1;
        }
        
        // Used for setting colspan in view
        $numUncategorizedPLOS = 0;
        foreach ($allPLO as $plo) {
            if ($plo->plo_category_id == null){
                $numUncategorizedPLOS ++;
            }
        }

        // returns true if there exists a plo without a category
        $hasUncategorized = false;
        foreach ($plos as $plo) {
            if ($plo->plo_category == NULL) {
                $hasUncategorized = true;
            }
        }

        // All Courses Frequency Distribution
        $coursesOutcomes = array();
        $coursesOutcomes = $this->getCoursesOutcomes($coursesOutcomes, $programCourses);
        $arr = array();
        $arr = $this->getOutcomeMaps($allPLO, $coursesOutcomes, $arr);
        $store = array();
        $store = $this->createCDFArray($arr, $store);
        $store = $this->frequencyDistribution($arr, $store);
        $store = $this->replaceIdsWithAbv($store, $arr);
        $store = $this->assignColours($store);

        // Required Courses Frequency Distribution
        $coursesOutcomes = array();
        $coursesOutcomes = $this->getCoursesOutcomes($coursesOutcomes, $requiredProgramCourses);
        $arrRequired = array();
        $arrRequired = $this->getOutcomeMaps($allPLO, $coursesOutcomes, $arrRequired);
        $storeRequired = array();
        $storeRequired = $this->createCDFArray($arrRequired, $storeRequired);
        $storeRequired = $this->frequencyDistribution($arrRequired, $storeRequired);
        $storeRequired = $this->replaceIdsWithAbv($storeRequired, $arrRequired);
        $storeRequired = $this->assignColours($storeRequired);

        $pdf = PDF::loadView('programs.downloadSummary', compact('program','ploCount','msCount','courseCount','mappingScales','programCourses','requiredProgramCourses','ploCategories','ploProgramCategories','allPLO','plos','unCategorizedPLOS','numCatUsed','uniqueCategories','plosPerCategory','numUncategorizedPLOS','hasUncategorized','store','requiredProgramCourses','storeRequired'));

        return $pdf->download('summary.pdf');
    }

    public function getCoursesOutcomes($coursesOutcomes, $programCourses) {
        // get all CLO's for each course in the program
        foreach ($programCourses as $programCourse) {
            $learningOutcomes = $programCourse->learningOutcomes;
            $coursesOutcomes[$programCourse->course_id] = $learningOutcomes;
        }
        return $coursesOutcomes;
    }

    public function getOutcomeMaps ($allPLO, $coursesOutcomes, $arr) {
        // retrieves all the outcome mapping values for every clo and plo
        $count = 0;
        foreach ($allPLO as $plo) {
            // loop through CLOs to get map scale value
            foreach ($coursesOutcomes as $clos) {
                foreach ($clos as $clo) {
                    // Check if record exists in the db
                    if (!OutcomeMap::where(['l_outcome_id' => $clo->l_outcome_id, 'pl_outcome_id' => $plo->pl_outcome_id])->exists()) {
                        // if nothing is found then do nothing
                        // else if record (mapping_scale_id) is found then store it in the array
                    } else {
                        $count++;
                        $mapScaleValue = OutcomeMap::where(['l_outcome_id' => $clo->l_outcome_id, 'pl_outcome_id' => $plo->pl_outcome_id])->value('map_scale_id');
                        $arr[$count] = array(
                            'pl_outcome_id' => $plo->pl_outcome_id,
                            'course_id' => $clo->course_id,
                            'map_scale_id' => $mapScaleValue,
                            'l_outcome_id' => $clo->l_outcome_id,
                        );
                    }
                }
            }
        }
        return $arr;
    }

    public function createCDFArray($arr, $store) {
        // Initialize array for each pl_outcome_id with the value of null
        foreach ($arr as $ar) {
            $store[$ar['pl_outcome_id']] = null;
        }
        // Initialize Array for Storing 
        foreach ($arr as $ar) {
            if ($store[$ar['pl_outcome_id']] == null || $store[$ar['pl_outcome_id']] == $ar['pl_outcome_id']) {
                $store[$ar['pl_outcome_id']] = array(
                    $ar['course_id'] => array(
                    ),
                );
            } else {
                $store[$ar['pl_outcome_id']][$ar['course_id']] = array();
                $store[$ar['pl_outcome_id']][$ar['course_id']]['frequencies'] = array();
            }
        }
        return $store;
    }

    public function frequencyDistribution($arr, $store) {
        //Initialize Array for Frequency Distribution
        $freq = array();
        foreach ($arr as $map) {
            $pl_outcome_id = $map['pl_outcome_id'];
            $course_id = $map['course_id'];
            $map_scale_id = $map['map_scale_id'];
            //Initialize Array with the value of zero
            $freq[$pl_outcome_id][$course_id][$map_scale_id] = 0;
        }
        // Store values in the frequency distribution array that was initialized to zero above
        foreach ($arr as $map) {
            $pl_outcome_id = $map['pl_outcome_id'];
            $course_id = $map['course_id'];
            $map_scale_id = $map['map_scale_id'];
            // check if map_scale_value is in the frequency array and give it the value of 1
            if ($freq[$pl_outcome_id][$course_id][$map_scale_id] == 0) {
                $freq[$pl_outcome_id][$course_id][$map_scale_id] = 1;
            // if the value is found again, and is not zero, increment
            } else {
                $freq[$pl_outcome_id][$course_id][$map_scale_id] += 1;
            }
        }
        // loop through the frequencies of the mapping values
        foreach($freq as $plOutcomeId => $dist) {
            foreach($dist as $courseId => $d) {
                $weight = 0;
                $tieResults = array();
                $id = NULL;
                //count the number of times a mapping scales appears for a program learning outcome 
                foreach($d as $ms_Id => $mapScaleWeight) {
                    //check if the current ($mapScaleWeight) > than the previously stored value
                    if ($weight < $mapScaleWeight) {
                        $weight = $mapScaleWeight;
                        $id = $ms_Id;
                    }
                }
                // Check if the largest weighted value ties with another value
                foreach($d as $ms_Id => $mapScaleWeight) {
                    if ($weight == $mapScaleWeight && $id != $ms_Id) {    // if a tie is found store the mapping scale values (I.e: I, A, D) in and array
                        $tieResults = array_keys($d, $weight);
                    }
                }
                // if A tie is found.. 
                if ($tieResults != null) {
                    $stringResults = '';
                    $numItems = count($tieResults);
                    $i = 0;
                    // for each tie value append to a string
                    foreach ($tieResults as $tieResult) {
                        // appends '/' only if it's not at the last index in the array
                        if (++$i !== $numItems) {
                            $stringResults .= "" .MappingScale::where('map_scale_id', $tieResult)->value('abbreviation'). " / "; 
                        } else {
                            $stringResults .= "" .MappingScale::where('map_scale_id', $tieResult)->value('abbreviation');
                        }
                    }
                    // Store the results array as the map_scale_value key
                    $store[$plOutcomeId][$courseId] += array(
                        'map_scale_abv' => $stringResults 
                    );
                    // Store a new array to be able to determine if the mapping scale value comes from the result of a tie
                    $store[$plOutcomeId][$courseId] += array(
                        'map_scale_id_tie' => True
                    );
                    // Store the frequencies
                    $store[$plOutcomeId][$courseId]['frequencies'] = $freq[$plOutcomeId][$courseId];
                } else {
                    // If no tie is present, store the strongest weighted map_scale_value 
                    $store[$plOutcomeId][$courseId] = array(
                        'map_scale_id' => array_search($weight, $d)
                    );
                    $store[$plOutcomeId][$courseId] += array(
                        'map_scale_abv' => MappingScale::where('map_scale_id', array_search($weight, $d))->value('abbreviation')
                    );
                    // Store the frequencies
                    $store[$plOutcomeId][$courseId]['frequencies'] = $freq[$plOutcomeId][$courseId];
                }
            }
        }
        return $store;
    }

    public function replaceIdsWithAbv($store, $arr) {
        //Initialize Array for Frequency Distribution
        $freq = array();
        foreach ($arr as $map) {
            $pl_outcome_id = $map['pl_outcome_id'];
            $course_id = $map['course_id'];
            $map_scale_id = MappingScale::where('map_scale_id', $map['map_scale_id'])->value('abbreviation');
            //Initialize Array with the value of zero
            $freq[$pl_outcome_id][$course_id][$map_scale_id] = 0;
        }
        // Store values in the frequency distribution array that was initialized to zero above
        foreach ($arr as $map) {
            $pl_outcome_id = $map['pl_outcome_id'];
            $course_id = $map['course_id'];
            $map_scale_id = MappingScale::where('map_scale_id', $map['map_scale_id'])->value('abbreviation');
            // check if map_scale_value is in the frequency array and give it the value of 1
            if ($freq[$pl_outcome_id][$course_id][$map_scale_id] == 0) {
                $freq[$pl_outcome_id][$course_id][$map_scale_id] = 1;
            // if the value is found again, and is not zero, increment
            } else {
                $freq[$pl_outcome_id][$course_id][$map_scale_id] += 1;
            }
        }
        foreach($freq as $plOutcomeId => $dist) {
            foreach($dist as $courseId => $d) {
                // Store the frequencies
                $store[$plOutcomeId][$courseId]['frequencies'] = $freq[$plOutcomeId][$courseId];
            }
        }
        return $store;
    }

    public function assignColours($store){
        // Assign a colour to store based
        foreach ($store as $plOutcomeId => $s) {
            foreach ($s as $courseId => $msv) {
                // If a tie exists assign it the colour white
                if (array_key_exists("map_scale_id_tie",$msv)) {
                    $mapScaleColour = '#FFFFFF';
                    $store[$plOutcomeId][$courseId] += array(
                        'colour' => $mapScaleColour
                    );
                } else {
                    // Search for the mapping scale colour in the db, then assign it to the array
                    $mapScaleColour = MappingScale::where('map_scale_id', $msv['map_scale_id'])->value('colour');
                
                if ($mapScaleColour == null) {
                    $mapScaleColour = '#FFFFFF';
                }
                    $store[$plOutcomeId][$courseId] += array(
                        'colour' => $mapScaleColour
                    );
                }
            }
        }
        return $store;
    }

    public function duplicate(Request $request, $program_id) {

        //
        $this->validate($request, [
            'program'=> 'required',
            ]);
        
        $oldProgram = Program::find($program_id);

        $program = new Program;
        $program->program = $request->input('program');
        $program->level = $oldProgram->level;
        $program->department = $oldProgram->department;
        $program->faculty = $oldProgram->faculty;
        $program->status = -1;
        $program->save();

        // This array is used to keep track of the id's for each category duplicated
        // This is used for the program learning outcomes step to determine which plo belongs to which category
        $historyCategories = array();
        // duplicate plo categories
        $ploCategories = $oldProgram->ploCategories;
        foreach ($ploCategories as $ploCategory) {
            $newCategory = new PLOCategory;
            $newCategory->plo_category = $ploCategory->plo_category;
            $newCategory->program_id = $program->program_id;
            $newCategory->save();
            $historyCategories[$ploCategory->plo_category_id] = $newCategory->plo_category_id;
        }

        // duplicate plos
        $plos = $oldProgram->programLearningOutcomes;
        foreach ($plos as $plo) {
            $newProgramLearningOutcome = new ProgramLearningOutcome;
            $newProgramLearningOutcome->plo_shortphrase = $plo->plo_shortphrase;
            $newProgramLearningOutcome->pl_outcome = $plo->pl_outcome;
            $newProgramLearningOutcome->program_id = $program->program_id;
            if ($plo->plo_category_id == NULL) {
                $newProgramLearningOutcome->plo_category_id = NULL;
            } else {
                $newProgramLearningOutcome->plo_category_id = $historyCategories[$plo->plo_category_id];
            }
            $newProgramLearningOutcome->save();
        }

        // duplicate mapping scales
        $mapScalesProgram = $oldProgram->mappingScalePrograms;
        foreach ($mapScalesProgram as $mapScaleProgram) {
            $mapScale = MappingScale::find($mapScaleProgram->map_scale_id);
            // if mapping scale category is NULL then it is a custom mapping scale. This means we will need to duplicate it in order to add it to the new program.
            if ($mapScale->mapping_scale_categories_id == NULL) {
                // create new mapping scale
                $newMappingScale = new MappingScale;
                $newMappingScale->title = $mapScale->title;
                $newMappingScale->abbreviation = $mapScale->abbreviation;
                $newMappingScale->description = $mapScale->description;
                $newMappingScale->colour = $mapScale->colour;
                $newMappingScale->save();

                // create new mapping scale program
                $newMappingScaleProgram = new MappingScaleProgram;
                $newMappingScaleProgram->map_scale_id = $newMappingScale->map_scale_id;
                $newMappingScaleProgram->program_id = $program->program_id;
                $newMappingScaleProgram->save();
            } else {
                // create new mapping scale program
                $newMappingScaleProgram = new MappingScaleProgram;
                $newMappingScaleProgram->map_scale_id = $mapScaleProgram->map_scale_id;
                $newMappingScaleProgram->program_id = $program->program_id;
                $newMappingScaleProgram->save();
            }
        }
        
        $user = User::find(Auth::id());
        $programUser = new ProgramUser;
        $programUser->user_id = $user->id;

        $programUser->program_id = $program->program_id;
        // assign the creator of the program the owner permission
        $programUser->permission = 1;
        if($programUser->save()){
            $request->session()->flash('success', 'Program has been successfully duplicated');
        }else{
            $request->session()->flash('error', 'There was an error duplicating the program');
        }

        return redirect()->route('home');
    }

}
