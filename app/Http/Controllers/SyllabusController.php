<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\MappingScale;
use App\Models\Program;
use App\Models\syllabus\OkanaganSyllabus;
use App\Models\syllabus\OkanaganSyllabusResource;
use App\Models\syllabus\Syllabus;
use App\Models\syllabus\SyllabusResourceOkanagan;
use App\Models\syllabus\SyllabusResourceVancouver;
use App\Models\syllabus\SyllabusUser;
use App\Models\syllabus\VancouverSyllabus;
use App\Models\syllabus\VancouverSyllabusResource;
use App\Models\SyllabusInstructor;
use App\Models\SyllabusProgram;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\TemplateProcessor;

define('INPUT_TIPS', [
    'otherCourseStaff' => 'At the discretion of the course instructor, the names of any other student-facing members of teaching staff such as teaching assistants involved in the offering of the course (if not available on the Student Service Centre or on Workday), and details of when and by what means students may contact them.',
    'learningOutcomes' => 'i.e., what is to be achieved and assessed in the course.',
    'learningAssessments' => 'The methods used to assess achievement of stated learning outcomes or objectives, including the weighting of each component in the final grade.',
    'learningActivities' => 'Do you expect students to participate in class? In what ways? (e.g., case studies, using “clickers” to answer questions, working in small groups, etc.) Is participation in on-line discussions required? Are readings required in advance with answers to be submitted to discussion questions or problem sets? 
    Is an oral presentation required? Is there a field excursion?',
    'learningMaterials' => 'List of required learning materials for your course (including textbooks, reading packages, on-line assessment tools, lab and field trip manuals) and where they might be obtained (e.g. the Bookstore if you ordered a text or a reading package, your department office if an in-house resource is available, the Library through their <a href="https://library.ok.ubc.ca/services/course-reserves/" target="_blank" rel="noopener noreferrer">course-reserve system</a>). Providing students with at least an estimate of the costs of materials is expected. Explanation of any on-line learning management system used (e.g.Canvas).',
    'latePolicy' => 'State your policies on re-grading of marked work and on late submissions. What are the penalties for late assignments?',
    'missedActivityPolicy' => 'In accordance with policy on <a href="https://www.calendar.ubc.ca/okanagan/index.cfm?tree=3,41,90,1014" target="_blank" rel="noopener noreferrer">Grading Practices</a> and <a href="https://www.calendar.ubc.ca/okanagan/index.cfm?tree=3,48,1127,0" target="_blank" rel="noopener noreferrer">Academic Concessions</a>, state how you deal with missed in-class assessments (e.g., are make-up tests offered for missed in-class tests, do you count the best X of Y assignments/tests, do you re-weight marks from a missed test onto later assessments?)',
    'courseDescription' => 'As in the Academic Calendar or, for courses without a published description, include a brief representative one.',
    'okanaganCourseDescription' => 'Course descriptions are provided in the UBCO Okanagan <a href="https://okanagan.calendar.ubc.ca/course-descriptions" target="_blank" rel="noopener noreferrer">Academic Calendar</a>.',
    'coursePrereqs' => 'Is there a course that students must have passed before taking this course?',
    'courseCoreqs' => 'Is there a course that students must take concurrently (if not before)?',
    'courseContacts' => 'Include any and all contact information you are willing to have students use. If you have a preferred mode, state it. For example, do you accept email inquiries? What is your typical response time?',
    'officeHours' => 'Details of when, and by what means students may contact the course instructor(s).',
    'courseStructure' => 'First, the basic components: lecture, lab, discussion, tutorial. Typically the locations are on the Student Service Centre but you may wish to include them. Then a description of how your classes are structured: Do you use traditional lecturing? Do you provide notes (outlines)? Do you combine on-line and in-class activity? You may wish to combine this section and Learning Outcomes below to provide an opportunity to introduce students to your philosophy of learning, to the culture of your discipline and how this course fits in the larger context.',
    'courseSchedule' => 'This may be a weekly schedule, it may be class by class, but let students know that if changes occur, they will be informed.',
    'instructorBioStatement' => 'You may wish to include your department/faculty/school and other information about your academic qualifications, interests, etc.',
    'learningResources' => 'Include information on any resources to support student learning that are supported by the academic unit responsible for the course.',
    'learningAnalytics' => 'If your course or department has a learning resource centre (physical or virtual), inform your students. Who will students encounter there? Are the staff knowledgeable about this course?',
    'officeLocation' => 'Building & Room Number',
    'creativeCommons' => 'Include a copyright statement or include a Creative Commons Open Copyright license of your choosing. Visit the <a href="https://creativecommons.org/licenses/" target="_blank" rel="noopener noreferrer">Creative Commons Website</a> for options and more information. Need help deciding? Try using the <a href="https://creativecommons.org/choose/" target="_blank" rel="noopener noreferrer">Creative Commons License Chooser</a>.',
    'uniPolicy' => 'Hearing from each course instructor about University policies and values can help to emphasize their importance to students. To fulfil the policy, you need only to present the following paragraph with the link to the web page that provides details and links to specific policies and resources. You may wish to take the opportunity to relate the ideas to your own course as part of your students’ education. This policy is <b>always included</b> in a generated Vancouver syllabus.',
    'customResource' => 'Include any additional information or resources that have not been provided.',
    'saveWarning' => 'Be sure to save your content regularly by clicking the save button <i class="bi bi-clipboard2-check-fill"></i> at the top and bottom of this page.',
    'crossListed' => 'Is this a Cross-Listed Course? Per <a href="https://senate.ubc.ca/okanagan/forms/" target="_blank" rel="noopener noreferrer">Curriculum Guidelines</a>.',
    'courseStructureOK' =>'A description of the course structure such as, for example, lecture, lab, tutorial, flipped classroom, mixed-mode, contact hours per week; day, time, and location of classes, or other activities that may not be available on the Student Service Centre or on Workday.'
]);

class SyllabusController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('hasAccess');
    }

    public function index(Request $request)
    {
        // get the current user
        $user = User::find(Auth::id());
        $syllabusId = $request->input('syllabus_id');

        $url = parse_url($_SERVER['REQUEST_URI']);
        if ($syllabusId == null) {
            $pathArr = explode('/', $url['path']);
            $syllabusId = $pathArr[count($pathArr) - 1];
        }

        // get this users courses
        $myCourses = $user->courses;
        // get vancouver campus resources
        $vancouverSyllabusResources = VancouverSyllabusResource::all();
        // get okanagan campus resources
        $okanaganSyllabusResources = OkanaganSyllabusResource::all();
        // get faculties
        $faculties = Faculty::orderBy('faculty')->get();
        // get departments
        $departments = Department::orderBy('department')->get();

        $courseAlignment = null;
        $outcomeMaps = null;
        if ($syllabusId != null && $syllabusId != 'syllabusGenerator') {
            $syllabus = Syllabus::find($syllabusId);
            // get this users permission level
            $userPermission = $user->syllabi->where('id', $syllabusId)->first()->pivot->permission;
            // check for user settings
            if (isset($syllabus->course_id)) {
                $importCourse = Course::find($syllabus->course_id);
                if ($syllabus->include_alignment) {

                    $courseAlignment = $importCourse->learningOutcomes;
                    foreach ($courseAlignment as $clo) {
                        $clo->assessmentMethods;
                        $clo->learningActivities;
                    }
                }
                $syllabusProgramIds = SyllabusProgram::where('syllabus_id', $syllabus->id)->pluck('program_id')->toArray();
                if (count($syllabusProgramIds) > 0) {
                    $outcomeMaps = $this->getOutcomeMaps($syllabusProgramIds, $importCourse->course_id);
                }
            }

            // get  Vancouver Syllabus
            $vancouverSyllabus = VancouverSyllabus::where('syllabus_id', $syllabus->id);

            // get  Okanagan Syllabus
            $okanaganSyllabus = OkanaganSyllabus::where('syllabus_id', $syllabus->id);

            // show view based on user permission
            switch ($userPermission) {
                // owner
                case 1:
                    return $this->syllabusEditor($syllabus, ['user' => $user, 'myCourses' => $myCourses, 'vancouverSyllabusResources' => $vancouverSyllabusResources, 'okanaganSyllabusResources' => $okanaganSyllabusResources, 'faculties' => $faculties, 'departments' => $departments, 'courseAlignment' => $courseAlignment, 'outcomeMaps' => $outcomeMaps]);

                    break;
                case 2:
                    // editor
                    return $this->syllabusEditor($syllabus, ['user' => $user, 'myCourses' => $myCourses, 'vancouverSyllabusResources' => $vancouverSyllabusResources, 'okanaganSyllabusResources' => $okanaganSyllabusResources, 'faculties' => $faculties, 'departments' => $departments, 'courseAlignment' => $courseAlignment, 'outcomeMaps' => $outcomeMaps]);
                    break;
                    // viewer
                case 3:
                    return $this->syllabusViewer($syllabus, ['vancouverSyllabusResources' => $vancouverSyllabusResources, 'okanaganSyllabusResources' => $okanaganSyllabusResources, 'courseAlignment' => $courseAlignment, 'outcomeMaps' => $outcomeMaps, 'vancouverSyllabus' => $vancouverSyllabus, 'okanaganSyllabus' => $okanaganSyllabus]);

                    break;
                    // return view to create a syllabus as default
                default:
                    return view('syllabus.syllabusGenerator')->with('user', $user)->with('myCourses', $myCourses)->with('inputFieldDescriptions', INPUT_TIPS)->with('okanaganSyllabusResources', $okanaganSyllabusResources)->with('vancouverSyllabusResources', $vancouverSyllabusResources)->with('faculties', $faculties)->with('departments', $departments)->with('syllabus', []);
            }

        // return view to create a syllabus
        } else {
            return view('syllabus.syllabus')->with('user', $user)->with('myCourses', $myCourses)->with('inputFieldDescriptions', INPUT_TIPS)->with('okanaganSyllabusResources', $okanaganSyllabusResources)->with('vancouverSyllabusResources', $vancouverSyllabusResources)->with('faculties', $faculties)->with('departments', $departments)->with('syllabus', []);
        }
    }

    public function syllabusEditor($syllabus, $data)
    {
        // get this syllabus
        $syllabusInstructors = SyllabusInstructor::where('syllabus_id', $syllabus->id)->get();
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
                return view('syllabus.syllabus')->with('user', $data['user'])->with('myCourses', $data['myCourses'])->with('syllabusInstructors', $syllabusInstructors)->with('myCourseScheduleTbl', $courseScheduleTbl)->with('courseScheduleTblRowsCount', $courseScheduleTblRowsCount)->with('inputFieldDescriptions', INPUT_TIPS)->with('okanaganSyllabusResources', $data['okanaganSyllabusResources'])->with('vancouverSyllabusResources', $data['vancouverSyllabusResources'])->with('syllabus', $syllabus)->with('okanaganSyllabus', $okanaganSyllabus)->with('selectedOkanaganSyllabusResourceIds', $selectedOkanaganSyllabusResourceIds)->with('faculties', $data['faculties'])->with('departments', $data['departments'])->with('courseAlignment', $data['courseAlignment'])->with('outcomeMaps', $data['outcomeMaps']);
                break;
            case 'V':
                // get data specific to vancouver campus
                $vancouverSyllabus = VancouverSyllabus::where('syllabus_id', $syllabus->id)->first();
                // get selected vancouver syllabus resource
                $selectedVancouverSyllabusResourceIds = SyllabusResourceVancouver::where('syllabus_id', $syllabus->id)->pluck('v_syllabus_resource_id')->toArray();

                // return view with vancouver syllabus data
                return view('syllabus.syllabus')->with('user', $data['user'])->with('myCourses', $data['myCourses'])->with('syllabusInstructors', $syllabusInstructors)->with('myCourseScheduleTbl', $courseScheduleTbl)->with('courseScheduleTblRowsCount', $courseScheduleTblRowsCount)->with('inputFieldDescriptions', INPUT_TIPS)->with('okanaganSyllabusResources', $data['okanaganSyllabusResources'])->with('vancouverSyllabusResources', $data['vancouverSyllabusResources'])->with('syllabus', $syllabus)->with('vancouverSyllabus', $vancouverSyllabus)->with('selectedVancouverSyllabusResourceIds', $selectedVancouverSyllabusResourceIds)->with('faculties', $data['faculties'])->with('departments', $data['departments'])->with('courseAlignment', $data['courseAlignment'])->with('outcomeMaps', $data['outcomeMaps']);
                break;

        }

    }

    public function syllabusViewer($syllabus, $data)
    {
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
                return view('syllabus.syllabusViewerOkanagan')->with('myCourseScheduleTbl', $courseScheduleTbl)->with('courseScheduleTblRowsCount', $courseScheduleTblRowsCount)->with('inputFieldDescriptions', INPUT_TIPS)->with('okanaganSyllabusResources', $data['okanaganSyllabusResources'])->with('syllabus', $syllabus)->with('okanaganSyllabus', $okanaganSyllabus)->with('selectedOkanaganSyllabusResourceIds', $selectedOkanaganSyllabusResourceIds)->with('syllabusInstructors', $syllabusInstructors)->with('courseAlignment', $data['courseAlignment'])->with('outcomeMaps', $data['outcomeMaps']);
                break;
            case 'V':
                // get data specific to vancouver campus
                $vancouverSyllabus = VancouverSyllabus::where('syllabus_id', $syllabus->id)->first();
                // get selected vancouver syllabus resource
                $selectedVancouverSyllabusResourceIds = SyllabusResourceVancouver::where('syllabus_id', $syllabus->id)->pluck('v_syllabus_resource_id')->toArray();

                // return view with vancouver syllabus data
                return view('syllabus.syllabusViewerVancouver')->with('myCourseScheduleTbl', $courseScheduleTbl)->with('courseScheduleTblRowsCount', $courseScheduleTblRowsCount)->with('inputFieldDescriptions', INPUT_TIPS)->with('vancouverSyllabusResources', $data['vancouverSyllabusResources'])->with('syllabus', $syllabus)->with('vancouverSyllabus', $vancouverSyllabus)->with('selectedVancouverSyllabusResourceIds', $selectedVancouverSyllabusResourceIds)->with('syllabusInstructors', $syllabusInstructors)->with('courseAlignment', $data['courseAlignment'])->with('outcomeMaps', $data['outcomeMaps']);
        }
    }

    /**
     * Save syllabus.
     *
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

        $syllabusId = $syllabus->id;
        // get this users courses
        $myCourses = $user->courses;
        // get vancouver campus resources
        $vancouverSyllabusResources = VancouverSyllabusResource::all();
        // get okanagan campus resources
        $okanaganSyllabusResources = OkanaganSyllabusResource::all();
        // get faculties
        $faculties = Faculty::orderBy('faculty')->get();
        // get departments
        $departments = Department::orderBy('department')->get();

        $courseAlignment = null;
        $outcomeMaps = null;
        if ($syllabusId != null) {
            $syllabus = Syllabus::find($syllabusId);
            // get this users permission level
            $userPermission = $user->syllabi->where('id', $syllabusId)->first()->pivot->permission;
            // check for user settings
            if (isset($syllabus->course_id)) {
                $importCourse = Course::find($syllabus->course_id);
                if ($syllabus->include_alignment) {

                    $courseAlignment = $importCourse->learningOutcomes;
                    foreach ($courseAlignment as $clo) {
                        $clo->assessmentMethods;
                        $clo->learningActivities;
                    }
                }
                $syllabusProgramIds = SyllabusProgram::where('syllabus_id', $syllabus->id)->pluck('program_id')->toArray();
                if (count($syllabusProgramIds) > 0) {
                    $outcomeMaps = $this->getOutcomeMaps($syllabusProgramIds, $importCourse->course_id);
                }
            }

            // get  Vancouver Syllabus
            $vancouverSyllabus = VancouverSyllabus::where('syllabus_id', $syllabus->id);

            // get  Okanagan Syllabus
            $okanaganSyllabus = OkanaganSyllabus::where('syllabus_id', $syllabus->id);

            // show view based on user permission
            switch ($userPermission) {
                // owner
                case 1:
                    return $this->syllabusEditor($syllabus, ['user' => $user, 'myCourses' => $myCourses, 'vancouverSyllabusResources' => $vancouverSyllabusResources, 'okanaganSyllabusResources' => $okanaganSyllabusResources, 'faculties' => $faculties, 'departments' => $departments, 'courseAlignment' => $courseAlignment, 'outcomeMaps' => $outcomeMaps]);

                    break;
                case 2:
                    // editor
                    return $this->syllabusEditor($syllabus, ['user' => $user, 'myCourses' => $myCourses, 'vancouverSyllabusResources' => $vancouverSyllabusResources, 'okanaganSyllabusResources' => $okanaganSyllabusResources, 'faculties' => $faculties, 'departments' => $departments, 'courseAlignment' => $courseAlignment, 'outcomeMaps' => $outcomeMaps]);
                    break;
                    // viewer
                case 3:
                    return $this->syllabusViewer($syllabus, ['vancouverSyllabusResources' => $vancouverSyllabusResources, 'okanaganSyllabusResources' => $okanaganSyllabusResources, 'courseAlignment' => $courseAlignment, 'outcomeMaps' => $outcomeMaps, 'vancouverSyllabus' => $vancouverSyllabus, 'okanaganSyllabus' => $okanaganSyllabus]);

                    break;
                    // return view to create a syllabus as default
                default:
                    return view('syllabus.syllabusGenerator')->with('user', $user)->with('myCourses', $myCourses)->with('inputFieldDescriptions', INPUT_TIPS)->with('okanaganSyllabusResources', $okanaganSyllabusResources)->with('vancouverSyllabusResources', $vancouverSyllabusResources)->with('faculties', $faculties)->with('departments', $departments)->with('syllabus', []);
            }

        // return view to create a syllabus
        } else {
            return view('syllabus.syllabus')->with('user', $user)->with('myCourses', $myCourses)->with('inputFieldDescriptions', INPUT_TIPS)->with('okanaganSyllabusResources', $okanaganSyllabusResources)->with('vancouverSyllabusResources', $vancouverSyllabusResources)->with('faculties', $faculties)->with('departments', $departments)->with('syllabus', []);
        }
    }

    /**
     * Create a new syllabus resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // get current user
        $user = User::where('id', Auth::id())->first();
        $campus = $request->input('campus');
        // create a new syllabus and set required data values
        $syllabus = new Syllabus;
        $syllabus->campus = $campus;
        $syllabus->course_title = $request->input('courseTitle');
        $syllabus->course_code = $request->input('courseCode');
        $syllabus->course_num = $request->input('courseNumber');
        $syllabus->custom_resource = $request->input('customResource');
        $syllabus->custom_resource_title = $request->input('customResourceTitle');
        $syllabus->delivery_modality = $request->input('deliveryModality');
        $syllabus->course_instructor = $request->input('courseInstructor')[0];
        $request->input('courseSemester') == 'O' ? $syllabus->course_term = $request->input('courseSemesterOther') : $syllabus->course_term = $request->input('courseSemester');
        $syllabus->course_year = $request->input('courseYear');
        $courseInstructors = $request->input('courseInstructor');
        $courseInstructorEmails = $request->input('courseInstructorEmail');
        $syllabus->course_section = $request->input('courseSection');
        $syllabus->prerequisites = $request->input('prerequisites');
        $syllabus->corequisites = $request->input('corequisites');

        if ($request->input('crossListed') == 1) {
            $syllabus->cross_listed_code = $request->input('courseCodeCL');
            $syllabus->cross_listed_num = $request->input('courseNumberCL');
        } else {
            $syllabus->cross_listed_code = null;
            $syllabus->cross_listed_num = null;
        }

        if ($request->input('copyright') == 1) {
            $syllabus->cc_license = null;
            $syllabus->copyright = true;
        } else {
            $syllabus->cc_license = $request->input('creativeCommons');
            $syllabus->copyright = false;
        }
        //Land Acknowledgement
        if ($request->input('landAck') == null) {
            $syllabus->land_acknow = null;
        } else {
            $syllabus->land_acknow = true;
        }

        $syllabus->save();

        // set optional syllabus fields common to both campuses
        $syllabus->faculty = $request->input('faculty', null);
        $syllabus->department = $request->input('department', null);
        $syllabus->course_location = $request->input('courseLocation', null);
        $syllabus->other_instructional_staff = $request->input('otherCourseStaff', null);
        $syllabus->class_start_time = $request->input('startTime', null);
        $syllabus->class_end_time = $request->input('endTime', null);
        if ($classMeetingDays = $request->input('schedule', null)) {
            $classSchedule = '';
            foreach ($classMeetingDays as $day) {
                $classSchedule = ($classSchedule == '' ? $day : $classSchedule.'/'.$day);
            }

            $syllabus->class_meeting_days = $classSchedule;
        }
        $syllabus->learning_outcomes = $request->input('learningOutcome', null);
        $syllabus->learning_assessments = $request->input('learningAssessments', null);
        $syllabus->learning_activities = $request->input('learningActivities', null);
        $syllabus->late_policy = $request->input('latePolicy', null);
        $syllabus->missed_exam_policy = $request->input('missingExam', null);
        $syllabus->missed_activity_policy = $request->input('missingActivity', null);
        $syllabus->passing_criteria = $request->input('passingCriteria', null);
        $syllabus->learning_materials = $request->input('learningMaterials', null);
        $syllabus->learning_resources = $request->input('learningResources');
        $importCourseSettings = $request->input('import_course_settings', null);

        if ($importCourseSettings) {
            $this->createImportCourseSettings($syllabus->id, $importCourseSettings);
        }

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
            foreach ($courseScheduleTblHeadings as $colIndex => $courseScheduleTblHeading) {
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
                // create okanagan syllabus record
                $okanaganSyllabus = new OkanaganSyllabus;
                $okanaganSyllabus->syllabus_id = $syllabus->id;
                // set optional syllabus fields for Okangan campus
                $okanaganSyllabus->course_format = $request->input('courseFormat');
                $okanaganSyllabus->course_overview = $request->input('courseOverview');
                $okanaganSyllabus->course_description = $request->input('courseDesc');

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
        // save syllabus
        $syllabus->save();
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
     * Helper to create the import course settings (e.g. import course alignment and program outcome maps)
     *
     * @param array specifying what information needs to be imported/linked from a course to a syllabus
     */
    private function createImportCourseSettings($syllabusId, $settings)
    {
        $syllabus = Syllabus::find($syllabusId);
        // reset previous syllabi import settings
        $syllabus->course_id = null;
        $syllabus->include_alignment = 0;
        SyllabusProgram::where('syllabus_id', $syllabus->id)->delete();
        // check if course alignment table was included
        if (array_key_exists('importCourseAlignment', $settings)) {
            $syllabus->course_id = $settings['courseId'];
            $syllabus->include_alignment = 1;
        }
        // check if program outcome maps were included
        if (array_key_exists('programs', $settings)) {
            $syllabus->course_id = $settings['courseId'];
            $programIds = $settings['programs'];
            foreach ($programIds as $programId) {
                $syllabiProgram = new SyllabusProgram;
                $syllabiProgram->syllabus_id = $syllabusId;
                $syllabiProgram->program_id = $programId;
                $syllabiProgram->save();
            }
        }
        $syllabus->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $syllabusId)
    {
        // get the syllabus, and start updating it
        $syllabus = Syllabus::find($syllabusId);
        $campus = $request->input('campus');
        $syllabus->campus = $campus;
        $syllabus->course_title = $request->input('courseTitle');
        $syllabus->course_code = $request->input('courseCode');
        $syllabus->custom_resource = $request->input('customResource');
        $syllabus->custom_resource_title = $request->input('customResourceTitle');
        $syllabus->course_num = $request->input('courseNumber');
        $syllabus->delivery_modality = $request->input('deliveryModality');
        $courseInstructors = $request->input('courseInstructor');
        $courseInstructorEmails = $request->input('courseInstructorEmail');
        $syllabus->course_instructor = $courseInstructors[0];
        $request->input('courseSemester') == 'O' ? $syllabus->course_term = $request->input('courseSemesterOther') : $syllabus->course_term = $request->input('courseSemester');
        $syllabus->course_year = $request->input('courseYear');
        $importCourseSettings = $request->input('import_course_settings', null);
        $syllabus->cross_listed_code = $request->input('courseCodeCL');
        $syllabus->cross_listed_num = $request->input('courseNumberCL');
        $syllabus->course_section = $request->input('courseSection');
        $syllabus->prerequisites = $request->input('prerequisites');
        $syllabus->corequisites = $request->input('corequisites');

        if ($request->input('crossListed') == 1) {
            $syllabus->cross_listed_code = $request->input('courseCodeCL');
            $syllabus->cross_listed_num = $request->input('courseNumberCL');
        } else {
            $syllabus->cross_listed_code = null;
            $syllabus->cross_listed_num = null;
        }

        //Creative Commons or Copyright
        if ($request->input('copyright') == null) {
            $syllabus->cc_license = null;
            $syllabus->copyright = null;
        } elseif ($request->input('copyright') == 1) {
            $syllabus->cc_license = null;
            $syllabus->copyright = true;
        } else {
            $syllabus->cc_license = $request->input('creativeCommons');
            $syllabus->copyright = false;
        }
        //Land Acknowledgement
        if ($request->input('landAck') == null) {
            $syllabus->land_acknow = null;
        } else {
            $syllabus->land_acknow = true;
        }

        // check if user set import settings and update them

        if ($importCourseSettings) {

            $this->createImportCourseSettings($syllabus->id, $importCourseSettings);
        } else {
            // reset import course settings
            $syllabus->course_id = null;
            SyllabusProgram::where('syllabus_id', $syllabus->id)->delete();
        }

        // update optional syllabus fields common to both campuses
        $syllabus->course_location = $request->input('courseLocation', null);
        $syllabus->other_instructional_staff = $request->input('otherCourseStaff', null);
        $syllabus->office_hours = $request->input('officeHour', null);
        $syllabus->class_start_time = $request->input('startTime', null);
        $syllabus->class_end_time = $request->input('endTime', null);
        $syllabus->faculty = $request->input('faculty', null);
        $syllabus->department = $request->input('department', null);
        if ($classMeetingDays = $request->input('schedule', null)) {
            $classSchedule = '';
            foreach ($classMeetingDays as $day) {
                $classSchedule = ($classSchedule == '' ? $day : $classSchedule.'/'.$day);
            }
            $syllabus->class_meeting_days = $classSchedule;
        } else {
            $syllabus->class_meeting_days = null;
        }

        $syllabus->learning_outcomes = $request->input('learningOutcome', null);
        $syllabus->learning_assessments = $request->input('learningAssessments', null);
        $syllabus->learning_activities = $request->input('learningActivities', null);
        $syllabus->late_policy = $request->input('latePolicy', null);
        $syllabus->missed_exam_policy = $request->input('missingExam', null);
        $syllabus->missed_activity_policy = $request->input('missingActivity', null);
        $syllabus->passing_criteria = $request->input('passingCriteria', null);
        $syllabus->learning_materials = $request->input('learningMaterials', null);
        $syllabus->learning_resources = $request->input('learningResources', null);

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
            foreach ($courseScheduleTblHeadings as $colIndex => $courseScheduleTblHeading) {
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
                    $okanaganSyllabus->course_description = $request->input('courseDesc');
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
                break;
        }

        $syllabus->save();

        return $syllabus;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $syllabusId)
    {
        // find the syllabus to delete
        $syllabus = Syllabus::find($syllabusId);
        // find the current user
        $currentUser = User::find(Auth::id());
        // get the current users permission level for the syllabus to delete
        $currentUserPermission = $currentUser->syllabi->where('id', $syllabusId)->first()->pivot->permission;
        // if the current user owns the syllabus, try delete it
        if ($currentUserPermission == 1) {
            if ($syllabus->delete()) {
                $request->session()->flash('success', 'Your syllabus has been deleted');
            } else {
                $request->session()->flash('error', 'There was an error deleting your syllabus');
            }
        // else the current user does not own the syllabus, flash an error
        } else {
            $request->session()->flash('error', 'You do not have permission to delete this syllabus');
        }

        // return to the dashboard
        return redirect()->route('home');
    }

    /*
        * Helper function to get outcome maps
        * @param Array of programIds
        * @return 2D array[$clos][plos] = $mappingScales
    */

    private function getOutcomeMaps($programIds, $courseId)
    {
        $programsOutcomeMaps = [];
        foreach ($programIds as $programId) {
            $program = Program::find($programId);
            $programsOutcomeMaps[$programId]['program'] = $program;
            $programsOutcomeMaps[$programId]['clos'] = Course::find($courseId)->learningOutcomes;
            foreach ($program->programLearningOutcomes as $programLearningOutcome) {
                $outcomeMaps = $programLearningOutcome->learningOutcomes->where('course_id', $courseId);
                foreach ($outcomeMaps as $outcomeMap) {
                    $programsOutcomeMaps[$programId]['outcomeMap'][$programLearningOutcome->pl_outcome_id][$outcomeMap->l_outcome_id] = MappingScale::find($outcomeMap->pivot->map_scale_id);
                }

            }
        }

        return $programsOutcomeMaps;
    }

    // get existing course information
    // Ajax to get course infomation
    public function getCourseInfo(Request $request)
    {
        // validate request data
        $this->validate($request, [
            'course_id' => 'required',
        ]);
        $courseId = $request->course_id;
        // get the corresponding course
        $course = Course::find($courseId);
        // 2D array with user requested info
        $importCourseSettings = $request->input('importCourseSettings', []);
        // reduce user requested info array to a 1D array
        $importCourseSettings = array_reduce($importCourseSettings, function ($acc, $setting) {
            $temp = [$setting['name']];

            return array_merge($acc, $temp);
        }, []);
        // create return data object with basic course info
        $data['c_title'] = $course->course_title;
        $data['c_code'] = $course->course_code;
        $data['c_num'] = $course->course_num;
        $data['c_del'] = $course->delivery_modality;
        $data['c_year'] = $course->year;
        $data['c_term'] = $course->semester;
        // check if clos were requested
        if (in_array('importLearningOutcomes', $importCourseSettings)) {
            $data['l_outcomes'] = $course->learningOutcomes;
        }
        // check if assessment methods were requested
        if (in_array('importAssessmentMethods', $importCourseSettings)) {
            $data['a_methods'] = $course->assessmentMethods;
        }
        // check if teaching and learning activities were requested
        if (in_array('importLearningActivities', $importCourseSettings)) {
            $data['l_activities'] = $course->learningActivities;
        }
        // check if course alignment was requested
        if (in_array('importCourseAlignment', $importCourseSettings)) {
            $data['course_alignment'] = $course->learningOutcomes;
            foreach ($data['course_alignment'] as $clo) {
                $clo->assessmentMethods;
                $clo->learningActivities;
            }
        }
        // check which program outcome maps were requested
        $data['closForOutcomeMaps'] = null;
        foreach ($course->programs as $program) {
            if (in_array($program->program_id, $importCourseSettings)) {
                if (! isset($data['closForOutcomeMaps'])) {
                    $data['closForOutcomeMaps'] = $course->learningOutcomes;
                }
                $data['programs'][$program->program_id]['programId'] = $program->program_id;
                $data['programs'][$program->program_id]['programTitle'] = $program->program;
                $data['programs'][$program->program_id]['programLearningOutcomes'] = $program->programLearningOutcomes;
                $data['programs'][$program->program_id]['categories'] = $program->ploCategories;
                foreach ($data['programs'][$program->program_id]['categories'] as $category) {
                    $category->plos;
                }
                $uncategorizedPlos = $program->programLearningOutcomes->where('plo_category_id', null);
                $data['programs'][$program->program_id]['uncategorizedPlosCount'] = $uncategorizedPlos->count();
                $data['programs'][$program->program_id]['uncategorizedPlos'] = $uncategorizedPlos;
                $data['programs'][$program->program_id]['outcomeMap'] = $uncategorizedPlos;
                $data['programs'][$program->program_id]['mappingScales'] = $program->mappingScaleLevels;
                $data['programs'][$program->program_id]['outcomeMap'] = $this->getProgramOutcomeMap($program->program_id, $course->course_id);
            }
        }
        $data = json_encode($data);

        return $data;
    }

    /**
     * Create program outcome map data structure
     *
     * @return 2-D array[$ploId][$cloId] = MappingScale
     */
    public function getProgramOutcomeMap($programId, $courseId)
    {
        $program = Program::find($programId);
        $programOutcomeMap = [];
        foreach ($program->programLearningOutcomes as $programLearningOutcome) {
            $outcomeMaps = $programLearningOutcome->learningOutcomes->where('course_id', $courseId);
            foreach ($outcomeMaps as $outcomeMap) {
                $programOutcomeMap[$programLearningOutcome->pl_outcome_id][$outcomeMap->l_outcome_id] = MappingScale::find($outcomeMap->pivot->map_scale_id);
            }
        }

        return $programOutcomeMap;
    }

    /**
     * Download the given syllabus $syllabusId in $ext format
     *
     * @param  string  $ext: the file extension
     * @return a download response
     */
    public function download(int $syllabusId, $ext)
    {

        $syllabus = Syllabus::find($syllabusId);
        $tableStyle = ['borderSize' => 8, 'borderColor' => 'DCDCDC', 'unit' => TblWidth::PERCENT, 'width' => 100 * 50, 'cellMargin' => Converter::cmToTwip(0.25)];
        $tableHeaderRowStyle = ['bgColor' => 'c6e0f5', 'borderBottomColor' => '000000'];
        $secondaryTableHeaderRowStyle = ['bgColor' => 'dfe0e1', 'borderBottomColor' => '000000'];
        $tableHeaderFontStyle = ['bold' => true];

        switch ($syllabus->campus) {
            case 'O':
                // create a new template for this syllabus
                $templateProcessor = new TemplateProcessor('word-template/UBC-O_default.docx');
                // get data specific to the okanagan campus
                $okanaganSyllabus = OkanaganSyllabus::where('syllabus_id', $syllabus->id)->first();

                //Course Description Okanagan

                if ($okanaganSyllabus->course_description) {

                    $CDArr = explode("\n", $okanaganSyllabus->course_description);
                    $i = 0;
                    if ($ext == 'pdf') {
                        foreach ($CDArr as $index => $courseDesc) {
                            $templateProcessor->cloneBlock('NocourseDescription');
                            $templateProcessor->setValue('courseDescriptionOK'.$i, $courseDesc.'</w:t><w:br/><w:t>');
                            $i++;
                        }

                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('courseDescriptionOK'.$i, '');
                        }
                    } else {
                        $templateProcessor->cloneBlock('NocourseDescription');
                        $templateProcessor->setValue('courseDescriptionOK0', str_replace("\n", '</w:t><w:br/><w:t>', $okanaganSyllabus->course_description));
                        $i++;
                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('courseDescriptionOK'.$i, '');
                        }
                    }

                } else {
                    $templateProcessor->cloneBlock('NocourseDescription', 0);
                }

                //Course Format Okanagan

                if ($okanaganSyllabus->course_format) {
                    $CFArr = explode("\n", $okanaganSyllabus->course_format);
                    $i = 0;
                    if ($ext == 'pdf') {
                        foreach ($CFArr as $index => $courseForm) {
                            $templateProcessor->cloneBlock('NocourseFormat');
                            $templateProcessor->setValue('courseFormat'.$i, $courseForm.'</w:t><w:br/><w:t>');
                            $i++;
                        }

                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('courseFormat'.$i, '');
                        }
                    } else {
                        $templateProcessor->cloneBlock('NocourseFormat');
                        $templateProcessor->setValue('courseFormat0', str_replace("\n", '</w:t><w:br/><w:t>', $okanaganSyllabus->course_format));
                        $i++;
                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('courseFormat'.$i, '');
                        }
                    }

                } else {
                    $templateProcessor->cloneBlock('NocourseFormat', 0);
                }

                //Course Overview Okanagan

                if ($okanaganSyllabus->course_overview) {
                    $COArr = explode("\n", $okanaganSyllabus->course_overview);
                    $i = 0;
                    if ($ext == 'pdf') {
                        foreach ($COArr as $index => $courseOver) {
                            $templateProcessor->cloneBlock('NocourseOverview');
                            $templateProcessor->setValue('courseOverview'.$i, $courseOver.'</w:t><w:br/><w:t>');
                            $i++;
                        }

                        for ($i; $i <= 30; $i++) {
                            $templateProcessor->setValue('courseOverview'.$i, '');
                        }
                    } else {
                        $templateProcessor->cloneBlock('NocourseOverview');
                        $templateProcessor->setValue('courseOverview0', str_replace("\n", '</w:t><w:br/><w:t>', $okanaganSyllabus->course_overview));
                        $i++;
                        for ($i; $i <= 30; $i++) {
                            $templateProcessor->setValue('courseOverview'.$i, '');
                        }
                    }

                } else {
                    $templateProcessor->cloneBlock('NocourseOverview', 0);
                }

                /*
                // tell template processor to include learning activities if user completed the field(s)
                if($learningActivities = $syllabus->learning_activities){
                    $templateProcessor->cloneBlock('NoLearningActivities');
                    // split learning activities string on newline char
                    $learningActivitiesArr = explode("\n", $learningActivities);
                    // create a table for learning activities (workaround for no list option)
                    $learningActivitiesTable = new Table($tableStyle);
                    //$learningActivitiesTable->addRow();
                   // $learningActivitiesTable->addCell(10, $tableHeaderRowStyle);                    $learningActivitiesTable->addCell(null, $tableHeaderRowStyle)->addText('Learning Activity', $tableHeaderFontStyle);

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
                */

                if ($syllabus->learning_activities) {
                    $LearnActArr = explode("\n", $syllabus->learning_activities);
                    $i = 0;
                    if ($ext == 'pdf') {
                        foreach ($LearnActArr as $index => $learnAct) {
                            $templateProcessor->cloneBlock('NoLearningActivities');
                            $templateProcessor->setValue('learningActivities'.$i, $learnAct.'</w:t><w:br/><w:t>');
                            $i++;
                        }

                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('learningActivities'.$i, '');
                        }
                    } else {
                        $templateProcessor->cloneBlock('NoLearningActivities');
                        $templateProcessor->setValue('learningActivities0', str_replace("\n", '</w:t><w:br/><w:t>', $syllabus->learning_activities));
                        $i++;
                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('learningActivities'.$i, '');
                        }
                    }

                } else {
                    $templateProcessor->cloneBlock('NoLearningActivities', 0);
                }

                
                // tell template processor to include prerequisites if user completed the field(s)
                if ($syllabus->prerequisites) {
                    $prerequisitesArr = explode("\n", $syllabus->prerequisites);
                    $i = 0;
                    if ($ext == 'pdf') {
                        foreach ($prerequisitesArr as $index => $prereqs) {
                            $templateProcessor->cloneBlock('NoPrerequisites');
                            $templateProcessor->setValue('prerequisites'.$i, $prereqs.'</w:t><w:br/><w:t>');
                            $i++;
                        }

                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('prerequisites'.$i, '');
                        }
                    } else {
                        $templateProcessor->cloneBlock('NoPrerequisites');
                        $templateProcessor->setValue('prerequisites0', str_replace("\n", '</w:t><w:br/><w:t>', $syllabus->prerequisites));
                        $i++;
                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('prerequisites'.$i, '');
                        }
                    }

                } else {
                    $templateProcessor->cloneBlock('NoPrerequisites', 0);
                }
                // tell template processor to include corequisites if user completed the field(s)
                if ($syllabus->corequisites) {
                    $corequisitesArr = explode("\n", $syllabus->corequisites);
                    $i = 0;
                    if ($ext == 'pdf') {
                        foreach ($corequisitesArr as $index => $coreqs) {
                            $templateProcessor->cloneBlock('NoCorequisites');
                            $templateProcessor->setValue('corequisites'.$i, $coreqs.'</w:t><w:br/><w:t>');
                            $i++;
                        }

                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('corequisites'.$i, '');
                        }
                    } else {
                        $templateProcessor->cloneBlock('NoCorequisites');
                        $templateProcessor->setValue('corequisites0', str_replace("\n", '</w:t><w:br/><w:t>', $syllabus->corequisites));
                        $i++;
                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('corequisites'.$i, '');
                        }
                    }

                } else {
                    $templateProcessor->cloneBlock('NoCorequisites', 0);
                }
                // tell template processor to include other course staff if user completed the field(s)
                if ($syllabus->other_instructional_staff) {
                    $otherCourseStaffArr = explode("\n", $syllabus->other_instructional_staff);
                    $i = 0;
                    if ($ext == 'pdf') {
                        foreach ($otherCourseStaffArr as $index => $otherCourseStaff) {
                            $templateProcessor->cloneBlock('NoOtherInstructionalStaff');
                            $templateProcessor->setValue('otherInstructionalStaff'.$i, $otherCourseStaff.'</w:t><w:br/><w:t>');
                            $i++;
                        }

                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('otherInstructionalStaff'.$i, '');
                        }
                    } else {
                        $templateProcessor->cloneBlock('NoOtherInstructionalStaff');
                        $templateProcessor->setValue('otherInstructionalStaff0', str_replace("\n", '</w:t><w:br/><w:t>', $syllabus->other_instructional_staff));
                        $i++;
                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('otherInstructionalStaff'.$i, '');
                        }
                    }

                } else {
                    $templateProcessor->cloneBlock('NoOtherInstructionalStaff', 0);
                }
                // tell template processor to include course location if user completed the field(s)
                if ($courseLocation = $syllabus->course_location) {
                    $templateProcessor->cloneBlock('NoCourseLocation');
                    $templateProcessor->setValue('courseLocation', $courseLocation);
                } else {
                    $templateProcessor->cloneBlock('NoCourseLocation', 0);
                }

                // tell template processor to include class hours if user completed the field(s)
                if ($classStartTime = $syllabus->class_start_time && $classEndTime = $syllabus->class_end_time) {
                    $templateProcessor->cloneBlock('NoClassHours');
                    $templateProcessor->setValues(['classStartTime' => $syllabus->class_start_time, 'classEndTime' => $syllabus->class_end_time]);
                } else {
                    $templateProcessor->cloneBlock('NoClassHours', 0);
                }
                // tell template processor to include course schedule if user completed the field(s)
                if ($syllabus->class_meeting_days) {
                    $templateProcessor->cloneBlock('NoCourseDays');
                    $templateProcessor->setValue('schedule', $syllabus->class_meeting_days);
                } else {
                    $templateProcessor->cloneBlock('NoCourseDays', 0);
                }
                // tell template processor to include office hours if user completed the field(s)
                if ($syllabus->office_hours) {
                    $templateProcessor->cloneBlock('NoOfficeHours');
                    $templateProcessor->setValue('officeHour', $syllabus->office_hours);
                } else {
                    $templateProcessor->cloneBlock('NoOfficeHours', 0);
                }

                switch ($syllabus->course_term) {
                    case 'W1':
                        $templateProcessor->setValue('season', 'Winter');
                        $templateProcessor->setValue('term', 'Term 1');
                        break;
                    case 'W2':
                        $templateProcessor->setValue('season', 'Winter');
                        $templateProcessor->setValue('term', 'Term 2');
                        break;
                    case 'S1':
                        $templateProcessor->setValue('season', 'Summer');
                        $templateProcessor->setValue('term', 'Term 1');
                        break;
                    case 'S2':
                        $templateProcessor->setValue('season', 'Summer');
                        $templateProcessor->setValue('term', 'Term 2');
                        break;
                    default:
                        $templateProcessor->setValue('term', $syllabus->course_term);
                        $templateProcessor->setValue('season', '');
                }
                /*
                if($learningOutcome = $syllabus->learning_outcomes){
                    $templateProcessor->cloneBlock('NolearningOutcomes');
                    // split learning outcomes string on newline char
                    $learningOutcomes = explode("\n", $learningOutcome);
                    // create a table for learning outcomes (workaround for no list option)
                    $learningOutcomesTable = new Table($tableStyle);
                    //$learningOutcomesTable->addRow();
                    //$learningOutcomesTable->addCell(10, $tableHeaderRowStyle);                    $learningOutcomesTable->addCell(null, $tableHeaderRowStyle)->addText('Learning Outcome', $tableHeaderFontStyle);

                    // add a new row and cell to table for each learning outcome
                    foreach($learningOutcomes as $index => $outcome) {
                        $learningOutcomesTable->addRow();
                        $learningOutcomesTable->addCell()->addText(strval($index + 1));
                        $learningOutcomesTable->addCell()->addText($outcome);
                    }
                    // add learning outcome table to word doc
                    $templateProcessor->setComplexBlock('learningOutcomes',$learningOutcomesTable);
                }else{
                    $templateProcessor->cloneBlock('NolearningOutcomes',0);
                }
                */
                if ($syllabus->learning_outcomes) {
                    $LOutArr = explode("\n", $syllabus->learning_outcomes);
                    $i = 0;
                    if ($ext == 'pdf') {
                        foreach ($LOutArr as $index => $courseLOut) {
                            $templateProcessor->cloneBlock('NolearningOutcomes');
                            $templateProcessor->setValue('learningOutcomes'.$i, $courseLOut.'</w:t><w:br/><w:t>');
                            $i++;
                        }

                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('learningOutcomes'.$i, '');
                        }
                    } else {
                        $templateProcessor->cloneBlock('NolearningOutcomes');
                        $templateProcessor->setValue('learningOutcomes0', str_replace("\n", '</w:t><w:br/><w:t>', $syllabus->learning_outcomes));
                        $i++;
                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('learningOutcomes'.$i, '');
                        }
                    }

                } else {
                    $templateProcessor->cloneBlock('NolearningOutcomes', 0);
                }

                if ($syllabus->learning_assessments) {
                    $LAssArr = explode("\n", $syllabus->learning_assessments);
                    $i = 0;
                    if ($ext == 'pdf') {
                        foreach ($LAssArr as $index => $courseLAss) {
                            $templateProcessor->cloneBlock('NolearningAssessments');
                            $templateProcessor->setValue('learningAssessments'.$i, $courseLAss.'</w:t><w:br/><w:t>');
                            $i++;
                        }

                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('learningAssessments'.$i, '');
                        }
                    } else {
                        $templateProcessor->cloneBlock('NolearningAssessments');
                        $templateProcessor->setValue('learningAssessments0', str_replace("\n", '</w:t><w:br/><w:t>', $syllabus->learning_assessments));
                        $i++;
                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('learningAssessments'.$i, '');
                        }
                    }

                } else {
                    $templateProcessor->cloneBlock('NolearningAssessments', 0);
                }

                //Learning Resources

                if ($syllabus->learning_resources) {
                    $LRArr = explode("\n", $syllabus->learning_resources);
                    $i = 0;
                    if ($ext == 'pdf') {
                        foreach ($LRArr as $index => $courseLR) {
                            $templateProcessor->cloneBlock('NocourseLearningResources');
                            $templateProcessor->setValue('courseLearningResources'.$i, $courseLR.'</w:t><w:br/><w:t>');
                            $i++;
                        }

                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('courseLearningResources'.$i, '');
                        }
                    } else {
                        $templateProcessor->cloneBlock('NocourseLearningResources');
                        $templateProcessor->setValue('courseLearningResources0', str_replace("\n", '</w:t><w:br/><w:t>', $syllabus->learning_resources));
                        $i++;
                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('courseLearningResources'.$i, '');
                        }
                    }

                } else {
                    $templateProcessor->cloneBlock('NocourseLearningResources', 0);
                }

                //Learning Materials

                if ($learningMaterials = $syllabus->learning_materials) {
                    $LMArr = explode("\n", $learningMaterials);
                    $i = 0;
                    if ($ext == 'pdf') {
                        foreach ($LMArr as $index => $courseLM) {
                            $templateProcessor->cloneBlock('NoLearningMaterials');
                            $templateProcessor->setValue('learningMaterials'.$i, $courseLM.'</w:t><w:br/><w:t>');
                            $i++;
                        }

                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('learningMaterials'.$i, '');
                        }
                    } else {
                        $templateProcessor->cloneBlock('NoLearningMaterials');
                        $templateProcessor->setValue('learningMaterials0', str_replace("\n", '</w:t><w:br/><w:t>', $learningMaterials));
                        $i++;
                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('learningMaterials'.$i, '');
                        }
                    }

                } else {
                    $templateProcessor->cloneBlock('NoLearningMaterials', 0);
                }

                $allOkanaganSyllabusResources = OkanaganSyllabusResource::all();
                $selectedOkanaganSyllabusResourceIds = SyllabusResourceOkanagan::where('syllabus_id', $syllabus->id)->pluck('o_syllabus_resource_id')->toArray();

                foreach ($allOkanaganSyllabusResources as $resource) {
                    if (in_array($resource->id, $selectedOkanaganSyllabusResourceIds)) {
                        $templateProcessor->cloneBlock($resource->id_name);
                        $templateProcessor->setValue($resource->id_name.'-title', $resource->title);
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
                $templateProcessor->setValues(['courseCredit' => $courseCredit]);

                //removed if statement in attempt to solve bug
                $templateProcessor->setValue('officeLocation', $vancouverSyllabus->office_location);

                //Vancouver Course Description

                if ($courseDescription = $vancouverSyllabus->course_description) {
                    $CDVArr = explode("\n", $courseDescription);
                    $i = 0;
                    if ($ext == 'pdf') {
                        foreach ($CDVArr as $index => $courseCDV) {
                            $templateProcessor->cloneBlock('NoCourseDescription');
                            $templateProcessor->setValue('courseDescription'.$i, $courseCDV.'</w:t><w:br/><w:t>');
                            $i++;
                        }

                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('courseDescription'.$i, '');
                        }
                    } else {
                        $templateProcessor->cloneBlock('NoCourseDescription');
                        $templateProcessor->setValue('courseDescription0', str_replace("\n", '</w:t><w:br/><w:t>', $courseDescription));
                        $i++;
                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('courseDescription'.$i, '');
                        }
                    }

                } else {
                    $templateProcessor->cloneBlock('NoCourseDescription', 0);
                }

                if ($contacts = $vancouverSyllabus->course_contacts) {
                    $templateProcessor->cloneBlock('NoContacts', 0);
                    // split contacts string on newline char
                    $contactsArr = explode("\n", $contacts);
                    // create a table for contacts (workaround for no list option)
                    $contactsTable = new Table($tableStyle);
                    //$contactsTable->addRow();
                    //$contactsTable->addCell(10, $tableHeaderRowStyle);                    $contactsTable->addCell(null, $tableHeaderRowStyle)->addText('Contact', $tableHeaderFontStyle);
                    // add a new row and cell to table for each contact
                    foreach ($contactsArr as $index => $contact) {
                        $contactsTable->addRow();
                        $contactsTable->addCell()->addText(strval($index + 1));
                        $contactsTable->addCell()->addText($contact);
                    }
                    // add contacts table to word doc
                    $templateProcessor->setComplexBlock('contacts', $contactsTable);

                } else {
                    $templateProcessor->cloneBlock('NoContacts');
                    $templateProcessor->setValue('contacts', '');
                }

                if ($coursePrereqs = $vancouverSyllabus->course_prereqs) {
                    $templateProcessor->cloneBlock('NoPrerequisites', 0);
                    // split course prereqs string on newline char
                    $coursePrereqsArr = explode("\n", $coursePrereqs);
                    // create a table for course prereqs (workaround for no list option)
                    $coursePrereqsTable = new Table($tableStyle);
                    // $coursePrereqsTable->addRow();
                    // $coursePrereqsTable->addCell(10, $tableHeaderRowStyle);                    $coursePrereqsTable->addCell(null, $tableHeaderRowStyle)->addText('Course Prerequisites', $tableHeaderFontStyle);
                    // add a new row and cell to table for each prereq
                    foreach ($coursePrereqsArr as $index => $prereq) {
                        $coursePrereqsTable->addRow();
                        $coursePrereqsTable->addCell()->addText(strval($index + 1));
                        $coursePrereqsTable->addCell()->addText($prereq);
                    }
                    // add course prereqs table to word doc
                    $templateProcessor->setComplexBlock('prerequisites', $coursePrereqsTable);
                } else {
                    $templateProcessor->cloneBlock('NoPrerequisites');
                    $templateProcessor->setValue('prerequisites', '');
                }

                if ($courseCoreqs = $vancouverSyllabus->course_coreqs) {
                    $templateProcessor->cloneBlock('NoCorequisites', 0);
                    // split course coreqs string on newline char
                    $courseCoreqsArr = explode("\n", $courseCoreqs);
                    // create a table for course coreqs (workaround for no list option)
                    $courseCoreqsTable = new Table($tableStyle);
                    //$courseCoreqsTable->addRow();
                    //$courseCoreqsTable->addCell(10, $tableHeaderRowStyle);                    $courseCoreqsTable->addCell(null, $tableHeaderRowStyle)->addText('Course Corequisites', $tableHeaderFontStyle);
                    // add a new row and cell to table for each coreq
                    foreach ($courseCoreqsArr as $index => $coreq) {
                        $courseCoreqsTable->addRow();
                        $courseCoreqsTable->addCell()->addText(strval($index + 1));
                        $courseCoreqsTable->addCell()->addText($coreq);
                    }
                    // add course coreqs table to word doc
                    $templateProcessor->setComplexBlock('corequisites', $courseCoreqsTable);
                } else {
                    $templateProcessor->cloneBlock('NoCorequisites');
                    $templateProcessor->setValue('corequisites', '');
                }

                //Course Instructor Biographical Statement Vancouver

                if ($courseInstructorBio = $vancouverSyllabus->instructor_bio) {
                    $CIBArr = explode("\n", $courseInstructorBio);
                    $i = 0;
                    if ($ext == 'pdf') {
                        foreach ($CIBArr as $index => $courseCIB) {
                            $templateProcessor->cloneBlock('NoInstructorBio');
                            $templateProcessor->setValue('instructorBio'.$i, $courseCIB.'</w:t><w:br/><w:t>');
                            $i++;
                        }

                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('instructorBio'.$i, '');
                        }
                    } else {
                        $templateProcessor->cloneBlock('NoInstructorBio');
                        $templateProcessor->setValue('instructorBio0', str_replace("\n", '</w:t><w:br/><w:t>', $courseInstructorBio));
                        $i++;
                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('instructorBio'.$i, '');
                        }
                    }

                } else {
                    $templateProcessor->cloneBlock('NoInstructorBio', 0);
                }

                //Course Structure Vancouver

                if ($courseStructure = $vancouverSyllabus->course_structure) {
                    $CStructArr = explode("\n", $courseStructure);
                    $i = 0;
                    if ($ext == 'pdf') {
                        foreach ($CStructArr as $index => $courseStruct) {
                            $templateProcessor->cloneBlock('NoCourseStructureDesc', 0);
                            $templateProcessor->cloneBlock('NoCourseStructure');
                            $templateProcessor->setValue('courseStructure'.$i, $courseStruct.'</w:t><w:br/><w:t>');
                            $i++;
                        }

                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('courseStructure'.$i, '');
                        }
                    } else {
                        $templateProcessor->cloneBlock('NoCourseStructureDesc', 0);
                        $templateProcessor->cloneBlock('NoCourseStructure');
                        $templateProcessor->setValue('courseStructure0', str_replace("\n", '</w:t><w:br/><w:t>', $courseStructure));
                        $i++;
                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('courseStructure'.$i, '');
                        }
                    }

                } else {
                    $templateProcessor->cloneBlock('NoCourseStructureDesc');
                    $templateProcessor->cloneBlock('NoCourseStructure', 0);
                }

                if ($courseSchedule = $vancouverSyllabus->course_schedule) {
                    $templateProcessor->setValue('courseSchedule', $courseSchedule);
                } else {
                    $templateProcessor->setValue('courseSchedule', '');
                }

                if ($learningActivities = $syllabus->learning_activities) {
                    $LearnActArr = explode("\n", $learningActivities);
                    $i = 0;
                    if ($ext == 'pdf') {
                        foreach ($LearnActArr as $index => $learnAct) {
                            $templateProcessor->cloneBlock('NoLearningActivities');
                            $templateProcessor->cloneBlock('NoLearningActivitiesDesc', 0);
                            $templateProcessor->setValue('learningActivities'.$i, $learnAct.'</w:t><w:br/><w:t>');
                            $i++;
                        }

                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('learningActivities'.$i, '');
                        }
                    } else {
                        $templateProcessor->cloneBlock('NoLearningActivities');
                        $templateProcessor->cloneBlock('NoLearningActivitiesDesc', 0);
                        $templateProcessor->setValue('learningActivities0', str_replace("\n", '</w:t><w:br/><w:t>', $learningActivities));
                        $i++;
                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('learningActivities'.$i, '');
                        }
                    }

                } else {
                    $templateProcessor->cloneBlock('NoLearningActivities', 0);
                    $templateProcessor->cloneBlock('NoLearningActivitiesDesc');
                }

                // tell template processor to include other course staff if user completed the field(s)
                if ($syllabus->other_instructional_staff) {
                    $otherCourseStaffArr = explode("\n", $syllabus->other_instructional_staff);
                    $i = 0;
                    if ($ext == 'pdf') {
                        foreach ($otherCourseStaffArr as $index => $otherCourseStaff) {
                            $templateProcessor->cloneBlock('NoOtherInstructionalStaff');
                            $templateProcessor->setValue('otherInstructionalStaff'.$i, $otherCourseStaff.'</w:t><w:br/><w:t>');
                            $i++;
                        }

                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('otherInstructionalStaff'.$i, '');
                        }
                    } else {
                        $templateProcessor->cloneBlock('NoOtherInstructionalStaff');
                        $templateProcessor->setValue('otherInstructionalStaff0', str_replace("\n", '</w:t><w:br/><w:t>', $syllabus->other_instructional_staff));
                        $i++;
                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('otherInstructionalStaff'.$i, '');
                        }
                    }

                } else {
                    $templateProcessor->cloneBlock('NoOtherInstructionalStaff', 0);
                }
                // tell template processor to include course location if user completed the field(s)
                if ($courseLocation = $syllabus->course_location) {
                    $templateProcessor->cloneBlock('NoCourseLocation');
                    $templateProcessor->setValue('courseLocation', $courseLocation);
                } else {
                    $templateProcessor->cloneBlock('NoCourseLocation', 0);
                }

                // tell template processor to include class hours if user completed the field(s)
                if ($classStartTime = $syllabus->class_start_time && $classEndTime = $syllabus->class_end_time) {
                    $templateProcessor->cloneBlock('NoClassHours');
                    $templateProcessor->setValues(['classStartTime' => $syllabus->class_start_time, 'classEndTime' => $syllabus->class_end_time]);
                } else {
                    $templateProcessor->cloneBlock('NoClassHours', 0);
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
                    $templateProcessor->setValue('officeHour', $officeHour);
                } else {
                    $templateProcessor->cloneBlock('NoOfficeHours', 0);
                }

                switch ($syllabus->course_term) {
                    case 'W1':
                        $templateProcessor->setValue('season', 'Winter');
                        $templateProcessor->setValue('term', 'Term 1');
                        break;
                    case 'W2':
                        $templateProcessor->setValue('season', 'Winter');
                        $templateProcessor->setValue('term', 'Term 2');
                        break;
                    case 'S1':
                        $templateProcessor->setValue('season', 'Summer');
                        $templateProcessor->setValue('term', 'Term 1');
                        break;
                    case 'S2':
                        $templateProcessor->setValue('season', 'Summer');
                        $templateProcessor->setValue('term', 'Term 2');
                        break;
                    default:
                        $templateProcessor->setValue('term', $syllabus->course_term);
                        $templateProcessor->setValue('season', '');
                }

                if ($learningOutcome = $syllabus->learning_outcomes) {
                    $LOutArr = explode("\n", $learningOutcome);
                    $i = 0;
                    if ($ext == 'pdf') {
                        foreach ($LOutArr as $index => $courseLOut) {
                            $templateProcessor->cloneBlock('NolearningOutcomesDesc', 0);
                            $templateProcessor->cloneBlock('NolearningOutcomes');
                            $templateProcessor->setValue('learningOutcomes'.$i, $courseLOut.'</w:t><w:br/><w:t>');
                            $i++;
                        }

                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('learningOutcomes'.$i, '');
                        }
                    } else {
                        $templateProcessor->cloneBlock('NolearningOutcomesDesc', 0);
                        $templateProcessor->cloneBlock('NolearningOutcomes');
                        $templateProcessor->setValue('learningOutcomes0', str_replace("\n", '</w:t><w:br/><w:t>', $learningOutcome));
                        $i++;
                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('learningOutcomes'.$i, '');
                        }
                    }

                } else {
                    $templateProcessor->cloneBlock('NolearningOutcomesDesc');
                    $templateProcessor->cloneBlock('NolearningOutcomes', 0);
                }

                if ($learningAssessments = $syllabus->learning_assessments) {
                    $LAssArr = explode("\n", $learningAssessments);
                    $i = 0;
                    if ($ext == 'pdf') {
                        foreach ($LAssArr as $index => $courseLAss) {
                            $templateProcessor->cloneBlock('NolearningAssessmentsDesc', 0);
                            $templateProcessor->cloneBlock('NolearningAssessments');
                            $templateProcessor->setValue('learningAssessments'.$i, $courseLAss.'</w:t><w:br/><w:t>');
                            $i++;
                        }

                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('learningAssessments'.$i, '');
                        }
                    } else {
                        $templateProcessor->cloneBlock('NolearningAssessmentsDesc', 0);
                        $templateProcessor->cloneBlock('NolearningAssessments');
                        $templateProcessor->setValue('learningAssessments0', str_replace("\n", '</w:t><w:br/><w:t>', $learningAssessments));
                        $i++;
                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('learningAssessments'.$i, '');
                        }
                    }

                } else {
                    $templateProcessor->cloneBlock('NolearningAssessmentsDesc');
                    $templateProcessor->cloneBlock('NolearningAssessments', 0);
                }

                //Vancouver Course Learning Resources
                if ($learningResources = $syllabus->learning_resources) {
                    $LRVArr = explode("\n", $learningResources);
                    $i = 0;
                    if ($ext == 'pdf') {
                        foreach ($LRVArr as $index => $courseLRV) {
                            $templateProcessor->cloneBlock('NoCourseLearningResources');
                            $templateProcessor->setValue('courseLearningResources'.$i, $courseLRV.'</w:t><w:br/><w:t>');
                            $i++;
                        }

                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('courseLearningResources'.$i, '');
                        }
                    } else {
                        $templateProcessor->cloneBlock('NoCourseLearningResources');
                        $templateProcessor->setValue('courseLearningResources0', str_replace("\n", '</w:t><w:br/><w:t>', $learningResources));
                        $i++;
                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('courseLearningResources'.$i, '');
                        }
                    }

                } else {
                    $templateProcessor->cloneBlock('NoCourseLearningResources', 0);
                }

                //Learning Materials

                if ($learningMaterials = $syllabus->learning_materials) {
                    $LMVArr = explode("\n", $learningMaterials);
                    $i = 0;
                    if ($ext == 'pdf') {
                        foreach ($LMVArr as $index => $courseLMV) {
                            $templateProcessor->cloneBlock('NoLearningMaterials');
                            $templateProcessor->cloneBlock('NoLearningMaterialsDesc', 0);
                            $templateProcessor->setValue('learningMaterials'.$i, $courseLMV.'</w:t><w:br/><w:t>');
                            $i++;
                        }

                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('learningMaterials'.$i, '');
                        }
                    } else {
                        $templateProcessor->cloneBlock('NoLearningMaterials');
                        $templateProcessor->cloneBlock('NoLearningMaterialsDesc', 0);
                        $templateProcessor->setValue('learningMaterials0', str_replace("\n", '</w:t><w:br/><w:t>', $learningMaterials));
                        $i++;
                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('learningMaterials'.$i, '');
                        }
                    }

                } else {
                    $templateProcessor->cloneBlock('NoLearningMaterials', 0);
                    $templateProcessor->cloneBlock('NoLearningMaterialsDesc');
                }

                //Learning Analytics
                //Currently not showing, $learningAnalytics=null every time

                if ($learningAnalytics = $vancouverSyllabus->learning_analytics) {

                    $LAVArr = explode("\n", $learningAnalytics);
                    $i = 0;
                    if ($ext == 'pdf') {
                        foreach ($LAVArr as $index => $courseLAV) {
                            $templateProcessor->cloneBlock('NoLearningAnalytics');
                            $templateProcessor->setValue('learningAnalytics'.$i, $courseLAV.'</w:t><w:br/><w:t>');
                            $i++;
                        }

                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('learningAnalytics'.$i, '');
                        }
                    } else {
                        $templateProcessor->cloneBlock('NoLearningAnalytics');
                        $templateProcessor->setValue('learningAnalytics0', str_replace("\n", '</w:t><w:br/><w:t>', $learningAnalytics));
                        $i++;
                        for ($i; $i <= 20; $i++) {
                            $templateProcessor->setValue('learningAnalytics'.$i, '');
                        }
                    }

                } else {
                    $templateProcessor->cloneBlock('NoLearningAnalytics', 0);
                }

                $allVancouverSyllabusResources = VancouverSyllabusResource::all();
                $selectedVancouverSyllabusResourceIds = SyllabusResourceVancouver::where('syllabus_id', $syllabus->id)->pluck('v_syllabus_resource_id')->toArray();

                foreach ($allVancouverSyllabusResources as $resource) {
                    if (in_array($resource->id, $selectedVancouverSyllabusResourceIds)) {
                        $templateProcessor->cloneBlock($resource->id_name);
                        $templateProcessor->setValue($resource->id_name.'-title', strtoupper($resource->title));
                    // $templateProcessor->setValue($resource->id_name . '-description', $resource->description);
                    } else {
                        $templateProcessor->cloneBlock($resource->id_name, 0);
                    }
                }

                break;
        }

        //include Custom Resource
        if (! empty($syllabus->custom_resource) && ! empty($syllabus->custom_resource_title)) {
            $CRArr = explode("\n", $syllabus->custom_resource);
            $i = 0;
            if ($ext == 'pdf') {
                foreach ($CRArr as $index => $courseCR) {
                    $templateProcessor->cloneBlock('NoCustomResource');
                    $templateProcessor->setValue('custom_resource'.$i, $courseCR.'</w:t><w:br/><w:t>');
                    $i++;
                }

                for ($i; $i <= 20; $i++) {
                    $templateProcessor->setValue('custom_resource'.$i, '');
                }

            } else {
                $templateProcessor->cloneBlock('NoCustomResource');
                $templateProcessor->setValue('custom_resource0', str_replace("\n", '</w:t><w:br/><w:t>', $syllabus->custom_resource));
                $i++;
                for ($i; $i <= 20; $i++) {
                    $templateProcessor->setValue('custom_resource'.$i, '');
                }
            }
            $templateProcessor->setValue('custom_resource_title', $syllabus->custom_resource_title);

        } else {
            $templateProcessor->cloneBlock('NoCustomResource', 0);
        }

        //include creative commons or copyright
        $creativeCommons = $syllabus->cc_license;
        if ($syllabus->campus == 'O' && ! empty($syllabus->cc_license)) {
            $templateProcessor->cloneBlock('NoCreativeCommons');
            $templateProcessor->setValue('creativeCommons', $creativeCommons);
            $templateProcessor->setValue('name', $syllabus->course_instructor);
        } else {
            $templateProcessor->cloneBlock('NoCreativeCommons', 0);
        }

        if ($syllabus->copyright) {
            $templateProcessor->cloneBlock('NoCopyright');
        } else {
            $templateProcessor->cloneBlock('NoCopyright', 0);
        }

        //Land Acknowledgement

        if ($syllabus->land_acknow) {
            $templateProcessor->cloneBlock('NoLand');
        } else {
            $templateProcessor->cloneBlock('NoLand', 0);
        }

        $courseName = ($syllabus->course_code).' '.($syllabus->course_num);
        if ($syllabus->cross_listed_code != null) {
            $courseName = $courseName.'/'.($syllabus->cross_listed_code).' '.($syllabus->cross_listed_num);
        }

        // add required form fields common to both campuses to template
        $templateProcessor->setValues(['courseTitle' => $syllabus->course_title, 'courseCode' => $syllabus->course_code, 'courseNumber' => $syllabus->course_num, 'courseYear' => $syllabus->course_year, 'courseCodeCL' => $syllabus->cross_listed_code, 'courseNumberCL' => $syllabus->cross_listed_num, 'courseName' => $courseName]);

        $syllabusInstructors = SyllabusInstructor::where('syllabus_id', $syllabus->id)->get();
        $templateProcessor->setValue('courseInstructor', $syllabusInstructors->implode('name', ', '));
        $templateProcessor->setValue('courseInstructorEmail', $syllabusInstructors->implode('email', ', '));

        switch ($syllabus->delivery_modality) {
            case 'M' :
                $templateProcessor->setValue('deliveryModality', 'Multi-Access');
                break;
            case 'I' :
                $templateProcessor->setValue('deliveryModality', 'In-Person');
                break;
            case 'B' :
                $templateProcessor->setValue('deliveryModality', 'Hybrid');
                break;
            default:
                $templateProcessor->setValue('deliveryModality', 'Online');
        }

        // date the syllabus
        $templateProcessor->setValue('dateGenerated', date('d, M Y'));

        if ($faculty = $syllabus->faculty) {
            $templateProcessor->cloneBlock('NoFaculty');
            $templateProcessor->setValue('faculty', $faculty);
        } else {
            $templateProcessor->cloneBlock('NoFaculty', 0);
        }

        if ($courseSection = $syllabus->course_section) {
            $templateProcessor->cloneBlock('NoCourseSection');
            $templateProcessor->setValue('courseSection', $courseSection);
        } else {
            $templateProcessor->cloneBlock('NoCourseSection', 0);
        }

        if ($department = $syllabus->department) {
            $templateProcessor->cloneBlock('NoDepartment');
            $templateProcessor->setValue('department', $department);
        } else {
            $templateProcessor->cloneBlock('NoDepartment', 0);
        }

        //Late Policy

        if ($latePolicy = $syllabus->late_policy) {
            $LPArr = explode("\n", $latePolicy);
            $i = 0;
            if ($ext == 'pdf') {
                foreach ($LPArr as $index => $courseLP) {
                    $templateProcessor->cloneBlock('NolatePolicy');
                    $templateProcessor->setValue('latePolicy'.$i, $courseLP.'</w:t><w:br/><w:t>');
                    $i++;
                }

                for ($i; $i <= 20; $i++) {
                    $templateProcessor->setValue('latePolicy'.$i, '');
                }
            } else {
                $templateProcessor->cloneBlock('NolatePolicy');
                $templateProcessor->setValue('latePolicy0', str_replace("\n", '</w:t><w:br/><w:t>', $latePolicy));
                $i++;
                for ($i; $i <= 20; $i++) {
                    $templateProcessor->setValue('latePolicy'.$i, '');
                }
            }

        } else {
            $templateProcessor->cloneBlock('NolatePolicy', 0);
        }

        //Missing Exam Policy

        if ($missingExam = $syllabus->missed_exam_policy) {
            $MEArr = explode("\n", $missingExam);
            $i = 0;
            if ($ext == 'pdf') {
                foreach ($MEArr as $index => $courseME) {
                    $templateProcessor->cloneBlock('NoMissingExam');
                    $templateProcessor->setValue('missingExam'.$i, $courseME.'</w:t><w:br/><w:t>');
                    $i++;
                }

                for ($i; $i <= 20; $i++) {
                    $templateProcessor->setValue('missingExam'.$i, '');
                }
            } else {
                $templateProcessor->cloneBlock('NoMissingExam');
                $templateProcessor->setValue('missingExam0', str_replace("\n", '</w:t><w:br/><w:t>', $missingExam));
                $i++;
                for ($i; $i <= 20; $i++) {
                    $templateProcessor->setValue('missingExam'.$i, '');
                }
            }

        } else {
            $templateProcessor->cloneBlock('NoMissingExam', 0);
        }

        //Missing Activity Policy

        if ($missingActivity = $syllabus->missed_activity_policy) {
            $MAArr = explode("\n", $missingActivity);
            $i = 0;
            if ($ext == 'pdf') {
                foreach ($MAArr as $index => $courseMA) {
                    $templateProcessor->cloneBlock('NomissingActivity');
                    $templateProcessor->setValue('missingActivity'.$i, $courseMA.'</w:t><w:br/><w:t>');
                    $i++;
                }

                for ($i; $i <= 20; $i++) {
                    $templateProcessor->setValue('missingActivity'.$i, '');
                }
            } else {
                $templateProcessor->cloneBlock('NomissingActivity');
                $templateProcessor->setValue('missingActivity0', str_replace("\n", '</w:t><w:br/><w:t>', $missingActivity));
                $i++;
                for ($i; $i <= 20; $i++) {
                    $templateProcessor->setValue('missingActivity'.$i, '');
                }
            }

        } else {
            $templateProcessor->cloneBlock('NomissingActivity', 0);
        }

        //Passing Criteria

        if ($passingCriteria = $syllabus->passing_criteria) {
            $PCArr = explode("\n", $passingCriteria);
            $i = 0;
            if ($ext == 'pdf') {
                foreach ($PCArr as $index => $coursePC) {
                    $templateProcessor->cloneBlock('NopassingCriteria');
                    $templateProcessor->setValue('passingCriteria'.$i, $coursePC.'</w:t><w:br/><w:t>');
                    $i++;
                }

                for ($i; $i <= 20; $i++) {
                    $templateProcessor->setValue('passingCriteria'.$i, '');
                }
            } else {
                $templateProcessor->cloneBlock('NopassingCriteria');
                $templateProcessor->setValue('passingCriteria0', str_replace("\n", '</w:t><w:br/><w:t>', $passingCriteria));
                $i++;
                for ($i; $i <= 20; $i++) {
                    $templateProcessor->setValue('passingCriteria'.$i, '');
                }
            }

        } else {
            $templateProcessor->cloneBlock('NopassingCriteria', 0);
        }

        // add course schedule table to word document
        $courseScheduleTblColsCount = CourseSchedule::where('syllabus_id', $syllabus->id)->where('row', 0)->get()->count();
        $courseScheduleTbl['rows'] = CourseSchedule::where('syllabus_id', $syllabus->id)->get()->chunk($courseScheduleTblColsCount);
        if (count($courseScheduleTbl['rows']) > 0) {
            $templateProcessor->cloneBlock('NoTopicsSchedule', 0);
            $templateProcessor->cloneBlock('NoCourseScheduleTbl');
            $courseScheduleTable = new Table($tableStyle);
            // add a new row and cell to table for each learning activity

            foreach ($courseScheduleTbl['rows'] as $rowIndex => $row) {
                // add a row to the table
                $courseScheduleTable->addRow();
                if ($rowIndex == 0) {
                    foreach ($row as $headerIndex => $header) {
                        $heading = ($header->val) ? $header->val : '';
                        $courseScheduleTable->addCell(null, $tableHeaderRowStyle)->addText($heading, $tableHeaderFontStyle);
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
            $templateProcessor->cloneBlock('NoTopicsSchedule');
            $templateProcessor->cloneBlock('NoCourseScheduleTbl', 0);
            $templateProcessor->setValue('courseScheduleTbl', '');
        }
        //Outcome Maps
        if ($syllabus->course_id) {
            if ($syllabus->include_alignment) {
                $this->addAlignmentToWordDoc($syllabus->id, $templateProcessor, ['tableStyle' => $tableStyle, 'tableHeaderRowStyle' => $tableHeaderRowStyle, 'tableHeaderFontStyle' => $tableHeaderFontStyle]);
            } else {
                $templateProcessor->cloneBlock('NoCourseAlignmentTbl', 0);
            }

            $syllabusProgramIds = SyllabusProgram::where('syllabus_id', $syllabusId)->pluck('program_id')->toArray();
            if (count($syllabusProgramIds) > 0) {
                $this->addOutcomeMapsToWordDoc($syllabusProgramIds, $templateProcessor, $syllabus->course_id, ['tableStyle' => $tableStyle, 'tableHeaderRowStyle' => $tableHeaderRowStyle, 'tableHeaderFontStyle' => $tableHeaderFontStyle, 'secondaryTableHeaderRowStyle' => $secondaryTableHeaderRowStyle]);
            } else {
                $templateProcessor->cloneBlock('NoOutcomeMaps', 0);
            }
        } else {
            $templateProcessor->cloneBlock('NoCourseAlignmentTbl', 0);
            $templateProcessor->cloneBlock('NoOutcomeMaps', 0);
        }

        // set document name
        $fileName = 'syllabus';
        // word file ext
        $wordFileExt = '.docx';
        // save word document on server
        $templateProcessor->saveAs($fileName.$wordFileExt);

        if ($ext == 'pdf') {
            // pdf file ext
            $pdfFileExt = '.pdf';
            $pdfRendererPath = base_path(DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'dompdf'.DIRECTORY_SEPARATOR.'dompdf');
            Settings::setPdfRendererPath($pdfRendererPath);
            Settings::setPdfRendererName('DomPDF');
            // get path to word file
            $wordFilePath = config('app.env') == 'local' ? public_path($fileName.$wordFileExt) : base_path('html'.DIRECTORY_SEPARATOR.$fileName.$wordFileExt);
            // load word file
            $wordFileContent = IOFactory::load($wordFilePath);
            $pdfWriter = IOFactory::createWriter($wordFileContent, 'PDF');
            $pdfWriter->save($fileName.$pdfFileExt);
            // delete the word version
            unlink($fileName.$wordFileExt);

            // return pdf download response
            return response()->download($fileName.$pdfFileExt)->deleteFileAfterSend(true);

        }

        return response()->download($fileName.$wordFileExt)->deleteFileAfterSend(true);
    }

    private function addAlignmentToWordDoc($syllabusId, $docTemplate, $styles)
    {
        $docTemplate->cloneBlock('NoCourseAlignmentTbl');
        $syllabus = Syllabus::find($syllabusId);
        $importCourse = Course::find($syllabus->course_id);
        $courseAlignmentTable = new Table($styles['tableStyle']);
        // add a header row to the table
        $courseAlignmentTable->addRow();
        // add header cells
        $courseAlignmentTable->addCell(null, $styles['tableHeaderRowStyle'])->addText('Course Learning Outcome', $styles['tableHeaderFontStyle']);
        $courseAlignmentTable->addCell(null, $styles['tableHeaderRowStyle'])->addText('Student Assessment Method', $styles['tableHeaderFontStyle']);
        $courseAlignmentTable->addCell(null, $styles['tableHeaderRowStyle'])->addText('Teaching and Learning Activity', $styles['tableHeaderFontStyle']);
        // add a new row and cell to table for each learning outcome and its alignment
        foreach ($importCourse->learningOutcomes as $rowIndex => $clo) {
            $courseAlignmentTable->addRow();
            isset($clo->clo_shortphrase) ? $shortphrase = $clo->clo_shortphrase.': ' : $shortphrase = '';
            $courseAlignmentTable->addCell()->addText($shortphrase.$clo->l_outcome);
            $courseAlignmentTable->addCell()->addText($clo->assessmentMethods->implode('a_method', ', '));
            $courseAlignmentTable->addCell()->addText($clo->learningActivities->implode('l_activity', ', '));
        }
        // add course schedule table to word doc
        $docTemplate->setComplexBlock('courseAlignmentTbl', $courseAlignmentTable);
    }

    private function addOutcomeMapsToWordDoc($syllabusProgramIds, $docTemplate, $courseId, $styles)
    {
        $docTemplate->cloneBlock('NoOutcomeMaps');
        $course = Course::find($courseId);
        $outcomeMaps = $this->getOutcomeMaps($syllabusProgramIds, $course->course_id);
        foreach (array_values($outcomeMaps) as $index => $outcomeMap) {
            // limit of 5 outcome maps
            if ($index > 4) {
                break;
            }
            $docTemplate->setValue('programtitle-'.strval($index), strtoupper($outcomeMap['program']->program));
            $this->addMappingScaleTblToWordDoc($outcomeMap['program']->mappingScaleLevels, $docTemplate, $index, $styles);
            if (isset($outcomeMap['outcomeMap'])) {
                $this->addOutcomeMapTblToWordDoc($outcomeMap['program'], $course->learningOutcomes, $outcomeMap['outcomeMap'], $docTemplate, $index, $styles);
            } else {
                $docTemplate->setValue('outcomeMap-'.strval($index), '');
            }
        }
        // remove remaining template tags in word doc
        while ($index < 5) {
            $docTemplate->setValue('programtitle-'.strval($index), '');
            $docTemplate->setValue('mappingScale-'.strval($index), '');
            $docTemplate->setValue('outcomeMap-'.strval($index), '');
            $index++;
        }
    }

    private function addMappingScaleTblToWordDoc($mappingScales, $docTemplate, $index, $styles)
    {
        $mappingScalesTbl = new Table($styles['tableStyle']);
        // add a header row to the table
        $mappingScalesTbl->addRow();
        // add header cells
        $mappingScaleheaderCell = $mappingScalesTbl->addCell(null, $styles['tableHeaderRowStyle']);
        $mappingScaleheaderCell->getStyle()->setGridSpan(3);
        $mappingScaleheaderCell->addText('Mapping Scale', $styles['tableHeaderFontStyle']);

        // add a new row and cell to table for each learning outcome and its alignment
        foreach ($mappingScales as $mappingScaleLevel) {
            $mappingScalesTbl->addRow();
            $mappingScalesTbl->addCell(null, ['bgColor' => substr($mappingScaleLevel->colour, 1)]);
            $mappingScalesTbl->addCell()->addText($mappingScaleLevel->title.' ('.$mappingScaleLevel->abbreviation.')');
            $mappingScalesTbl->addCell()->addText($mappingScaleLevel->description);
            ['bgColor' => 'c6e0f5', 'borderBottomColor' => '000000'];
        }
        $docTemplate->setComplexBlock('mappingScale-'.strval($index), $mappingScalesTbl);
    }

    private function addOutcomeMapTblToWordDoc($program, $clos, $outcomeMap, $docTemplate, $index, $styles)
    {
        $outcomeMapTbl = new Table($styles['tableStyle']);
        // add a header row to the table
        $outcomeMapTbl->addRow();
        // add header cells
        $outcomeMapTbl->addCell(null, $styles['tableHeaderRowStyle'])->addText('CLO', $styles['tableHeaderFontStyle']);
        $plosHeaderCell = $outcomeMapTbl->addCell(null, $styles['tableHeaderRowStyle']);
        $plosHeaderCell->getStyle()->setGridSpan($program->programLearningOutcomes->count());
        $plosHeaderCell->addText('Program Learning Outcome', $styles['tableHeaderFontStyle']);

        $outcomeMapTbl->addRow();
        $outcomeMapTbl->addCell();
        foreach ($program->ploCategories as $category) {
            if ($category->plos->count() > 0) {
                $categoryHeaderCell = $outcomeMapTbl->addCell(null, $styles['secondaryTableHeaderRowStyle']);
                $categoryHeaderCell->getStyle()->setGridSpan($category->plos->count());
                $categoryHeaderCell->addText($category->plo_category, $styles['tableHeaderFontStyle']);
            }
        }
        if ($program->programLearningOutcomes->where('plo_category_id', null)->count() > 0) {
            $unCategorizedHeaderCell = $outcomeMapTbl->addCell(null, $styles['secondaryTableHeaderRowStyle']);
            $unCategorizedHeaderCell->getStyle()->setGridSpan($program->programLearningOutcomes->where('plo_category_id', null)->count());
            $unCategorizedHeaderCell->addText('Uncategorized', $styles['tableHeaderFontStyle']);
        }
        $outcomeMapTbl->addRow();
        $outcomeMapTbl->addCell();
        foreach ($program->ploCategories as $category) {
            if ($category->plos->count() > 0) {
                foreach ($category->plos as $plo) {
                    if (isset($plo->plo_shortphrase)) {
                        $outcomeMapTbl->addCell()->addText($plo->plo_shortphrase);
                    } else {
                        $outcomeMapTbl->addCell()->addText($plo->pl_outcome);
                    }
                }
            }
        }
        if ($program->programLearningOutcomes->where('plo_category_id', null)->count() > 0) {
            foreach ($program->programLearningOutcomes->where('plo_category_id', null) as $uncategorizedPLO) {
                if (isset($uncategorizedPLO->plo_shortphrase)) {
                    $outcomeMapTbl->addCell()->addText($uncategorizedPLO->plo_shortphrase);
                } else {
                    $outcomeMapTbl->addCell()->addText($uncategorizedPLO->pl_outcome);
                }
            }
        }
        foreach ($clos as $clo) {
            $outcomeMapTbl->addRow();
            if (isset($clo->clo_shortphrase)) {
                $outcomeMapTbl->addCell()->addText($clo->clo_shortphrase);
            } else {
                $outcomeMapTbl->addCell()->addText($clo->l_outcome);
            }

            foreach ($program->ploCategories as $category) {
                if ($category->plos->count() > 0) {
                    foreach ($category->plos as $plo) {
                        if (! array_key_exists($plo->pl_outcome_id, $outcomeMap)) {
                            $outcomeMapTbl->addCell();
                        } else {
                            if (! array_key_exists($clo->l_outcome_id, $outcomeMap[$plo->pl_outcome_id])) {
                                $outcomeMapTbl->addCell();
                            } else {
                                $mappingScale = $outcomeMap[$plo->pl_outcome_id][$clo->l_outcome_id];
                                $outcomeMapTbl->addCell(null, ['bgColor' => substr($mappingScale->colour, 1)])->addText($mappingScale->abbreviation);
                            }
                        }
                    }
                }
            }

            if ($program->programLearningOutcomes->where('plo_category_id', null)->count() > 0) {
                foreach ($program->programLearningOutcomes->where('plo_category_id', null) as $uncategorizedPLO) {
                    if (! array_key_exists($uncategorizedPLO->pl_outcome_id, $outcomeMap)) {
                        $outcomeMapTbl->addCell();
                    } else {
                        if (! array_key_exists($clo->l_outcome_id, $outcomeMap[$uncategorizedPLO->pl_outcome_id])) {
                            $outcomeMapTbl->addCell();
                        } else {
                            $mappingScale = $outcomeMap[$uncategorizedPLO->pl_outcome_id][$clo->l_outcome_id];
                            $outcomeMapTbl->addCell(null, ['bgColor' => substr($mappingScale->colour, 1)])->addText($mappingScale->abbreviation);
                        }
                    }
                }
            }
        }
        $docTemplate->setComplexBlock('outcomeMap-'.strval($index), $outcomeMapTbl);
    }

    public function duplicate(Request $request, $syllabusId): RedirectResponse
    {

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
