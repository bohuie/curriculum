<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\Department;
use App\Models\Faculty;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Element\Table;
use Illuminate\Support\Facades\Log;
use App\Models\syllabus\Syllabus;
use App\Models\syllabus\OkanaganSyllabus;
use App\Models\syllabus\OkanaganSyllabusResource;
use App\Models\syllabus\SyllabusResourceOkanagan;
use App\Models\syllabus\SyllabusResourceVancouver;
use App\Models\syllabus\VancouverSyllabus;
use App\Models\syllabus\SyllabusUser;
use App\Models\syllabus\VancouverSyllabusResource;
use App\Models\SyllabusInstructor;
use Carbon\Carbon;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

define("INPUT_TIPS", array(
    "otherCourseStaff" => "If others lead face-to-face components such as tutorials or labs, let students know that they will meet them and be introduced in those sessions. Are others involved in marking homework? If so, do you want to identify them and provide contact information to students or have inquiries come to you?",
    "learningOutcomes" => "Tell students what changes in their knowledge, skills, or attitudes should occur during the course. Knowing these, students will have a framework within which to put individual components of the course and they will be primed for the kinds of assessments of learning that will come.",
    "learningAssessments" => "Identify the various ways you will assess achievement of stated learning outcomes or objectives, when each will occur, and the weighting of each component in the final grade. Sometimes your assessment plan will need to be adjusted, you must discuss the proposal with the class and provide a rationale and then update the syllabus. A new, dated electronic syllabus must be provided",
    "learningActivities" => "Do you expect students to participate in class? In what ways? (e.g., case studies, using “clickers” to answer questions, working in small groups, etc.) Is participation in on-line discussions required? Are readings required in advance with answers to be submitted to discussion questions or problem sets? 
    Is an oral presentation required? Is there a field excursion?",
    "learningMaterials" => "List of required learning materials for your course and where they might be obtained (e.g. the Bookstore if you ordered a text or a reading package, your department office if an in-house resource is available). Providing students with at least an estimate of the costs of materials is expected. Explanation of any on-line learning management system used (e.g.Canvas).",
    "latePolicy" => "State your policies on re-grading of marked work and on late submissions. What are the penalties for late assignments?",
    "missedActivityPolicy" => "In accordance with policy on Grading Practices, state how you deal with missed in-class assessments (e.g., are make-up tests offered for missed in-class tests, do you count the best X of Y assignments/tests, do you re-weight the marks from a missed test onto later assessments?",
    "courseDescription" => "As in the Academic Calendar or, for courses without a published description, include a brief representative one.", 
    "coursePrereqs" => "Is there a course that students must have passed before taking this course?",
    "courseCoreqs" => "Is there a course that students must take concurrently (if not before)?",
    "courseContacts" => "Include any and all contact information you are willing to have students use. If you have a preferred mode, state it. For example, do you accept email inquiries? What is your typical response time?", 
    "officeHours" => "Do you have set office hours or can students make appointments? Do you hold “office hours” online? If so, how do students access you?",
    "courseStructure" => "First, the basic components: lecture, lab, discussion, tutorial. Typically the locations are on the Student Service Centre but you may wish to include them. Then a description of how your classes are structured: Do you use traditional lecturing? Do you provide notes (outlines)? Do you combine on-line and in-class activity? You may wish to combine this section and Learning Outcomes below to provide an opportunity to introduce students to your philosophy of learning, to the culture of your discipline and how this course fits in the larger context.",
    "courseSchedule" => "This may be a weekly schedule, it may be class by class, but let students know that if changes occur, they will be informed.",
    "instructorBioStatement" => "You may wish to include your department/faculty/school and other information about your academic qualifications, interests, etc.",
    "learningResources" => "Include information on any resources to support student learning that are supported by the academic unit responsible for the course.",
    "learningAnalytics" => "If your course or department has a learning resource centre (physical or virtual), inform your students. Who will students encounter there? Are the staff knowledgeable about this course?",
    "officeLocation" => "Building & Room Number",
));


class SyllabusController extends Controller
{

    //
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('hasAccess');
    }

    public function index($syllabusId = null, Request $request) {
        // get the current user
        $user = User::find(Auth::id());
        // get this users courses
        $myCourses = $user->courses;
        // get vancouver campus resources
        $vancouverSyllabusResources = VancouverSyllabusResource::all();
        // get okanagan campus resources
        $okanaganSyllabusResources = OkanaganSyllabusResource::all();
        // get faculties
        $faculties =  Faculty::orderBy('faculty')->get();
        // get departments
        $departments =  Department::orderBy('department')->get();
        // ddd($faculties);

        if ($syllabusId != null) {
            // get this users permission level 
            $userPermission = $user->syllabi->where('id', $syllabusId)->first()->pivot->permission;
            
            // show view based on user permission
            switch ($userPermission) {
                // owner
                case 1:
                    return $this->syllabusEditor($syllabusId, array("user" => $user, "myCourses" => $myCourses, "vancouverSyllabusResources" => $vancouverSyllabusResources, "okanaganSyllabusResources" => $okanaganSyllabusResources, "faculties" => $faculties, "departments" => $departments));

                break;
                case 2:
                    // editor
                    return $this->syllabusEditor($syllabusId, array("user" => $user, "myCourses" => $myCourses, "vancouverSyllabusResources" => $vancouverSyllabusResources, "okanaganSyllabusResources" => $okanaganSyllabusResources, "faculties" => $faculties, "departments" => $departments));
                break;
                // viewer
                case 3:
                    return $this->syllabusViewer($syllabusId, array("vancouverSyllabusResources" => $vancouverSyllabusResources, "okanaganSyllabusResources" => $okanaganSyllabusResources));

                break;
                // return view to create a syllabus as default
                default:
                    return view("syllabus.syllabusGenerator")->with('user', $user)->with('myCourses', $myCourses)->with('inputFieldDescriptions', INPUT_TIPS)->with('okanaganSyllabusResources', $okanaganSyllabusResources)->with('vancouverSyllabusResources', $vancouverSyllabusResources)->with('faculties', $faculties)->with('departments', $departments)->with('syllabus', []);
            }

        // return view to create a syllabus
        } else {
            return view("syllabus.syllabus")->with('user', $user)->with('myCourses', $myCourses)->with('inputFieldDescriptions', INPUT_TIPS)->with('okanaganSyllabusResources', $okanaganSyllabusResources)->with('vancouverSyllabusResources', $vancouverSyllabusResources)->with('faculties', $faculties)->with('departments', $departments)->with('syllabus', []);
        }
    }


    public function syllabusEditor($syllabusId, $data) {
        // get this syllabus
        $syllabus = Syllabus::find($syllabusId);
        $syllabusInstructors = SyllabusInstructor::where('syllabus_id', $syllabusId)->get();
        $courseScheduleTblRowsCount = CourseSchedule::where('syllabus_id', $syllabus->id)->where('col', 0)->get()->count();
        $courseScheduleTblColsCount = CourseSchedule::where('syllabus_id', $syllabus->id)->where('row', 0)->get()->count();
        $courseScheduleTbl['rows'] = CourseSchedule::where('syllabus_id', $syllabus->id)->get()->chunk($courseScheduleTblColsCount);
        $courseScheduleTbl['numCols'] = $courseScheduleTblColsCount;
        $courseScheduleTbl['numRows'] = $courseScheduleTblRowsCount;
        
        switch ($syllabus->campus) {
            case 'O':
                // get data specific to okanagan campus
                $okanaganSyllabus = OkanaganSyllabus::where('syllabus_id', $syllabus->id)->first();
                // get selected okanagan syllabus resource
                $selectedOkanaganSyllabusResourceIds = SyllabusResourceOkanagan::where('syllabus_id', $syllabus->id)->pluck('o_syllabus_resource_id')->toArray();
                // return view with okanagan syllabus data
                return view("syllabus.syllabus")->with('user', $data['user'])->with('myCourses', $data['myCourses'])->with('syllabusInstructors', $syllabusInstructors)->with('myCourseScheduleTbl', $courseScheduleTbl)->with('courseScheduleTblRowsCount', $courseScheduleTblRowsCount)->with('inputFieldDescriptions', INPUT_TIPS)->with('okanaganSyllabusResources', $data['okanaganSyllabusResources'])->with('vancouverSyllabusResources', $data['vancouverSyllabusResources'])->with('syllabus', $syllabus)->with('okanaganSyllabus', $okanaganSyllabus)->with('selectedOkanaganSyllabusResourceIds', $selectedOkanaganSyllabusResourceIds)->with('faculties', $data['faculties'])->with('departments', $data['departments']);
            break;
            case 'V':
                // get data specific to vancouver campus
                $vancouverSyllabus = VancouverSyllabus::where('syllabus_id', $syllabusId)->first();
                // get selected vancouver syllabus resource
                $selectedVancouverSyllabusResourceIds = SyllabusResourceVancouver::where('syllabus_id', $syllabus->id)->pluck('v_syllabus_resource_id')->toArray();
                // return view with vancouver syllabus data
                return view("syllabus.syllabus")->with('user', $data['user'])->with('myCourses', $data['myCourses'])->with('syllabusInstructors', $syllabusInstructors)->with('myCourseScheduleTbl', $courseScheduleTbl)->with('courseScheduleTblRowsCount', $courseScheduleTblRowsCount)->with('inputFieldDescriptions', INPUT_TIPS)->with('okanaganSyllabusResources', $data['okanaganSyllabusResources'])->with('vancouverSyllabusResources', $data['vancouverSyllabusResources'])->with('syllabus', $syllabus)->with('vancouverSyllabus', $vancouverSyllabus)->with('selectedVancouverSyllabusResourceIds', $selectedVancouverSyllabusResourceIds)->with('faculties', $data['faculties'])->with('departments', $data['departments']);
            break;
                
        }

    }


    public function syllabusViewer($syllabusId, $data) {
        // get this syllabus
        $syllabus = Syllabus::find($syllabusId);
        $courseScheduleTblRowsCount = CourseSchedule::where('syllabus_id', $syllabus->id)->where('col', 0)->get()->count();
        $courseScheduleTblColsCount = CourseSchedule::where('syllabus_id', $syllabus->id)->where('row', 0)->get()->count();
        $courseScheduleTbl['rows'] = CourseSchedule::where('syllabus_id', $syllabus->id)->get()->chunk($courseScheduleTblColsCount);
        $courseScheduleTbl['numCols'] = $courseScheduleTblColsCount;
        $courseScheduleTbl['numRows'] = $courseScheduleTblRowsCount;
        $syllabusInstructors = SyllabusInstructor::where('syllabus_id', $syllabus->id)->get()->implode('name', ', ');

        switch ($syllabus->campus) {
            case 'O':
                // get data specific to okanagan campus
                $okanaganSyllabus = OkanaganSyllabus::where('syllabus_id', $syllabus->id)->first();
                // get selected okanagan syllabus resource
                $selectedOkanaganSyllabusResourceIds = SyllabusResourceOkanagan::where('syllabus_id', $syllabus->id)->pluck('o_syllabus_resource_id')->toArray();
                // return view with okanagan syllabus data
                return view("syllabus.syllabusViewerOkanagan")->with('myCourseScheduleTbl', $courseScheduleTbl)->with('courseScheduleTblRowsCount', $courseScheduleTblRowsCount)->with('inputFieldDescriptions', INPUT_TIPS)->with('okanaganSyllabusResources', $data['okanaganSyllabusResources'])->with('syllabus', $syllabus)->with('okanaganSyllabus', $okanaganSyllabus)->with('selectedOkanaganSyllabusResourceIds', $selectedOkanaganSyllabusResourceIds)->with('syllabusInstructors', $syllabusInstructors);
            break;
            case 'V':
                // get data specific to vancouver campus
                $vancouverSyllabus = VancouverSyllabus::where('syllabus_id', $syllabusId)->first();
                // get selected vancouver syllabus resource
                $selectedVancouverSyllabusResourceIds = SyllabusResourceVancouver::where('syllabus_id', $syllabus->id)->pluck('v_syllabus_resource_id')->toArray();
                // return view with vancouver syllabus data
                return view("syllabus.syllabusViewerVancouver")->with('myCourseScheduleTbl', $courseScheduleTbl)->with('courseScheduleTblRowsCount', $courseScheduleTblRowsCount)->with('inputFieldDescriptions', INPUT_TIPS)->with('vancouverSyllabusResources', $data['vancouverSyllabusResources'])->with('syllabus', $syllabus)->with('vancouverSyllabus', $vancouverSyllabus)->with('selectedVancouverSyllabusResourceIds', $selectedVancouverSyllabusResourceIds)->with('syllabusInstructors', $syllabusInstructors);        
        }
    }

    /**
     * Save syllabus.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, $syllabusId = null)
    {
        // validate request
        $request->validate([
            'campus' => ['required'],
            'courseTitle' => ['required'],
            'courseCode' => ['required'],
            'courseNumber' => ['required'],
            'deliveryModality' => ['required'],
            'courseInstructor' => ['required'],
            'courseYear' => ['required'],
            'courseSemester' => ['required'],
        ]);

        // $courseScheduleOutline['headings'] = $request->input('courseScheduleTblHeadings');
        // $courseScheduleOutline['rows'] = $request->input('courseScheduleTblRows');
        
        // if syllabus already exists, update it
        if ($syllabusId) {
            // update syllabus
            $syllabus = $this->update($request, $syllabusId);
        // else create a new syllabus
        } else {
            // create a new syllabus
            $syllabus = $this->create($request);
        }
        // set updated_at time
        $syllabus->updated_at = date('Y-m-d H:i:s');

        // get users name for last_modified_user
        $user = User::find(Auth::id());
        $syllabus->last_modified_user = $user->name;

        // save syllabus
        if ($syllabus->save()) {
            $request->session()->flash('success', 'Your syllabus was successfully saved!');
            
        } else {
            $request->session()->flash('error', 'There was an error saving your syllabus!');
        }

        // download syllabus as a word document
        if ($request->input('download')) {
            // download syllabus
            return $this->download($syllabus->id, $request->input('download'));
        }

        return redirect()->route('syllabus', [
            'syllabusId' => $syllabus->id,
        ]);
    }

    /**
     * Create a new syllabus resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create($request)
    {        
        // validate request
        $request->validate([
            'campus' => ['required'],
            'courseTitle' => ['required'],
            'courseCode' => ['required'],
            'courseNumber' => ['required'],
            'deliveryModality' => ['required'],
            'courseInstructor' => ['required'],
            'courseInstructorEmail' => ['required'],
            'courseYear' => ['required'],
            'courseSemester' => ['required'],
        ]);
        // get required fields common to both campuses
        $campus = $request->input('campus');
        $courseTitle = $request->input('courseTitle');
        $courseCode = $request->input('courseCode');
        $courseNumber = $request->input('courseNumber');
        $deliveryModality = $request->input('deliveryModality');
        $courseInstructors = $request->input('courseInstructor');
        $courseInstructorEmails = $request->input('courseInstructorEmail');
        $courseYear = $request->input('courseYear');
        $request->input('courseSemester') == 'O' ? $courseSemester = $request->input('courseSemesterOther') : $courseSemester = $request->input('courseSemester');
        // get faculty input or use null if it's not present 
        $faculty = $request->input('faculty', null);        
        $department = $request->input('department', null);        

        // get current user
        $user = User::where('id', Auth::id())->first();
        
        // create a new syllabus and set required data values
        $syllabus = new Syllabus;
        $syllabus->campus = $campus;
        $syllabus->course_title = $courseTitle;
        $syllabus->course_code = $courseCode;
        $syllabus->course_num = $courseNumber;
        $syllabus->delivery_modality = $deliveryModality;
        $syllabus->course_instructor = $courseInstructors[0];
        $syllabus->course_term = $courseSemester;
        $syllabus->course_year = $courseYear;
        $syllabus->faculty = $faculty;
        $syllabus->department = $department;

        // set optional syllabus fields common to both campuses 
        $syllabus->course_location = $request->input('courseLocation');
        $syllabus->other_instructional_staff = $request->input('otherCourseStaff');
        $syllabus->class_start_time = $request->input('startTime');
        $syllabus->class_end_time = $request->input('endTime');
        if ($classMeetingDays = $request->input('schedule')) {
            $classSchedule = "";
            foreach($classMeetingDays as $day) {
                $classSchedule = ($classSchedule == "" ? $day : $classSchedule . '/' . $day);
            }

            $syllabus->class_meeting_days = $classSchedule;
        }        
        $syllabus->learning_outcomes = $request->input('learningOutcome');
        $syllabus->learning_assessments = $request->input('learningAssessments');
        $syllabus->learning_activities = $request->input('learningActivities');
        $syllabus->late_policy = $request->input('latePolicy');
        $syllabus->missed_exam_policy = $request->input('missingExam');
        $syllabus->missed_activity_policy = $request->input('missingActivity');
        $syllabus->passing_criteria = $request->input('passingCriteria');
        $syllabus->learning_materials = $request->input('learningMaterials');
        $syllabus->learning_resources = $request->input('learningResources');
        // save syllabus
        $syllabus->save();
        // save syllabus instructors 
        foreach ($courseInstructors as $index => $courseInstructor) {
            $syllabusInstructor = new SyllabusInstructor();
            $syllabusInstructor->syllabus_id = $syllabus->id;
            $syllabusInstructor->name = $courseInstructor;
            $syllabusInstructor->email = $courseInstructorEmails[$index];
            $syllabusInstructor->save();
        }
        // save course schedule table
        if ($courseScheduleTblHeadings = $request->input('courseScheduleTblHeadings')) {
            foreach($courseScheduleTblHeadings as $colIndex => $courseScheduleTblHeading) {
                // create a new course schedule object 
                $courseScheduleTbl = new CourseSchedule;
                // set the course schedule entries attributes 
                $courseScheduleTbl->syllabus_id = $syllabus->id;
                $courseScheduleTbl->col = $colIndex;
                $courseScheduleTbl->row = 0;
                $courseScheduleTbl->val = $courseScheduleTblHeading;
                // save course schedule entry
                $courseScheduleTbl->save();
            }
            if ($courseScheduleTblRows = $request->input('courseScheduleTblRows')) {
                $rows = array_chunk($courseScheduleTblRows, count($courseScheduleTblHeadings));

                foreach ($rows as $rowIndex => $row) {
                    foreach ($row as $colIndex => $rowItem) {
                        // create a new course schedule object 
                        $courseScheduleTbl = new CourseSchedule;
                        // set the course schedule entries attributes 
                        $courseScheduleTbl->syllabus_id = $syllabus->id;
                        $courseScheduleTbl->col = $colIndex;
                        $courseScheduleTbl->row = $rowIndex + 1;
                        $courseScheduleTbl->val = $rowItem;
                        // save course schedule entry 
                        $courseScheduleTbl->save();
                    }
                }
            }
        }

        switch($campus) {
            case 'O':
                // create okanagan syllabus record
                $okanaganSyllabus = new OkanaganSyllabus;
                $okanaganSyllabus->syllabus_id = $syllabus->id;
                // set optional syllabus fields for Okangan campus
                $okanaganSyllabus->course_format = $request->input('courseFormat');
                $okanaganSyllabus->course_overview = $request->input('courseOverview');
                // save okanagan syllabus record
                $okanaganSyllabus->save();
                // check if a list of okanagan syllabus resources to include was provided
                if ($okanaganSyllabusResources = $request->input('okanaganSyllabusResources')) {
                    foreach ($okanaganSyllabusResources as $resourceId => $resourceIdName) {
                        // create a record for each resource selected for this syllabus
                        SyllabusResourceOkanagan::create(
                            ['syllabus_id' => $syllabus->id, 'o_syllabus_resource_id' => $resourceId],
                        );            
                    }
                }
            break;
            case 'V':
                // validate request
                $request->validate([
                    'courseCredit' => ['required'],
                ]);
                // crate vancouver syllabus record
                $vancouverSyllabus = new VancouverSyllabus;
                $vancouverSyllabus->syllabus_id = $syllabus->id;
                $vancouverSyllabus->course_credit = $request->input('courseCredit');
                // set optional syllabus fields for Vancouver campus
                $vancouverSyllabus->office_location = $request->input('officeLocation');
                $vancouverSyllabus->course_description = $request->input('courseDescription');
                $vancouverSyllabus->course_contacts = $request->input('courseContacts');                    
                $vancouverSyllabus->course_prereqs = $request->input('coursePrereqs');
                $vancouverSyllabus->course_coreqs = $request->input('courseCoreqs');
                $vancouverSyllabus->instructor_bio = $request->input('courseInstructorBio');
                $vancouverSyllabus->course_structure = $request->input('courseStructure');
                $vancouverSyllabus->course_schedule = $request->input('courseSchedule');
                $vancouverSyllabus->learning_analytics = $request->input('learningAnalytics');
                // save vancouver syllabus record
                $vancouverSyllabus->save();
                // check if a list of vancouver syllabus resources to include was provided
                if ($vancouverSyllabusResources = $request->input('vancouverSyllabusResources')) {
                    foreach ($vancouverSyllabusResources as $resourceId => $resourceIdName) {
                        // create a record for each resource selected for this syllabus
                        SyllabusResourceVancouver::create(
                            ['syllabus_id' => $syllabus->id, 'v_syllabus_resource_id' => $resourceId],
                        );            
                    }
                }
            break;
        }
        // create a new syllabus user
        $syllabusUser = new SyllabusUser;
        // set relationship between syllabus and user
        $syllabusUser->syllabus_id = $syllabus->id;
        $syllabusUser->user_id = $user->id;
        $syllabusUser->permission = 1;
        $syllabusUser->save();

        return $syllabus;
    }


    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($request, $syllabusId)
    {
        // validate request
        $request->validate([
            'campus' => ['required'],
            'courseTitle' => ['required'],
            'courseCode' => ['required'],
            'courseNumber' => ['required'],
            'deliveryModality' => ['required'],
            'courseInstructor' => ['required'],
            'courseInstructorEmail' => ['required'],
            'courseYear' => ['required'],
            'courseSemester' => ['required'],
        ]);
        
        $campus = $request->input('campus');
        $courseTitle = $request->input('courseTitle');
        $courseCode = $request->input('courseCode');
        $courseNumber = $request->input('courseNumber');
        $deliveryModality = $request->input('deliveryModality');
        $courseInstructors = $request->input('courseInstructor');
        $courseInstructorEmails = $request->input('courseInstructorEmail');
        $courseYear = $request->input('courseYear');
        $request->input('courseSemester') == 'O' ? $courseSemester = $request->input('courseSemesterOther') : $courseSemester = $request->input('courseSemester');
        // get faculty input or use null if it's not present 
        $faculty = $request->input('faculty', null);        
        $department = $request->input('department', null);        

        // get the syllabus, and start updating it
        $syllabus = Syllabus::find($syllabusId);
        $syllabus->campus = $campus;
        $syllabus->course_title = $courseTitle;
        $syllabus->course_code = $courseCode;
        $syllabus->course_num = $courseNumber;
        $syllabus->delivery_modality = $deliveryModality;
        $syllabus->course_instructor = $courseInstructors[0];
        $syllabus->course_term = $courseSemester;
        $syllabus->course_year = $courseYear;
        $syllabus->faculty = $faculty;
        $syllabus->department = $department;

        // update optional syllabus fields common to both campuses
        $syllabus->course_location = $request->input('courseLocation');
        $syllabus->other_instructional_staff = $request->input('otherCourseStaff');
        $syllabus->office_hours = $request->input('officeHour');
        $syllabus->class_start_time = $request->input('startTime');
        $syllabus->class_end_time = $request->input('endTime');

        if ($classMeetingDays = $request->input('schedule')) {
            $classSchedule = "";
            foreach($classMeetingDays as $day) {
                $classSchedule = ($classSchedule == "" ? $day : $classSchedule . '/' . $day);
            }
            $syllabus->class_meeting_days = $classSchedule;
        } else {
            $syllabus->class_meeting_days = null;
        }

        $syllabus->learning_outcomes = $request->input('learningOutcome');
        $syllabus->learning_assessments = $request->input('learningAssessments');
        $syllabus->learning_activities = $request->input('learningActivities');
        $syllabus->late_policy = $request->input('latePolicy');
        $syllabus->missed_exam_policy = $request->input('missingExam');
        $syllabus->missed_activity_policy = $request->input('missingActivity');
        $syllabus->passing_criteria = $request->input('passingCriteria');
        $syllabus->learning_materials = $request->input('learningMaterials');
        $syllabus->learning_resources = $request->input('learningResources');

        // delete all the previous syllabus instructor entries (TODO: optimize)
        SyllabusInstructor::where('syllabus_id', $syllabus->id)->delete();
        // save syllabus instructors 
        foreach ($courseInstructors as $index => $courseInstructor) {
            $syllabusInstructor = new SyllabusInstructor();
            $syllabusInstructor->syllabus_id = $syllabus->id;
            $syllabusInstructor->name = $courseInstructor;
            $syllabusInstructor->email = $courseInstructorEmails[$index];
            $syllabusInstructor->save();
        }

        // delete all the previous course schedule table entries (TODO: optimize)
        $courseScheduleTbl = CourseSchedule::where('syllabus_id', $syllabus->id)->delete();
        // save the updated course schedule table
        
        if ($courseScheduleTblHeadings = $request->input('courseScheduleTblHeadings')) {
            foreach($courseScheduleTblHeadings as $colIndex => $courseScheduleTblHeading) {
                // create a new course schedule object 
                $courseScheduleTbl = new CourseSchedule;
                // set the course schedule entries attributes 
                $courseScheduleTbl->syllabus_id = $syllabus->id;
                $courseScheduleTbl->col = $colIndex;
                $courseScheduleTbl->row = 0;
                $courseScheduleTbl->val = $courseScheduleTblHeading;
                // save course schedule entry
                $courseScheduleTbl->save();
            }
            if ($courseScheduleTblRows = $request->input('courseScheduleTblRows')) {
                $rows = array_chunk($courseScheduleTblRows, count($courseScheduleTblHeadings));
                foreach ($rows as $rowIndex => $row) {
                    foreach ($row as $colIndex => $rowItem) {
                        // create a new course schedule object 
                        $courseScheduleTbl = new CourseSchedule;
                        // set the course schedule entries attributes 
                        $courseScheduleTbl->syllabus_id = $syllabus->id;
                        $courseScheduleTbl->col = $colIndex;
                        $courseScheduleTbl->row = $rowIndex + 1;
                        $courseScheduleTbl->val = $rowItem;
                        // save course schedule entry 
                        $courseScheduleTbl->save();
                    }
                }
            }
        }

        switch ($campus) {
            case 'O':
                // campus was not changed
                if ($syllabus->getOriginal('campus') == 'O') {
                    // get the related Okanagan syllabus
                    $okanaganSyllabus = OkanaganSyllabus::where('syllabus_id', $syllabus->id)->first();
                    // update optional fields for okanagan syllabus
                    $okanaganSyllabus->course_format = $request->input('courseFormat');
                    $okanaganSyllabus->course_overview = $request->input('courseOverview');
                    // save okanagan syllabus
                    $okanaganSyllabus->save();
                    // check if a list of okanagan syllabus resources to include was provided
                    if ($okanaganSyllabusResources = $request->input('okanaganSyllabusResources')) {
                        // delete all resources previously selected for the given syllabus but not currently selected
                        SyllabusResourceOkanagan::where('syllabus_id', $syllabus->id)->whereNotIn('o_syllabus_resource_id', array_keys($okanaganSyllabusResources))->delete();
                        // update or create records for selected okanagan syllabus resources
                        foreach ($okanaganSyllabusResources as $selectedResourceId => $selectedResourceIdName) {
                            SyllabusResourceOkanagan::updateOrCreate(
                                ['syllabus_id' => $syllabus->id, 'o_syllabus_resource_id' => $selectedResourceId], 
                            );
                        }   
                    } else {
                        // delete all resources previously selected for the given syllabus
                        SyllabusResourceOkanagan::where('syllabus_id', $syllabus->id)->delete();
                    }
                // campus was changed from 'V' to 'O'
                } else {
                    // delete vancouver syllabus record 
                    VancouverSyllabus::where('syllabus_id', $syllabus->id)->delete();
                    // create a new okanagan syllabus
                    $okanaganSyllabus = new OkanaganSyllabus;
                    $okanaganSyllabus->syllabus_id = $syllabus->id;
                    // set optional syllabus fields for Okangan campus
                    $okanaganSyllabus->course_format = $request->input('courseFormat');
                    $okanaganSyllabus->course_overview = $request->input('courseOverview');
                    // save okanagan syllabus
                    $okanaganSyllabus->save();
                    // delete all resources previously selected for the vancouver syllabus
                    SyllabusResourceVancouver::where('syllabus_id', $syllabus->id)->delete();
                    // check if a list of okanagan syllabus resources to include was provided
                    if ($okanaganSyllabusResources = $request->input('okanaganSyllabusResources')) {
                        // update or create records for selected okanagan syllabus resources
                        foreach ($okanaganSyllabusResources as $selectedResourceId => $selectedResourceIdName) {
                            SyllabusResourceOkanagan::updateOrCreate(
                                ['syllabus_id' => $syllabus->id, 'o_syllabus_resource_id' => $selectedResourceId], 
                            );
                        }   
                    } 
                }
            break;
            case 'V':
                // campus was not changed
                if ($syllabus->getOriginal('campus') == 'V') {
                    $request->validate([
                        'courseCredit' => ['required'],
                    ]);
                    // get related vancouver syllabus
                    $vancouverSyllabus = VancouverSyllabus::where('syllabus_id', $syllabus->id)->first();
                    $vancouverSyllabus->course_credit = $request->input('courseCredit');
                    // update optional fields for vancouver syllabus
                    $vancouverSyllabus->office_location = $request->input('officeLocation');
                    $vancouverSyllabus->course_description = $request->input('courseDescription');
                    $vancouverSyllabus->course_contacts = $request->input('courseContacts');
                    $vancouverSyllabus->course_prereqs = $request->input('coursePrereqs');
                    $vancouverSyllabus->course_coreqs = $request->input('courseCoreqs');
                    $vancouverSyllabus->instructor_bio = $request->input('courseInstructorBio');
                    $vancouverSyllabus->course_structure = $request->input('courseStructure');
                    $vancouverSyllabus->course_schedule = $request->input('courseSchedule');
                    $vancouverSyllabus->learning_analytics = $request->input('learningAnalytics');
                    // save vancouver syllabus
                    $vancouverSyllabus->save();
                    // check if a list of vancouver syllabus resources to include was provided
                    if ($vancouverSyllabusResources = $request->input('vancouverSyllabusResources')) {
                        // delete all resources previously selected for the given syllabus but not currently selected
                        SyllabusResourceVancouver::where('syllabus_id', $syllabus->id)->whereNotIn('v_syllabus_resource_id', array_keys($vancouverSyllabusResources))->delete();
                        // update or create records for selected vancouver syllabus resources
                        foreach ($vancouverSyllabusResources as $selectedResourceId => $selectedResourceIdName) {
                            SyllabusResourceVancouver::updateOrCreate(
                                ['syllabus_id' => $syllabus->id, 'v_syllabus_resource_id' => $selectedResourceId], 
                            );
                        }
                    } else {
                        // delete all resources previously selected for the given syllabus
                        SyllabusResourceVancouver::where('syllabus_id', $syllabus->id)->delete();
                    }
                // campus was changed from 'O' to 'V'
                } else {
                    // delete okanagan syllabus record 
                    OkanaganSyllabus::where('syllabus_id', $syllabusId)->delete();
                    // validate request
                    $request->validate([
                        'courseCredit' => ['required'],
                    ]);
                    // create new vancouver syllabus record
                    $vancouverSyllabus = new VancouverSyllabus;
                    $vancouverSyllabus->syllabus_id = $syllabus->id;
                    $vancouverSyllabus->course_credit = $request->input('courseCredit');
                    // set optional syllabus fields for Vancouver campus
                    $vancouverSyllabus->office_location = $request->input('officeLocation');
                    $vancouverSyllabus->course_description = $request->input('courseDescription');
                    $vancouverSyllabus->course_contacts = $request->input('courseContacts');                    
                    $vancouverSyllabus->course_prereqs = $request->input('coursePrereqs');
                    $vancouverSyllabus->course_coreqs = $request->input('courseCoreqs');
                    $vancouverSyllabus->instructor_bio = $request->input('courseInstructorBio');
                    $vancouverSyllabus->course_structure = $request->input('courseStructure');
                    $vancouverSyllabus->course_schedule = $request->input('courseSchedule');
                    $vancouverSyllabus->learning_analytics = $request->input('learningAnalytics');
                    // save vancouver syllabus
                    $vancouverSyllabus->save();
                    // delete all resources previously selected for the okanagan syllabus
                    SyllabusResourceOkanagan::where('syllabus_id', $syllabus->id)->delete();
                    // check if a list of vancouver syllabus resources to include was provided
                    if ($vancouverSyllabusResources = $request->input('vancouverSyllabusResources')) {
                        // update or create records for selected vancouver syllabus resources
                        foreach ($vancouverSyllabusResources as $selectedResourceId => $selectedResourceIdName) {
                            SyllabusResourceVancouver::updateOrCreate(
                                ['syllabus_id' => $syllabus->id, 'v_syllabus_resource_id' => $selectedResourceId], 
                            );
                        }
                    }
                }
            }
            return $syllabus;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $syllabusId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $syllabusId)
    {
        // find the syllabus to delete
        $syllabus = Syllabus::find($syllabusId);
        // find the current user
        $currentUser = User::find(Auth::id());
        // get the current users permission level for the syllabus to delete
        $currentUserPermission = $currentUser->syllabi->where('id', $syllabusId)->first()->pivot->permission;
        // if the current user owns the syllabus, try delete it
        if ($currentUserPermission == 1) {
            if($syllabus->delete()){
                $request->session()->flash('success','Your syllabus has been deleted');
            }else{
                $request->session()->flash('error', 'There was an error deleting your syllabus');
            }
        // else the current user does not own the syllabus, flash an error
        } else {
            $request->session()->flash('error','You do not have permission to delete this syllabus');
        }

        // return to the dashboard
        return redirect()->route('home');
    }

    

    // get existing course information
    // Ajax to get course infomation
    public function getCourseInfo(Request $request) {

        $this->validate($request, [
            'course_id'=> 'required',
            ]);

        $course_id = $request->course_id;
        $course = Course::find($course_id);
        // get relevant course info for import into Syllabus Generator
        $a_methods = $course->assessmentMethods;
        $l_outcomes = $course->learningOutcomes;
        $l_activities = $course->learningActivities;
        // put courseInfo, assessment methods and CLOs in the return object
        $data['c_title'] = $course->course_title;
        $data['c_code'] = $course->course_code;
        $data['c_num'] = $course->course_num;
        $data['c_del'] = $course->delivery_modality;
        $data['c_year'] = $course->year;
        $data['c_term'] = $course->semester;
        $data['a_methods'] = $a_methods;
        $data['l_outcomes'] = $l_outcomes;
        $data['l_activities'] = $l_activities;

        $data = json_encode($data);
        return $data;
    }

    /**
     * Download the given syllabus $syllabusId in $ext format
     * @param Integer $syllabusId
     * @param String $ext: the file extension
     * @return a download response
     */
    public function download($syllabusId, $ext) {
        
        $syllabus = Syllabus::find($syllabusId);

        switch ($syllabus->campus) {
            case 'O':
                // create a new template for this syllabus
                $templateProcessor = new TemplateProcessor('word-template/UBC-O_default.docx');
                // get data specific to the okanagan campus
                $okanaganSyllabus = OkanaganSyllabus::where('syllabus_id', $syllabus->id)->first();
                // add data to the okanagan syllabus template
                if($courseFormat = $okanaganSyllabus->course_format){
                    $templateProcessor->cloneBlock('NocourseFormat');
                    $templateProcessor->setValue('courseFormat', $courseFormat);
                }else{
                    $templateProcessor->cloneBlock('NocourseFormat',0);
                }
        
                if($courseOverview = $okanaganSyllabus->course_overview){
                    $templateProcessor->cloneBlock('NocourseOverview');
                    $templateProcessor->setValue('courseOverview',$courseOverview);
                }else{
                    // tell template processor to not include 'NocourseOverview block
                    $templateProcessor->cloneBlock('NocourseOverview', 0);
                }

                // tell template processor to include learning activities if user completed the field(s)
                if($learningActivities = $syllabus->learning_activities){
                    $templateProcessor->cloneBlock('NoLearningActivities');
                    // split learning activities string on newline char
                    $learningActivitiesArr = explode("\n", $learningActivities);
                    // create a table for learning activities (workaround for no list option)
                    $learningActivitiesTable = new Table(array('borderSize' => 8, 'borderColor' => 'DCDCDC'));
                    // add a new row and cell to table for each learning activity
                    foreach($learningActivitiesArr as $index => $learningActivity){
                        $learningActivitiesTable->addRow();
                        $learningActivitiesTable->addCell()->addText(strval($index + 1));
                        $learningActivitiesTable->addCell()->addText($learningActivity);
                    }
                    // add learning activities table to word doc
                    $templateProcessor->setComplexBlock('learningActivities', $learningActivitiesTable);
                }else{
                    $templateProcessor->cloneBlock('NoLearningActivities',0);
                }
                // tell template processor to include other course staff if user completed the field(s)
                if($otherCourseStaff = $syllabus->other_instructional_staff){
                    $templateProcessor->cloneBlock('NoOtherInstructionalStaff');
                    // split other course staff string on newline char
                    $otherCourseStaffArr = explode("\n", $otherCourseStaff);
                    // create a table for other course staff (workaround for no list option)
                    $otherCourseStaffTable = new Table(array('borderSize' => 8, 'borderColor' => 'DCDCDC'));
                    // add a new row and cell to table for each course staff member
                    foreach($otherCourseStaffArr as $index => $courseStaffMember){
                        $otherCourseStaffTable->addRow();
                        $otherCourseStaffTable->addCell()->addText(strval($index + 1));
                        $otherCourseStaffTable->addCell()->addText($courseStaffMember);
                    }
                    // add other course staff table to word doc
                    $templateProcessor->setComplexBlock('otherInstructionalStaff', $otherCourseStaffTable);
                }else{
                    $templateProcessor->cloneBlock('NoOtherInstructionalStaff',0);
                }
                // tell template processor to include course location if user completed the field(s)
                if ($courseLocation = $syllabus->course_location) {
                    $templateProcessor->cloneBlock('NoCourseLocation');
                    $templateProcessor->setValue('courseLocation',$courseLocation);
                } else {
                    $templateProcessor->cloneBlock('NoCourseLocation',0);
                }
                
                // tell template processor to include class hours if user completed the field(s)
                if ($classStartTime = $syllabus->class_start_time && $classEndTime = $syllabus->class_end_time) {
                    $templateProcessor->cloneBlock('NoClassHours');
                    $templateProcessor->setValues(array('classStartTime' => $syllabus->class_start_time, 'classEndTime' => $syllabus->class_end_time));
                } else {
                    $templateProcessor->cloneBlock('NoClassHours',0);
                }
                // tell template processor to include course schedule if user completed the field(s)
                if ($schedule = $syllabus->class_meeting_days) {
                    $templateProcessor->cloneBlock('NoCourseDays');
                    $templateProcessor->setValue('schedule', $schedule);
                } else {
                    $templateProcessor->cloneBlock('NoCourseDays', 0);
                }
                // tell template processor to include office hours if user completed the field(s)
                if ($officeHour = $syllabus->office_hours) {
                    $templateProcessor->cloneBlock('NoOfficeHours');
                    $templateProcessor->setValue('officeHour',$officeHour);
                } else {
                    $templateProcessor->cloneBlock('NoOfficeHours', 0);
                }

                switch($syllabus->course_term){
                    case("W1"):
                        $templateProcessor->setValue('season',"Winter");
                        $templateProcessor->setValue('term',"Term 1");
                    break;
                    case("W2"):
                        $templateProcessor->setValue('season',"Winter");
                        $templateProcessor->setValue('term',"Term 2");
                    break;
                    case("S1"):
                        $templateProcessor->setValue('season',"Summer");
                        $templateProcessor->setValue('term',"Term 1");
                    break;
                    case("S2"):
                        $templateProcessor->setValue('season',"Summer");
                        $templateProcessor->setValue('term',"Term 2");
                    break;
                    default: 
                        $templateProcessor->setValue('term', $syllabus->course_term);
                        $templateProcessor->setValue('season',"");
                }

                if($learningOutcome = $syllabus->learning_outcomes){
                    $templateProcessor->cloneBlock('NolearningOutcomes');
                    // split learning outcomes string on newline char
                    $learningOutcomes = explode("\n", $learningOutcome);
                    // create a table for learning outcomes (workaround for no list option)
                    $learningOutcomesTable = new Table(array('borderSize'=>8, 'borderColor' => 'DCDCDC'));
                    // add a new row and cell to table for each learning outcome
                    foreach($learningOutcomes as $outcome) {
                        $learningOutcomesTable->addRow();
                        $learningOutcomesTable->addCell()->addText($outcome);
                    }
                    // add learning outcome table to word doc
                    $templateProcessor->setComplexBlock('learningOutcomes',$learningOutcomesTable);
                }else{
                    $templateProcessor->cloneBlock('NolearningOutcomes',0);
                }

                if($learningAssessments = $syllabus->learning_assessments){
                    $templateProcessor->cloneBlock('NoLearningAssessments');
                    // split assessment methods string on newline char
                    $assessmentMethods = explode("\n", $learningAssessments);
                    // create a table for learning outcomes (workaround for no list option)
                    $assessmentMethodsTable = new Table(array('borderSize' => 8, 'borderColor' => 'DCDCDC'));
                    // add a new row and cell to table for each assessment method
                    foreach($assessmentMethods as $index => $assessmentMethod){
                        $assessmentMethodsTable->addRow();
                        $assessmentMethodsTable->addCell()->addText(strval($index + 1));
                        $assessmentMethodsTable->addCell()->addText($assessmentMethod);
                    }
                    // add assessment methods table to word doc
                    $templateProcessor->setComplexBlock('learningAssessments', $assessmentMethodsTable);
                }else{
                    $templateProcessor->cloneBlock('NoLearningAssessments',0);
                }
                // include vancouver course learning resources in template
                if($learningResources = $syllabus->learning_resources){
                    $templateProcessor->cloneBlock('NoCourseLearningResources');
                    $templateProcessor->setValue('courseLearningResources', $learningResources);
                }else{
                    $templateProcessor->cloneBlock('NoCourseLearningResources', 0);
                }
        
                if($learningMaterials = $syllabus->learning_materials){
                    $templateProcessor->cloneBlock('NoLearningMaterials');
                    $templateProcessor->setValue('learningMaterials',$learningMaterials);
                }else{
                    $templateProcessor->cloneBlock('NoLearningMaterials',0);
                }
                
                $allOkanaganSyllabusResources = OkanaganSyllabusResource::all();
                $selectedOkanaganSyllabusResourceIds = SyllabusResourceOkanagan::where('syllabus_id', $syllabus->id)->pluck('o_syllabus_resource_id')->toArray();
                
                foreach ($allOkanaganSyllabusResources as $resource) {
                    if (in_array($resource->id, $selectedOkanaganSyllabusResourceIds)) {
                        $templateProcessor->cloneBlock($resource->id_name);
                        $templateProcessor->setValue($resource->id_name . '-title', $resource->title);
                        // $templateProcessor->setValue($resource->id_name . '-description', $resource->description);
                    } else {
                        $templateProcessor->cloneBlock($resource->id_name, 0);
                    }
                }  

            break;
            case 'V':
                // get data specific to the okanagan campus
                $vancouverSyllabus = VancouverSyllabus::where('syllabus_id', $syllabus->id)->first();
                // generate word syllabus for Vancouver campus course
                $templateProcessor = new TemplateProcessor('word-template/UBC-V_default.docx');
                // add data to the vancouver syllabus template
                $courseCredit = $vancouverSyllabus->course_credit;
                // add required form fields specific to Vancouver campus to template
                $templateProcessor->setValues(array('courseCredit' => $courseCredit,));

                if($officeLocation = $vancouverSyllabus->office_location){
                    $templateProcessor->cloneBlock('NoOfficeLocation');
                    $templateProcessor->setValue('officeLocation', $officeLocation);
                }else{
                    $templateProcessor->cloneBlock('NoOfficeLocation', 0);
                }

                // include vancouver course description in template
                if($courseDescription = $vancouverSyllabus->course_description){
                    $templateProcessor->cloneBlock('NoCourseDescription');
                    $templateProcessor->setValue('courseDescription', $courseDescription);
                }else{
                    $templateProcessor->cloneBlock('NoCourseDescription', 0);
                }

                if($contacts = $vancouverSyllabus->course_contacts){
                    $templateProcessor->cloneBlock('NoContacts', 0);
                    // split contacts string on newline char
                    $contactsArr = explode("\n", $contacts);
                    // create a table for contacts (workaround for no list option)
                    $contactsTable = new Table(array('borderSize' => 8, 'borderColor' => 'DCDCDC'));
                    // add a new row and cell to table for each contact
                    foreach($contactsArr as $index => $contact){
                        $contactsTable->addRow();
                        $contactsTable->addCell()->addText(strval($index + 1));
                        $contactsTable->addCell()->addText($contact);
                    }
                    // add contacts table to word doc
                    $templateProcessor->setComplexBlock('contacts', $contactsTable);
                    
                }else{
                    $templateProcessor->cloneBlock('NoContacts');
                    $templateProcessor->setValue('contacts', '');
                }

                if($coursePrereqs = $vancouverSyllabus->course_prereqs){
                    $templateProcessor->cloneBlock('NoPrerequisites', 0);
                    // split course prereqs string on newline char
                    $coursePrereqsArr = explode("\n", $coursePrereqs);
                    // create a table for course prereqs (workaround for no list option)
                    $coursePrereqsTable = new Table(array('borderSize' => 8, 'borderColor' => 'DCDCDC'));
                    // add a new row and cell to table for each prereq
                    foreach($coursePrereqsArr as $index => $prereq){
                        $coursePrereqsTable->addRow();
                        $coursePrereqsTable->addCell()->addText(strval($index + 1));
                        $coursePrereqsTable->addCell()->addText($prereq);
                    }
                    // add course prereqs table to word doc
                    $templateProcessor->setComplexBlock('prerequisites', $coursePrereqsTable);
                }else{
                    $templateProcessor->cloneBlock('NoPrerequisites');
                    $templateProcessor->setValue('prerequisites', '');
                }

                if($courseCoreqs = $vancouverSyllabus->course_coreqs){
                    $templateProcessor->cloneBlock('NoCorequisites', 0);
                    // split course coreqs string on newline char
                    $courseCoreqsArr = explode("\n", $courseCoreqs);
                    // create a table for course coreqs (workaround for no list option)
                    $courseCoreqsTable = new Table(array('borderSize' => 8, 'borderColor' => 'DCDCDC'));
                    // add a new row and cell to table for each coreq
                    foreach($courseCoreqsArr as $index => $coreq){
                        $courseCoreqsTable->addRow();
                        $courseCoreqsTable->addCell()->addText(strval($index + 1));
                        $courseCoreqsTable->addCell()->addText($coreq);
                    }
                    // add course coreqs table to word doc
                    $templateProcessor->setComplexBlock('corequisites', $courseCoreqsTable);
                }else{
                    $templateProcessor->cloneBlock('NoCorequisites');
                    $templateProcessor->setValue('corequisites', '');
                }

                if($courseInstructorBio = $vancouverSyllabus->instructor_bio){
                    $templateProcessor->cloneBlock('NoInstructorBio');
                    $templateProcessor->setValue('instructorBio', $courseInstructorBio);
                }else{
                    $templateProcessor->cloneBlock('NoInstructorBio', 0);
                }

                if($courseStructure = $vancouverSyllabus->course_structure){
                    $templateProcessor->cloneBlock('NoCourseStructure', 0);
                    $templateProcessor->setValue('courseStructure', $courseStructure);
                }else{
                    $templateProcessor->cloneBlock('NoCourseStructure');
                    $templateProcessor->setValue('courseStructure', '');
                }

                if($courseSchedule = $vancouverSyllabus->course_schedule){
                    $templateProcessor->cloneBlock('NoTopicsSchedule', 0 );
                    $templateProcessor->setValue('courseSchedule', $courseSchedule);
                }else{
                    $templateProcessor->cloneBlock('NoTopicsSchedule');
                    $templateProcessor->setValue('courseSchedule', '');
                }

                // tell template processor to include learning activities if user completed the field(s)
                if($learningActivities = $syllabus->learning_activities){
                    $templateProcessor->cloneBlock('NoLearningActivities', 0);
                    // split learning activities string on newline char
                    $learningActivitiesArr = explode("\n", $learningActivities);
                    // create a table for learning activities (workaround for no list option)
                    $learningActivitiesTable = new Table(array('borderSize' => 8, 'borderColor' => 'DCDCDC'));
                    // add a new row and cell to table for each learning activity
                    foreach($learningActivitiesArr as $index => $learningActivity){
                        $learningActivitiesTable->addRow();
                        $learningActivitiesTable->addCell()->addText(strval($index + 1));
                        $learningActivitiesTable->addCell()->addText($learningActivity);
                    }
                    // add learning activities table to word doc
                    $templateProcessor->setComplexBlock('learningActivities', $learningActivitiesTable);
                }else{
                    $templateProcessor->cloneBlock('NoLearningActivities');
                    $templateProcessor->setValue('learningActivities', '');

                }
                // tell template processor to include other course staff if user completed the field(s)
                if($otherCourseStaff =  $syllabus->other_instructional_staff){
                    $templateProcessor->cloneBlock('NoOtherInstructionalStaff', 0);
                    // split other course staff string on newline char
                    $otherCourseStaffArr = explode("\n", $otherCourseStaff);
                    // create a table for other course staff (workaround for no list option)
                    $otherCourseStaffTable = new Table(array('borderSize' => 8, 'borderColor' => 'DCDCDC'));
                    // add a new row and cell to table for each course staff member
                    foreach($otherCourseStaffArr as $index => $courseStaffMember){
                        $otherCourseStaffTable->addRow();
                        $otherCourseStaffTable->addCell()->addText(strval($index + 1));
                        $otherCourseStaffTable->addCell()->addText($courseStaffMember);
                    }
                    // add other course staff table to word doc
                    $templateProcessor->setComplexBlock('otherInstructionalStaff', $otherCourseStaffTable);
                }else{
                    $templateProcessor->cloneBlock('NoOtherInstructionalStaff');
                    $templateProcessor->setValue('otherInstructionalStaff', '');
                }
                // tell template processor to include course location if user completed the field(s)
                if ($courseLocation =  $syllabus->course_location) {
                    $templateProcessor->cloneBlock('NoCourseLocation');
                    $templateProcessor->setValue('courseLocation',$courseLocation);
                } else {
                    $templateProcessor->cloneBlock('NoCourseLocation',0);
                }
                
                // tell template processor to include class hours if user completed the field(s)
                if ($classStartTime =  $syllabus->class_start_time && $classEndTime =  $syllabus->class_end_time) {
                    $templateProcessor->cloneBlock('NoClassHours');
                    $templateProcessor->setValues(array('classStartTime' => $syllabus->class_start_time, 'classEndTime' => $syllabus->class_end_time));
                } else {
                    $templateProcessor->cloneBlock('NoClassHours',0);
                }

                // tell template processor to include course schedule if user completed the field(s)
                if ($schedule =  $syllabus->class_meeting_days) {
                    $templateProcessor->cloneBlock('NoCourseDays');
                    $templateProcessor->setValue('schedule',$schedule);
                } else {
                    $templateProcessor->cloneBlock('NoCourseDays', 0);
                }

                // tell template processor to include office hours if user completed the field(s)
                if ($officeHour =  $syllabus->office_hours) {
                    $templateProcessor->cloneBlock('NoOfficeHours');
                    $templateProcessor->setValue('officeHour',$officeHour);
                } else {
                    $templateProcessor->cloneBlock('NoOfficeHours', 0);
                }

                switch($syllabus->course_term){
                    case("W1"):
                        $templateProcessor->setValue('season',"Winter");
                        $templateProcessor->setValue('term',"Term 1");
                    break;
                    case("W2"):
                        $templateProcessor->setValue('season',"Winter");
                        $templateProcessor->setValue('term',"Term 2");
                    break;
                    case("S1"):
                        $templateProcessor->setValue('season',"Summer");
                        $templateProcessor->setValue('term',"Term 1");
                    break;
                    case("S2"):
                        $templateProcessor->setValue('season',"Summer");
                        $templateProcessor->setValue('term',"Term 2");
                    break;
                    default: 
                        $templateProcessor->setValue('term', $syllabus->course_term);
                        $templateProcessor->setValue('season',"");
                }

                if($learningOutcome =  $syllabus->learning_outcomes){
                    $templateProcessor->cloneBlock('NolearningOutcomes', 0);
                    // split learning outcomes string on newline char
                    $learningOutcomes = explode("\n", $learningOutcome);
                    // create a table for learning outcomes (workaround for no list option)
                    $learningOutcomesTable = new Table(array('borderSize'=>8, 'borderColor' => 'DCDCDC'));
                    // add a new row and cell to table for each learning outcome
                    foreach($learningOutcomes as $outcome) {
                        $learningOutcomesTable->addRow();
                        $learningOutcomesTable->addCell()->addText($outcome);
                    }
                    // add learning outcome table to word doc
                    $templateProcessor->setComplexBlock('learningOutcomes',$learningOutcomesTable);
                }else{
                    $templateProcessor->cloneBlock('NolearningOutcomes');
                    $templateProcessor->setValue('learningOutcomes', '');
                }

                if($learningAssessments =  $syllabus->learning_assessments){
                    $templateProcessor->cloneBlock('NoLearningAssessments', 0);
                    // split assessment methods string on newline char
                    $assessmentMethods = explode("\n", $learningAssessments);
                    // create a table for learning outcomes (workaround for no list option)
                    $assessmentMethodsTable = new Table(array('borderSize' => 8, 'borderColor' => 'DCDCDC'));
                    // add a new row and cell to table for each assessment method
                    foreach($assessmentMethods as $index => $assessmentMethod){
                        $assessmentMethodsTable->addRow();
                        $assessmentMethodsTable->addCell()->addText(strval($index + 1));
                        $assessmentMethodsTable->addCell()->addText($assessmentMethod);
                    }
                    // add assessment methods table to word doc
                    $templateProcessor->setComplexBlock('learningAssessments', $assessmentMethodsTable);
                }else{
                    $templateProcessor->cloneBlock('NoLearningAssessments');
                    $templateProcessor->setValue('learningAssessments', '');

                }
                // include vancouver course learning resources in template
                if($learningResources =  $syllabus->learning_resources){
                    $templateProcessor->cloneBlock('NoCourseLearningResources');
                    $templateProcessor->setValue('courseLearningResources', $learningResources);
                }else{
                    $templateProcessor->cloneBlock('NoCourseLearningResources', 0);
                }
        
                if($learningMaterials =  $syllabus->learning_materials){
                    $templateProcessor->cloneBlock('NoLearningMaterials', 0);
                    $templateProcessor->setValue('learningMaterials',$learningMaterials);
                }else{
                    $templateProcessor->cloneBlock('NoLearningMaterials');
                    $templateProcessor->setValue('learningMaterials', '');

                }

                if ($learningAnalytics =  $vancouverSyllabus->learning_analytics) {
                    $templateProcessor->cloneBlock('NoLearningAnalytics');
                    $templateProcessor->setValue('learningAnalytics', $learningAnalytics);
                } else {
                    $templateProcessor->cloneBlock('NoLearningAnalytics', 0);
                }

                $allVancouverSyllabusResources = VancouverSyllabusResource::all();
                $selectedVancouverSyllabusResourceIds = SyllabusResourceVancouver::where('syllabus_id', $syllabus->id)->pluck('v_syllabus_resource_id')->toArray();
                
                foreach ($allVancouverSyllabusResources as $resource) {
                    if (in_array($resource->id, $selectedVancouverSyllabusResourceIds)) {
                        $templateProcessor->cloneBlock($resource->id_name);
                        $templateProcessor->setValue($resource->id_name . '-title', strtoupper($resource->title));
                        // $templateProcessor->setValue($resource->id_name . '-description', $resource->description);
                    } else {
                        $templateProcessor->cloneBlock($resource->id_name, 0);
                    }
                }  

            break;
        }

        // add required form fields common to both campuses to template
        $templateProcessor->setValues(array('courseTitle'=> $syllabus->course_title,'courseCode' => $syllabus->course_code, 'courseNumber'=> $syllabus->course_num, 'courseYear'=> $syllabus->course_year,));

        $syllabusInstructors = SyllabusInstructor::where('syllabus_id', $syllabus->id)->get();
        $templateProcessor->setValue('courseInstructor', $syllabusInstructors->implode('name', ', '));
        
        switch ($syllabus->delivery_modality) {
            case "M" :
                $templateProcessor->setValue('deliveryModality', 'Multi-Access');
                break;
            case "I" :
                $templateProcessor->setValue('deliveryModality', 'In-Person');
                break;
            case "B" :
                $templateProcessor->setValue('deliveryModality', 'Hybrid');
                break;
            default:
                $templateProcessor->setValue('deliveryModality', 'Online');
        }

        // date the syllabus
        $templateProcessor->setValue('dateGenerated', date('d, M Y'));

        if($faculty = $syllabus->faculty){
            $templateProcessor->cloneBlock('NoFaculty');
            $templateProcessor->setValue('faculty', $faculty);
        }else{
            $templateProcessor->cloneBlock('NoFaculty', 0);
        }

        if($department = $syllabus->department){
            $templateProcessor->cloneBlock('NoDepartment');
            $templateProcessor->setValue('department', $department);
        }else{
            $templateProcessor->cloneBlock('NoDepartment', 0);
        }

        
        if($latePolicy = $syllabus->late_policy){
            $templateProcessor->cloneBlock('NolatePolicy');
            $templateProcessor->setValue('latePolicy',$latePolicy);
        }else{
            $templateProcessor->cloneBlock('NolatePolicy',0);
        }

        if($missingExam = $syllabus->missed_exam_policy){
            $templateProcessor->cloneBlock('NoMissingExam');
            $templateProcessor->setValue('missingExam',$missingExam);
        }else{
            $templateProcessor->cloneBlock('NoMissingExam',0);
        }

        if($missingActivity = $syllabus->missed_activity_policy){
            $templateProcessor->cloneBlock('NomissingActivity');
            $templateProcessor->setValue('missingActivity',$missingActivity);
        }else{
            $templateProcessor->cloneBlock('NomissingActivity',0);
        }

        if($passingCriteria = $syllabus->passing_criteria){
            $templateProcessor->cloneBlock('NopassingCriteria');
            $templateProcessor->setValue('passingCriteria',$passingCriteria);
        }else{
            $templateProcessor->cloneBlock('NopassingCriteria',0);
        }

        // add course schedule table to word document
        $courseScheduleTblColsCount = CourseSchedule::where('syllabus_id', $syllabus->id)->where('row', 0)->get()->count();
        $courseScheduleTbl['rows'] = CourseSchedule::where('syllabus_id', $syllabus->id)->get()->chunk($courseScheduleTblColsCount);
        if($courseScheduleTbl['rows']) {
            $templateProcessor->cloneBlock('NoCourseScheduleTbl');
            $courseScheduleTable = new Table(array('borderSize' => 8, 'borderColor' => 'DCDCDC'));
            // add a new row and cell to table for each learning activity
            foreach ($courseScheduleTbl['rows'] as $rowIndex => $row) {
                // add a row to the table
                $courseScheduleTable->addRow();
                if ($rowIndex == 0) {
                    foreach ($row as $headerIndex => $header) {
                        $heading = ($header->val) ? $header->val : '';
                        $courseScheduleTable->addCell(null, array('bgColor' => 'd9d9d9',))->addText($heading, array('bold' => true, 'size' => 14,));
                    }
                } else {
                    foreach ($row as $colIndex => $rowItem) {
                        $data = ($rowItem->val) ? $rowItem->val : '';
                        $courseScheduleTable->addCell()->addText($data);
                    }
                }
            }
            // add course schedule table to word doc
            $templateProcessor->setComplexBlock('courseScheduleTbl', $courseScheduleTable);

        } else {
            $templateProcessor->cloneBlock('NoCourseScheduleTbl');
            $templateProcessor->setValue('courseScheduleTbl', '');
        }

        // set document name
        $fileName = 'syllabus';   
        // word file ext
        $wordFileExt = '.docx';         
        // save word document on server
        $templateProcessor->saveAs($fileName . $wordFileExt);
        
        if ($ext == 'pdf') {
            // pdf file ext
            $pdfFileExt = '.pdf';
            $pdfRendererPath = base_path(DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'dompdf' . DIRECTORY_SEPARATOR . 'dompdf');
            Settings::setPdfRendererPath($pdfRendererPath);
            Settings::setPdfRendererName('DomPDF');   
            // get path to word file
            $wordFilePath = config('app.env') == 'local' ? public_path($fileName . $wordFileExt) : base_path('html' . DIRECTORY_SEPARATOR . $fileName . $wordFileExt);
            // load word file
            $wordFileContent = IOFactory::load($wordFilePath);
            $pdfWriter = IOFactory::createWriter($wordFileContent, 'PDF');
            $pdfWriter->save($fileName . $pdfFileExt);
            // delete the word version
            unlink($fileName . $wordFileExt);        
            // return pdf download response 
            return response()->download($fileName . $pdfFileExt)->deleteFileAfterSend(true);
    
        } 

        return response()->download($fileName . $wordFileExt)->deleteFileAfterSend(true);
    }

    public function duplicate(Request $request, $syllabusId) {

        // validate request
        $request->validate([
            'course_title' => ['required'],
            'course_code' => ['required'],
            'course_num' => ['required'],
        ]);

        $oldSyllabus = Syllabus::find($syllabusId);

        $syllabus = $oldSyllabus->replicate();
        $syllabus->course_title = $request->input('course_title');
        $syllabus->course_code = $request->input('course_code');
        $syllabus->course_num = $request->input('course_num');
        $syllabus->created_at = Carbon::now();
        $syllabus->save();

        // duplicate course instructors
        $syllabusInstructors = SyllabusInstructor::where('syllabus_id', $oldSyllabus->id)->get();
        foreach ($syllabusInstructors as $syllabusInstructor) {
            $duplicateSyllabusInstructor = $syllabusInstructor->replicate();
            $duplicateSyllabusInstructor->syllabus_id = $syllabus->id;
            $duplicateSyllabusInstructor->created_at = Carbon::now();
            $duplicateSyllabusInstructor->save();
        }
        
        if ($oldSyllabus->campus == 'O') {
            $oldOkSyllabus = OkanaganSyllabus::where('syllabus_id', $syllabusId)->first();

            $okSyllabus = new OkanaganSyllabus;
            $okSyllabus->syllabus_id = $syllabus->id;
            $okSyllabus->course_format = $oldOkSyllabus->course_format;
            $okSyllabus->course_overview = $oldOkSyllabus->course_overview;
            $okSyllabus->save();

            $oldOkSyllabiResources = SyllabusResourceOkanagan::where('syllabus_id', $syllabusId)->get();
            foreach ($oldOkSyllabiResources as $oldOKSyllabiResource) {
                $newOkSyllabusResource = new SyllabusResourceOkanagan;
                $newOkSyllabusResource->syllabus_id = $syllabus->id;
                $newOkSyllabusResource->o_syllabus_resource_id = $oldOKSyllabiResource->o_syllabus_resource_id;
                $newOkSyllabusResource->save();
            }
        } elseif ($oldSyllabus->campus == 'V') {
            $oldVanSyllabus = VancouverSyllabus::where('syllabus_id', $syllabusId)->first();

            $newVanSyllabus = $oldVanSyllabus->replicate();
            $newVanSyllabus->syllabus_id = $syllabus->id;
            $newVanSyllabus->created_at = Carbon::now();
            $newVanSyllabus->save();

            $oldVanSyllabiResources = SyllabusResourceVancouver::where('syllabus_id', $syllabusId)->get();
            foreach ($oldVanSyllabiResources as $oldVanSyllabiResource) {
                $newVanSyllabusResource = new SyllabusResourceVancouver;
                $newVanSyllabusResource->syllabus_id = $syllabus->id;
                $newVanSyllabusResource->v_syllabus_resource_id = $oldVanSyllabiResource->v_syllabus_resource_id;
                $newVanSyllabusResource->save();
            }
        }

        // duplicate course schedules
        $oldCourseSchedules = CourseSchedule::where('syllabus_id', $syllabusId)->get();
        foreach ($oldCourseSchedules as $oldCourseSchedule) {
            $newCourseSchedule = $oldCourseSchedule->replicate();
            $newCourseSchedule->syllabus_id = $syllabus->id;
            $newCourseSchedule->created_at = Carbon::now();
            $newCourseSchedule->save();
        }

        $user = User::find(Auth::id());
        // create a new syllabus user
        $syllabusUser = new SyllabusUser;
        // set relationship between syllabus and user
        $syllabusUser->syllabus_id = $syllabus->id;
        $syllabusUser->user_id = $user->id;
        $syllabusUser->permission = 1;
        $syllabusUser->save();

        return redirect()->route('home');
    }
}
