<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Models\AssessmentMethod;
use App\Models\Course;
use App\Models\CourseProgram;
use App\Models\LearningActivity;
use App\Models\MappingScale;
use App\Models\MappingScaleProgram;
use App\Models\OutcomeMap;
use App\Models\PLOCategory;
use App\Models\Program;
use App\Models\ProgramLearningOutcome;
use App\Models\ProgramUser;
use App\Models\StandardCategory;
use App\Models\StandardScale;
use App\Models\StandardsOutcomeMap;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\ConditionalFormatting\Wizard;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Throwable;

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
    public function index(): RedirectResponse
    {
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
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        //
        $this->validate($request, [
            'program' => 'required',
            'level' => 'required',
            // 'faculty'=> 'required',
        ]);

        $program = new Program;
        $program->program = $request->input('program');

        if ($request->input('level') != 'Bachelors' && $request->input('level') != 'Masters' && $request->input('level') != 'Doctoral' && $request->input('level') != 'Other') {
            $program->level = 'Other';
        } else {
            $program->level = $request->input('level');
        }
        $program->faculty = $request->input('faculty');
        $program->department = $request->input('department');
        $program->campus = $request->input('campus');
        $program->status = -1;

        // get users name for last_modified_user
        $user = User::find(Auth::id());
        $program->last_modified_user = $user->name;

        if ($program->save()) {
            $request->session()->flash('success', 'New program added');
        } else {
            $request->session()->flash('error', 'There was an error Adding the program');
        }

        $programUser = new ProgramUser;
        $programUser->user_id = $request->input('user_id');

        $programUser->program_id = $program->program_id;
        // assign the creator of the program the owner permission
        $programUser->permission = 1;
        $programUser->save();

        return redirect()->route('programWizard.step1', $program->program_id);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $program_id): RedirectResponse
    {
        //
        $this->validate($request, [
            'program' => 'required',
            'level' => 'required',
            // 'faculty'=> 'required',
        ]);

        $program = Program::where('program_id', $program_id)->first();
        $program->program = $request->input('program');
        if ($request->input('level') != 'Bachelors' && $request->input('level') != 'Masters' && $request->input('level') != 'Doctoral' && $request->input('level') != 'Other') {
            $program->level = 'Other';
        } else {
            $program->level = $request->input('level');
        }
        $program->department = $request->input('department');
        $program->faculty = $request->input('faculty');
        $program->campus = $request->input('campus');

        // get users name for last_modified_user
        $user = User::find(Auth::id());
        $program->last_modified_user = $user->name;

        if ($program->save()) {
            // update courses 'updated_at' field
            $program = Program::find($program_id);
            $program->touch();

            $request->session()->flash('success', 'Program updated');
        } else {
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
    public function destroy(Request $request, $program_id): RedirectResponse
    {
        // find the program to delete
        $program = Program::find($program_id);
        // find the current user
        $currentUser = User::find(Auth::id());
        //get the current users permission level for the program delete
        $currentUserPermission = $currentUser->programs->where('program_id', $program_id)->first()->pivot->permission;
        // if the current user own the program, then try to delete it
        if ($currentUserPermission == 1) {
            if ($program->delete()) {
                $request->session()->flash('success', 'Program has been deleted');
            } else {
                $request->session()->flash('error', 'There was an error deleting the program');
            }
        } else {
            $request->session()->flash('error', 'You do not have permission to delete this program');
        }

        return redirect()->route('home');
    }

    public function submit(Request $request, $program_id): RedirectResponse
    {
        //
        $p = Program::where('program_id', $program_id)->first();
        $p->status = 1;

        if ($p->save()) {
            $request->session()->flash('success', 'Program settings have been submitted');
        } else {
            $request->session()->flash('error', 'There was an error submitting the program settings');
        }

        return redirect()->route('home');
    }

    /**
     * Get 2D array of courses indexed by their level for the program with $programId.
     *
     * @param Request HTTP request
     * @param  int  $prorgamId
     * @return array
     */
    public function getCoursesByLevel($programId)
    {
        $program = Program::find($programId);
        $coursesByLevels['100 Level'] = collect();
        $coursesByLevels['200 Level'] = collect();
        $coursesByLevels['300 Level'] = collect();
        $coursesByLevels['400 Level'] = collect();
        $coursesByLevels['500 Level'] = collect();
        $coursesByLevels['600 Level'] = collect();
        $coursesByLevels['Other'] = collect();

        foreach ($program->courses as $course) {
            switch ($course->course_num[0]) {
                case 1:
                    $coursesByLevels['100 Level']->push($course);
                    break;
                case 2:
                    $coursesByLevels['200 Level']->push($course);
                    break;
                case 3:
                    $coursesByLevels['300 Level']->push($course);
                    break;
                case 4:
                    $coursesByLevels['400 Level']->push($course);
                    break;
                case 5:
                    $coursesByLevels['500 Level']->push($course);
                    break;
                case 6:
                    $coursesByLevels['600 Level']->push($course);
                    break;
                default:
                    $coursesByLevels['Other']->push($course);
            }
        }

        return $coursesByLevels;
    }

    /**
     * Helper for spreadsheet and pdf summary files which gets images of the charts included in this program
     *
     * @param Request HTTP request
     * @param  int  $programId
     * @return array $url of pdf
     */
    private function getImagesOfCharts(int $programId, $dstFileExt): array
    {

        // find the program
        $program = Program::find($programId);
        // get all the courses this program belongs to
        $programCourses = $program->courses;
        // get program mapping scales of this program
        $mappingScales = $program->mappingScaleLevels;

        // TODO: refactor and clean up the code BELOW to reduce its cognitive complexity.
        // It was taken from ProgramWizardController.php which also needs to be refactored

        // get array of mapping scale abbreviations and add N/A
        $mappingScalesAbbrevArr = $mappingScales->pluck('abbreviation')->toArray();
        $mappingScalesAbbrevArr[count($mappingScalesAbbrevArr)] = 'N/A';
        // get array of mapping scale ids
        $mappingScaleIdsArr = $mappingScales->pluck('map_scale_id')->toArray();
        // set id of N/A to 0
        $mappingScaleIdsArr[count($mappingScaleIdsArr)] = 0;
        // create array of mapping scale colors
        $programMappingScalesColors = [];
        // create an array of mapping scale frequencies in course alignment
        $freqOfMSIds = [];
        for ($index = 0; $index < count($mappingScaleIdsArr); $index++) {
            $freqOfMSIds[$mappingScaleIdsArr[$index]] = [];
            $programMappingScalesColors[$index] = (strtolower(MappingScale::where('map_scale_id', $mappingScaleIdsArr[$index])->pluck('colour')->first()) == '#ffffff' || strtolower(MappingScale::where('map_scale_id', $mappingScaleIdsArr[$index])->pluck('colour')->first()) == '#fff' ? '#6c757d' : MappingScale::where('map_scale_id', $mappingScaleIdsArr[$index])->pluck('colour')->first());
        }
        // get categorized plo's for the program (ordered by category then outcome id)
        $plosInCatOrdered = ProgramLearningOutcome::where('program_id', $programId)->whereNotNull('plo_category_id')->orderBy('plo_category_id', 'ASC')->orderBy('pl_outcome_id', 'ASC')->get();
        // get UnCategorized PLO's
        $unCatPLOS = ProgramLearningOutcome::where('program_id', $programId)->whereNull('plo_category_id')->get();
        // Merge Categorized PLOs and Uncategorized PLOs to get allPlos in the correct order
        $allPlos = $plosInCatOrdered->toBase()->merge($unCatPLOS);
        // get shortphrase of all plos
        $plosInOrder = $allPlos->pluck('plo_shortphrase')->toArray();
        // get array of all plo ids
        $plosInOrderIds = $allPlos->pluck('pl_outcome_id')->toArray();

        // loop through $freqOfMSIds then
        // loop through PLOs ($ploInOrderIds) and get array [countOfAbvFor(plo1), countOfAbvFor(plo2), ... , countOfAbvFor(plo7)]
        foreach ($freqOfMSIds as $ms_id => $freqOfMSId) {
            foreach ($plosInOrderIds as $plosInOrderId) {
                array_push($freqOfMSIds[$ms_id], OutcomeMap::where('pl_outcome_id', $plosInOrderId)->where('map_scale_id', $ms_id)->count());
            }
        }
        // Change key so that order isn't messed up when data is used in highcharts
        $index = 0;
        $freqForMS = [];
        foreach ($freqOfMSIds as $ms_id => $freqOfMSId) {
            $freqForMS[$index] = $freqOfMSId;
            $index++;
        }
        // create series array for highcharts
        $seriesPLOCLO = [];
        for ($count = 0; $count < count($mappingScalesAbbrevArr); $count++) {
            array_push($seriesPLOCLO, ['name' => $mappingScalesAbbrevArr[$count], 'data' => $freqForMS[$count], 'colour' => $programMappingScalesColors[$count]]);
        }

        // DATA FOR ASSESSMENT METHODS

        $assessmentMethods = [];
        foreach ($programCourses as $programCourse) {
            array_push($assessmentMethods, AssessmentMethod::where('course_id', $programCourse->course_id)->pluck('a_method'));
        }
        $allAM = [];
        foreach ($assessmentMethods as $ams) {
            foreach ($ams as $am) {
                array_push($allAM, ucwords($am));
            }
        }
        // Get frequencies for all assessment methods
        $amFrequencies = [];
        if (count($allAM) >= 1) {
            for ($i = 0; $i < count($allAM); $i++) {
                if (array_key_exists($allAM[$i], $amFrequencies)) {
                    $amFrequencies[$allAM[$i]] += 1;
                } else {
                    $amFrequencies += [$allAM[$i] => 1];
                }
            }

            // Special Case (Might be removed in the future)
            // if there exists 'Final' and 'Final Exam' then combine them into 'Final Exam'
            if (array_key_exists('Final Exam', $amFrequencies) && array_key_exists('Final', $amFrequencies)) {
                $amFrequencies['Final Exam'] += $amFrequencies['Final'];
                unset($amFrequencies['Final']);
            }
        }
        $amTitles = array_keys($amFrequencies);
        $amData = [
            [
                'name' => '# of Occurrences',
                'data' => array_values($amFrequencies),
                'colorByPoint' => true,
            ],
        ];

        // Get frequencies for all learning activities
        $learningActivities = [];
        foreach ($programCourses as $programCourse) {
            array_push($learningActivities, LearningActivity::where('course_id', $programCourse->course_id)->pluck('l_activity'));
        }
        $allLA = [];
        foreach ($learningActivities as $lAS) {
            foreach ($lAS as $la) {
                array_push($allLA, ucwords($la));
            }
        }
        // Get frequencies for all Learning Activities
        $laFrequencies = [];
        if (count($allLA) >= 1) {
            for ($i = 0; $i < count($allLA); $i++) {
                if (array_key_exists($allLA[$i], $laFrequencies)) {
                    $laFrequencies[$allLA[$i]] += 1;
                } else {
                    $laFrequencies += [$allLA[$i] => 1];
                }
            }
        }

        $laTitles = array_keys($laFrequencies);
        $laData = [
            [
                'name' => '# of Occurrences',
                'data' => array_values($laFrequencies),
                'colorByPoint' => true,
            ],
        ];

        // Code to generate ministry standards chart
        $hasNoMS = false;

        // Get all Standard Categories for courses in the program
        if ($program->level == 'Undergraduate' || $program->level == 'Bachelors') {
            $standardCategory = StandardCategory::find(1);
        } elseif ($program->level == 'Masters') {
            $standardCategory = StandardCategory::find(2);
        } elseif ($program->level == 'Doctoral') {
            $standardCategory = StandardCategory::find(3);
        } else {
            $hasNoMS = true;
            $standardCategory = StandardCategory::find(0);
        }

        if (! $hasNoMS) {
            // Get all Standards for courses in the program
            $standards = $standardCategory->standards;

            // Get the names of the standards for the categories (x-axis)
            $namesStandards = [];
            $descriptionsStandards = [];
            for ($i = 0; $i < count($standards); $i++) {
                $namesStandards[$i] = $standards[$i]->s_shortphrase;
                $descriptionsStandards[$i] = $standards[$i]->s_outcome;
            }

            // Get Standards Mapping Scales for high-chart
            $standardsMappingScales = StandardScale::where('scale_category_id', 1)->pluck('abbreviation')->toArray();
            $standardsMappingScales[count($standardsMappingScales)] = 'N/A';
            $standardsMappingScalesTitles = StandardScale::where('scale_category_id', 1)->pluck('title')->toArray();
            $standardsMappingScalesTitles[count($standardsMappingScales)] = StandardScale::find(0)->pluck('title')->first();

            // Get Standards Mapping Scale Colours for high-chart
            $standardMappingScalesIds = StandardScale::where('scale_category_id', 1)->pluck('standard_scale_id')->toArray();
            $standardMappingScalesIds[count($standardMappingScalesIds)] = 0;
            $standardMappingScalesColours = [];
            $freqOfMinistryStandardIds = [];          // used in a later step
            $coursesOfMinistryStandardIds = [];
            for ($i = 0; $i < count($standardMappingScalesIds); $i++) {
                $freqOfMinistryStandardIds[$standardMappingScalesIds[$i]] = [];
                $standardMappingScalesColours[$i] = (strtolower(StandardScale::where('standard_scale_id', $standardMappingScalesIds[$i])->pluck('colour')->first()) == '#ffffff' || strtolower(StandardScale::where('standard_scale_id', $standardMappingScalesIds[$i])->pluck('colour')->first()) == '#fff' ? '#6c757d' : StandardScale::where('standard_scale_id', $standardMappingScalesIds[$i])->pluck('colour')->first());
            }
            foreach ($freqOfMinistryStandardIds as $ms => $freqOfMinistryStandardId) {
                foreach ($standards as $standard) {
                    $freqOfMinistryStandardIds[$ms][$standard->standard_id] = 0;
                    $coursesOfMinistryStandardIds[$ms][$standard->standard_id] = [];
                }
            }

            $programCoursesFiltered = $program->courses()->where('standard_category_id', $standardCategory->standard_category_id)->get();

            $outputStandardOutcomeMaps = [];
            foreach ($programCoursesFiltered as $course) {
                // check that outcome map exists
                if (StandardsOutcomeMap::where('course_id', $course->course_id)->exists()) {
                    foreach ($standards as $standard) {
                        $scale_id = StandardsOutcomeMap::where('course_id', $course->course_id)->where('standard_id', $standard->standard_id)->value('standard_scale_id');
                        $freqOfMinistryStandardIds[$scale_id][$standard->standard_id] += 1;
                        array_push($coursesOfMinistryStandardIds[$scale_id][$standard->standard_id], $course->course_id);
                    }
                }
            }
            $frequencyOfMinistryStandardIds = $this->resetKeys($freqOfMinistryStandardIds);
            $coursesOfMinistryStandardResetKeys = $this->resetKeys($coursesOfMinistryStandardIds);

            $tableMS = $this->generateHTMLTableMinistryStandards($namesStandards, $standardsMappingScalesTitles, $frequencyOfMinistryStandardIds, $coursesOfMinistryStandardResetKeys, $standardMappingScalesColours, $descriptionsStandards);

            // create series array for highcharts
            $seriesMS = [];
            for ($count = 0; $count < count($standardsMappingScales); $count++) {
                array_push($seriesMS, ['name' => $standardsMappingScales[$count], 'data' => $frequencyOfMinistryStandardIds[$count], 'color' => $standardMappingScalesColours[$count]]);
            }
        }

        // TODO: refactor and clean up the code ABOVE to reduce its cognitive complexity.
        // It was taken from ProgramWizardController.php which also needs to be refactored

        // get url of plos to clos cluster chart

        //setting default shorthands for PLOs so chart doesn't use index
        for ($i = 0; $i < count($plosInOrder); $i++) {
            if ($plosInOrder[$i] == null) {
                $plosInOrder[$i] = 'PLO #'.($i + 1);
            }
        }

        $plosToClosClusterChartImgURL = $this->barChartPOST(
            'plosToClosCluster-'.$program->program_id.'.jpeg',
            'Number of Course Outcomes per Program Learning Outcomes',
            'Program Learning Outcomes',
            '# of Outcomes',
            $plosInOrder,
            $seriesPLOCLO,
            true
        );
        // get url of assessment methods chart
        $assessmentMethodsChartImgUrl = $this->barChartPOST(
            'all-am-'.$program->program_id.'.jpeg',
            'Assessment Methods',
            'Assessment Method',
            'Frequency',
            $amTitles,
            $amData
        );
        // get url of learning activities chart
        $learningActivitiesChartImgUrl = $this->barChartPOST(
            'all-la-'.$program->program_id.'.jpeg',
            'Learning Activities',
            'Learning Activity',
            'Frequency',
            $laTitles,
            $laData
        );
        if (! $hasNoMS) {
            // get url of ministry standards cluster chart
            $ministryStandardsClusterChartImgURL = $this->barChartPOST(
                'ministryStandardsCluster-'.$program->program_id.'.jpeg',
                'Alignment with Ministry Standards',
                'Ministry Standards Outcomes',
                '# of Outcomes',
                $namesStandards,
                $seriesMS,
                true
            );
        }

        $chartsBaseURL = config('app.url').'/storage/charts/';
        $chartsBasePath = Storage::path('public'.DIRECTORY_SEPARATOR.'charts'.DIRECTORY_SEPARATOR);
        if (! $hasNoMS) {
            if ($dstFileExt == 'pdf') {
                return [
                    'Program MAP Chart' => $chartsBaseURL.$plosToClosClusterChartImgURL,
                    'Assessment Methods Chart' => $chartsBaseURL.$assessmentMethodsChartImgUrl,
                    'Learning Activities Chart' => $chartsBaseURL.$learningActivitiesChartImgUrl,
                    'Ministry Standards Chart' => $chartsBaseURL.$ministryStandardsClusterChartImgURL,
                ];
            } else {
                return [
                    'Program MAP Chart' => $chartsBasePath.$plosToClosClusterChartImgURL,
                    'Assessment Methods Chart' => $chartsBasePath.$assessmentMethodsChartImgUrl,
                    'Learning Activities Chart' => $chartsBasePath.$learningActivitiesChartImgUrl,
                    'Ministry Standards Chart' => $chartsBasePath.$ministryStandardsClusterChartImgURL,
                ];
            }
        } else {
            if ($dstFileExt == 'pdf') {
                return [
                    'Program MAP Chart' => $chartsBaseURL.$plosToClosClusterChartImgURL,
                    'Assessment Methods Chart' => $chartsBaseURL.$assessmentMethodsChartImgUrl,
                    'Learning Activities Chart' => $chartsBaseURL.$learningActivitiesChartImgUrl,
                ];
            } else {
                return [
                    'Program MAP Chart' => $chartsBasePath.$plosToClosClusterChartImgURL,
                    'Assessment Methods Chart' => $chartsBasePath.$assessmentMethodsChartImgUrl,
                    'Learning Activities Chart' => $chartsBasePath.$learningActivitiesChartImgUrl,
                ];
            }
        }
    }

    /**
     * Helper for spreadsheet and pdf summary files which fetches and saves an image of a highcharts bar chart used in this program
     *
     * @param  string  $filename: filename of saved image
     * @param  string  $title: title of bar chart
     * @param  string  $xLabel: x axis label
     * @param  string  $yLabel: y axis label
     * @param  array  $categories: x axis categories
     * @param  bool  $hasLegend: include legend
     * @param  array  $data: data for each category
     * @return string $url of image
     */
    private function barChartPOST($filename, $title, $xLabel, $yLabel, $categories, $data, $hasLegend = false): string
    {

        // create highcharts configuration object for a bar chart
        $config = json_encode(
            [
                'chart' => [
                    'type' => 'column',
                ],
                'title' => [
                    'text' => $title,
                ],
                'xAxis' => [
                    'title' => [
                        'text' => $xLabel,
                        'margin' => 20,
                        'style' => [
                            'fontWeight' => 'bold',
                        ],
                    ],
                    'categories' => $categories,
                ],
                'yAxis' => [
                    'title' => [
                        'text' => $yLabel,
                        'margin' => 20,
                    ],
                    'allowDecimals' => false,
                ],
                'legend' => [
                    'enabled' => $hasLegend,
                ],
                'series' => $data,
            ]
        );

        // create curl resource for POST request
        $ch = curl_init();
        // set URL and other appropriate options for POST
        $options = [
            // endpoint is the highcharts export server
            CURLOPT_URL => 'http://export.highcharts.com/',
            CURLOPT_HEADER => false,
            // return the transfer as a string
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ['type' => 'image/jpeg', 'width' => 600, 'options' => $config],

        ];
        curl_setopt_array($ch, $options);
        // $output contains the output string
        $output = curl_exec($ch);
        // save the image to the storage/public/charts directory which is accessible via public folder due to a symbolic link
        Storage::put('public'.DIRECTORY_SEPARATOR.'charts'.DIRECTORY_SEPARATOR.$filename, $output);
        // close curl resource to free up system resources
        curl_close($ch);

        return $filename;
    }

    /**
     * Create and save a pdf summary for this program.
     *
     * @param Request HTTP request
     * @param  int  $programId
     * @return string $url of pdf
     */
    public function pdf(Request $request, $program_id)
    {
        // set the max time to generate a pdf summary as 5 mins/300 seconds
        set_time_limit(300);
        try {
            $user = User::where('id', Auth::id())->first();
            $program = Program::where('program_id', $program_id)->first();

            //set array of flags to determine what content to include in downloadSummary.blade.php
            $programContent = [];

            if ($request->input('formFilled') == null) {
                $programContent = [1, 1, 1, 1, 1, 1, 1];

            } else {

                $programContent[0] = $request->input('PLOs');
                $programContent[1] = $request->input('mapping_scales');
                $programContent[2] = $request->input('freq_dist_tables');
                $programContent[3] = $request->input('clos_bar');
                $programContent[4] = $request->input('assessment_methods_bar');
                $programContent[5] = $request->input('learning_activities_bar');
                $programContent[6] = $request->input('ministry_stds_bar');

            }

            $coursesByLevels = $this->getCoursesByLevel($program_id);
            //progress bar
            $ploCount = ProgramLearningOutcome::where('program_id', $program_id)->count();
            $msCount = MappingScale::join('mapping_scale_programs', 'mapping_scales.map_scale_id', '=', 'mapping_scale_programs.map_scale_id')
                ->where('mapping_scale_programs.program_id', $program_id)->count();
            //
            $courseCount = CourseProgram::where('program_id', $program_id)->count();
            //
            $mappingScales = MappingScale::join('mapping_scale_programs', 'mapping_scales.map_scale_id', '=', 'mapping_scale_programs.map_scale_id')
                ->where('mapping_scale_programs.program_id', $program_id)->get();
            // ploIndexArray[$plo->pl_outcome_id] = $index
            $ploIndexArray = [];
            foreach ($program->programLearningOutcomes as $index => $plo) {
                $ploIndexArray[$plo->pl_outcome_id] = $index + 1;
            }
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
            $uniqueCategories = [];
            foreach ($ploProgramCategories as $ploInCategory) {
                if (! in_array($ploInCategory->plo_category_id, $uniqueCategories)) {
                    $uniqueCategories[] += $ploInCategory->plo_category_id;
                    $numCatUsed++;
                }
            }

            // plosPerCategory returns the number of plo's belonging to each category
            // used for setting the colspan in the view
            $plosPerCategory = [];
            foreach ($ploProgramCategories as $ploCategory) {
                $plosPerCategory[$ploCategory->plo_category_id] = 0;
            }
            foreach ($ploProgramCategories as $ploCategory) {
                $plosPerCategory[$ploCategory->plo_category_id] += 1;
            }

            // Used for setting colspan in view
            $numUncategorizedPLOS = 0;
            foreach ($allPLO as $plo) {
                if ($plo->plo_category_id == null) {
                    $numUncategorizedPLOS++;
                }
            }

            // returns true if there exists a plo without a category
            $hasUncategorized = false;
            foreach ($plos as $plo) {
                if ($plo->plo_category == null) {
                    $hasUncategorized = true;
                }
            }

            // All Courses Frequency Distribution
            $coursesOutcomes = [];
            $coursesOutcomes = $this->getCoursesOutcomes($coursesOutcomes, $programCourses);
            $arr = [];
            $arr = $this->getOutcomeMaps($allPLO, $coursesOutcomes, $arr);
            $store = [];
            $store = $this->createCDFArray($arr, $store);
            $store = $this->frequencyDistribution($arr, $store);
            $store = $this->replaceIdsWithAbv($store, $arr);
            $store = $this->assignColours($store);

            // Code to generate ministry standards chart
            $hasNoMS = false;

            // Get all Standard Categories for courses in the program
            if ($program->level == 'Undergraduate' || $program->level == 'Bachelors') {
                $standardCategory = StandardCategory::find(1);
            } elseif ($program->level == 'Masters') {
                $standardCategory = StandardCategory::find(2);
            } elseif ($program->level == 'Doctoral') {
                $standardCategory = StandardCategory::find(3);
            } else {
                $hasNoMS = true;
                $standardCategory = StandardCategory::find(0);
            }
            if (! $hasNoMS) {
                // Get all Standards for courses in the program
                $standards = $standardCategory->standards;

                // Get the names of the standards for the categories (x-axis)
                $namesStandards = [];
                $descriptionsStandards = [];
                for ($i = 0; $i < count($standards); $i++) {
                    $namesStandards[$i] = $standards[$i]->s_shortphrase;
                    $descriptionsStandards[$i] = $standards[$i]->s_outcome;
                }

                // Get Standards Mapping Scales for high-chart
                $standardsMappingScales = StandardScale::where('scale_category_id', 1)->pluck('abbreviation')->toArray();
                $standardsMappingScales[count($standardsMappingScales)] = 'N/A';
                $standardsMappingScalesTitles = StandardScale::where('scale_category_id', 1)->pluck('title')->toArray();
                $standardsMappingScalesTitles[count($standardsMappingScales)] = StandardScale::find(0)->pluck('title')->first();

                // Get Standards Mapping Scale Colours for high-chart
                $standardMappingScalesIds = StandardScale::where('scale_category_id', 1)->pluck('standard_scale_id')->toArray();
                $standardMappingScalesIds[count($standardMappingScalesIds)] = 0;
                $standardMappingScalesColours = [];
                $freqOfMinistryStandardIds = [];          // used in a later step
                $coursesOfMinistryStandardIds = [];
                for ($i = 0; $i < count($standardMappingScalesIds); $i++) {
                    $freqOfMinistryStandardIds[$standardMappingScalesIds[$i]] = [];
                    $standardMappingScalesColours[$i] = (strtolower(StandardScale::where('standard_scale_id', $standardMappingScalesIds[$i])->pluck('colour')->first()) == '#ffffff' || strtolower(StandardScale::where('standard_scale_id', $standardMappingScalesIds[$i])->pluck('colour')->first()) == '#fff' ? '#6c757d' : StandardScale::where('standard_scale_id', $standardMappingScalesIds[$i])->pluck('colour')->first());
                }
                foreach ($freqOfMinistryStandardIds as $ms => $freqOfMinistryStandardId) {
                    foreach ($standards as $standard) {
                        $freqOfMinistryStandardIds[$ms][$standard->standard_id] = 0;
                        $coursesOfMinistryStandardIds[$ms][$standard->standard_id] = [];
                    }
                }

                $programCoursesFiltered = $program->courses()->where('standard_category_id', $standardCategory->standard_category_id)->get();

                $outputStandardOutcomeMaps = [];
                foreach ($programCoursesFiltered as $course) {
                    // check that outcome map exists
                    if (StandardsOutcomeMap::where('course_id', $course->course_id)->exists()) {
                        foreach ($standards as $standard) {
                            $scale_id = StandardsOutcomeMap::where('course_id', $course->course_id)->where('standard_id', $standard->standard_id)->value('standard_scale_id');
                            $freqOfMinistryStandardIds[$scale_id][$standard->standard_id] += 1;
                            array_push($coursesOfMinistryStandardIds[$scale_id][$standard->standard_id], $course->course_id);
                        }
                    }
                }
                $frequencyOfMinistryStandardIds = $this->resetKeys($freqOfMinistryStandardIds);
                $coursesOfMinistryStandardResetKeys = $this->resetKeys($coursesOfMinistryStandardIds);

                $tableMS = $this->generateHTMLTableMinistryStandards($namesStandards, $standardsMappingScalesTitles, $frequencyOfMinistryStandardIds, $coursesOfMinistryStandardResetKeys, $standardMappingScalesColours, $descriptionsStandards);
            } else {
                $tableMS = [];
            }

            // get array of urls to charts in this program
            $charts = $this->getImagesOfCharts($program_id, '.pdf');

            //get defaultShortForms based on PLO Category, then Creation Order
            $defaultShortForms = [];
            $defaultShortFormsIndex = [];
            $plosInOrderCat = [];

            foreach ($ploCategories as $ploCat) {
                $plosByCat = ProgramLearningOutcome::where('plo_category_id', $ploCat['plo_category_id'])->get();
                array_push($plosInOrderCat, $plosByCat);

            }

            $ploDefaultCount = 0;
            for ($i = 0; $i < count($plosInOrderCat); $i++) {
                for ($j = 0; $j < count($plosInOrderCat[$i]); $j++) {
                    $defaultShortForms[$plosInOrderCat[$i][$j]['pl_outcome_id']] = 'PLO #'.($ploDefaultCount + 1);
                    $defaultShortFormsIndex[$plosInOrderCat[$i][$j]['pl_outcome_id']] = $ploDefaultCount + 1;
                    $ploDefaultCount++;
                }
            }

            foreach ($unCategorizedPLOS as $unCatPLO) {
                $defaultShortForms[$unCatPLO->pl_outcome_id] = 'PLO #'.($ploDefaultCount + 1);
                $defaultShortFormsIndex[$unCatPLO->pl_outcome_id] = $ploDefaultCount + 1;
                $ploDefaultCount++;
            }

            $pdf = PDF::loadView('programs.downloadSummary', compact('charts', 'coursesByLevels', 'ploIndexArray', 'program', 'ploCount', 'msCount', 'courseCount', 'mappingScales', 'programCourses', 'ploCategories', 'ploProgramCategories', 'allPLO', 'plos', 'unCategorizedPLOS', 'numCatUsed', 'uniqueCategories', 'plosPerCategory', 'numUncategorizedPLOS', 'hasUncategorized', 'store', 'tableMS', 'programContent', 'defaultShortForms', 'defaultShortFormsIndex'));
            // get the content of the pdf document
            $content = $pdf->output();
            // set name of pdf
            $pdfName = 'summary-'.$program->program_id.'.pdf';
            // store the pdf document in storage/app/public folder
            Storage::put('public'.DIRECTORY_SEPARATOR.'pdfs'.DIRECTORY_SEPARATOR.$pdfName, $content);
            // delete charts
            $this->deleteCharts($program_id, $charts);
            // get the url of the document
            $url = Storage::url('pdfs'.DIRECTORY_SEPARATOR.$pdfName);

            // return the location of the pdf document on the server
            return $url;

        } catch (Throwable $exception) {
            $message = 'There was an error downloading program overview for: '.$program->program;
            Log::error($message.' ...\n');
            Log::error('Code - '.$exception->getCode());
            Log::error('File - '.$exception->getFile());
            Log::error('Line - '.$exception->getLine());
            Log::error($exception->getMessage());

            return -1;
        }
    }

    /**
     * Delete the saved spreadsheet file for this program if it exists.
     *
     * @param Request HTTP request
     * @param  int  $programId
     * @return string $url of spreadsheet file
     */
    public function deletePDF(Request $request, $program_id)
    {
        Storage::delete('public/program-'.$program_id.'.pdf');
    }

    /**
     * Build a spreadsheet file of this program.
     *
     * @param Request HTTP $request
     * @param  int  $programId
     * @return string $url of spreadsheet file
     */
    public function spreadsheet(Request $request, int $programId)
    {
        // set the max time to generate a pdf summary as 5 mins/300 seconds
        set_time_limit(300);
        try {
            $program = Program::find($programId);
            // create the spreadsheet
            $spreadsheet = new Spreadsheet();
            // create array of column names
            $columns = range('A', 'Z');
            // create array of styles for spreadsheet
            $styles = [
                'primaryHeading' => [
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'C6E0F5'],
                    ],
                ],
                'secondaryHeading' => [
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'ced4da'],
                    ],
                ],
            ];
            // create each sheet in summary
            $plosSheet = $this->makeLearningOutcomesSheet($spreadsheet, $programId, $styles);
            $mappingScalesSheet = $this->makeMappingScalesSheet($spreadsheet, $programId, $styles);
            $mapSheet = $this->makeOutcomeMapSheet($spreadsheet, $programId, $styles, $columns);

            // get array of urls to charts in this program
            $charts = $this->getImagesOfCharts($programId, '.xlsx');
            $this->makeChartSheets($spreadsheet, $programId, $charts);
            // foreach sheet, set all possible columns in $columns to autosize
            array_walk($columns, function ($letter, $index) use ($plosSheet, $mapSheet, $mappingScalesSheet) {
                $plosSheet->getColumnDimension($letter)->setAutoSize(true);
                $mappingScalesSheet->getColumnDimension($letter)->setAutoSize(true);
                $mapSheet->getColumnDimension($letter)->setAutoSize(true);
            });

            // generate the spreadsheet
            $writer = new Xlsx($spreadsheet);
            // set the spreadsheets name
            $spreadsheetName = 'summary-'.$program->program_id.'.xlsx';
            // create absolute filename
            $storagePath = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'spreadsheets'.DIRECTORY_SEPARATOR.$spreadsheetName);
            // save the spreadsheet document
            $writer->save($storagePath);
            // delete charts
            $this->deleteCharts($programId, $charts);
            // get the url of the document
            $url = Storage::url('spreadsheets'.DIRECTORY_SEPARATOR.$spreadsheetName);

            // return the location of the spreadsheet document on the server
            return $url;

        } catch (Throwable $exception) {
            $message = 'There was an error downloading the spreadsheet overview for: '.$program->program;
            Log::error($message.' ...\n');
            Log::error('Code - '.$exception->getCode());
            Log::error('File - '.$exception->getFile());
            Log::error('Line - '.$exception->getLine());
            Log::error($exception->getMessage());

            return -1;
        }
    }

    /**
     * Private helper function to create sheets with charts in the program summary spreadsheet
     *
     * @param  Spreadsheet  $spreadsheet
     * @param  int  $programId
     * @param  array  $charts: array of urls to charts indexed by their sheet name
     */
    private function makeChartSheets(Spreadsheet $spreadsheet, int $programId, $charts)
    {
        try {
            $program = Program::find($programId);

            foreach ($charts as $chartName => $chartUrl) {
                $sheet = $spreadsheet->createSheet();
                $sheet->setTitle($chartName);
                $imageDrawing = new Drawing;
                $imageDrawing->setPath($chartUrl);
                $imageDrawing->setCoordinates('A1');
                $imageDrawing->setWorksheet($sheet);
                // Add ministry standards table to Ministry standards sheet
                if ($chartName == 'Ministry Standards Chart') {
                    $this->makeMinistryStandardsSheet($sheet, $programId);
                }
            }

        } catch (Throwable $exception) {
            $message = 'There was an error downloading the spreadsheet overview for: '.$program->program;
            Log::error($message.' ...\n');
            Log::error('Code - '.$exception->getCode());
            Log::error('File - '.$exception->getFile());
            Log::error('Line - '.$exception->getLine());
            Log::error($exception->getMessage());

            return -1;
        }
    }

    /**
     * Private helper function to create the learning outcomes sheet in the program summary spreadsheet
     *
     * @param  Spreadsheet  $spreadsheet
     * @param  int  $programId
     * @param  array  $primaryHeaderStyleArr is the style to use for primary headings
     * @return Worksheet
     */
    private function makeMinistryStandardsSheet($sheet, int $programId): Worksheet
    {
        try {
            $program = Program::find($programId);
            $outputMS = $this->getMinistryStandards($programId);
            $styles = [
                'primaryHeading' => [
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'C6E0F5'],
                    ],
                ],
                'secondaryHeading' => [
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'ced4da'],
                    ],
                ],
                'textBold' => [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                        'wrapText' => true,
                    ],
                ],
                'text' => [
                    'font' => [
                        'bold' => false,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                        'wrapText' => true,
                    ],
                ],
            ];
            if (! $outputMS[6]) {

                $sheet->setCellValue('K1', 'Ministry Standards');
                $sheet->mergeCells('K1:P1');
                $sheet->setCellValue('Q1', 'Courses');
                $sheet->mergeCells('Q1:Z1');
                $sheet->getStyle('K1')->applyFromArray($styles['primaryHeading']);
                $sheet->getStyle('Q1')->applyFromArray($styles['primaryHeading']);
                foreach ($outputMS[0] as $index => $standards) {
                    // add standards and descriptions
                    $sheet->setCellValue('K'.strval(($index * 8) + 2), $standards);
                    $sheet->mergeCells('K'.strval(($index * 8) + 2).':P'.strval(($index * 8) + 2).'');
                    $sheet->getStyle('K'.strval(($index * 8) + 2).'')->applyFromArray($styles['secondaryHeading']);
                    $sheet->setCellValue('K'.strval(($index * 8) + 3), strip_tags(preg_replace('~[\r\n\t]+~', '', $outputMS[5][$index])));
                    $sheet->mergeCells('K'.strval(($index * 8) + 3).':P'.strval(($index * 8) + 9).'');
                    $sheet->getStyle('K'.strval(($index * 8) + 3).'')->applyFromArray($styles['text']);

                    $count = 0;
                    foreach ($outputMS[1] as $indexMS => $titleMS) {
                        // add mapping scale titles
                        $sheet->mergeCells('Q'.strval((($index * 8) + 2)).':Z'.strval((($index * 8) + 2)).'');
                        $sheet->getStyle('Q'.strval(($index * 8) + 2).'')->applyFromArray($styles['secondaryHeading']);
                        $sheet->setCellValue('Q'.strval(3 + $count + ($index * 8)), ($titleMS.': '.$outputMS[2][$indexMS][$index]));
                        $sheet->getStyle('Q'.strval(3 + $count + ($index * 8)))->applyFromArray($styles['textBold']);
                        $sheet->mergeCells('Q'.strval((3 + $count + ($index * 8))).':R'.strval((3 + $count + ($index * 8))).'');
                        $k = 0;
                        $output = '';
                        $sheet->mergeCells('S'.strval((3 + $count + ($index * 8))).':Z'.strval((3 + $count + ($index * 8))).'');
                        $sheet->getStyle('S'.strval(3 + $count + ($index * 8)))->applyFromArray($styles['text']);
                        foreach ($outputMS[3][$indexMS][$index] as $indexCourse => $courseId) {
                            $code = Course::where('course_id', $courseId)->pluck('course_code')->first();
                            $num = Course::where('course_id', $courseId)->pluck('course_num')->first();
                            if ($k != 0) {
                                $output .= ', '.$code.' '.$num;
                            } else {
                                $output .= ' '.$code.' '.$num;
                            }
                            $k++;
                        }
                        $sheet->setCellValue('S'.strval(3 + $count + ($index * 8)), ($output));
                        $count++;
                    }
                    // style remaining cells
                    $remainingCells = 8 - count($outputMS[1]);
                    if ($remainingCells > 0) {
                        for ($i = 1; $i < $remainingCells; $i++) {
                            $sheet->mergeCells('Q'.strval((($index * 8) + 2) + ($i + count($outputMS[1]))).':R'.strval((($index * 8) + 2) + ($i + count($outputMS[1]))));
                            $sheet->mergeCells('S'.strval((($index * 8) + 2) + ($i + count($outputMS[1]))).':Z'.strval((($index * 8) + 2) + ($i + count($outputMS[1]))));
                        }
                    }
                }
            }

            return $sheet;

        } catch (Throwable $exception) {
            $message = 'There was an error downloading the spreadsheet overview for: '.$program->program;
            Log::error($message.' ...\n');
            Log::error('Code - '.$exception->getCode());
            Log::error('File - '.$exception->getFile());
            Log::error('Line - '.$exception->getLine());
            Log::error($exception->getMessage());

            return $exception;
        }
    }

    public function getMinistryStandards($program_id)
    {
        $program = Program::where('program_id', $program_id)->first();
        $hasNoMS = false;

        // Get all Standard Categories for courses in the program
        if ($program->level == 'Undergraduate' || $program->level == 'Bachelors') {
            $standardCategory = StandardCategory::find(1);
        } elseif ($program->level == 'Masters') {
            $standardCategory = StandardCategory::find(2);
        } elseif ($program->level == 'Doctoral') {
            $standardCategory = StandardCategory::find(3);
        } else {
            $hasNoMS = true;
            $standardCategory = StandardCategory::find(0);
        }

        // Get all Standards for courses in the program
        $standards = $standardCategory->standards;

        // Get the names of the standards for the categories (x-axis)
        $namesStandards = [];
        $descriptionsStandards = [];
        for ($i = 0; $i < count($standards); $i++) {
            $namesStandards[$i] = $standards[$i]->s_shortphrase;
            $descriptionsStandards[$i] = $standards[$i]->s_outcome;
        }

        // Get Standards Mapping Scales for high-chart
        $standardsMappingScales = StandardScale::where('scale_category_id', 1)->pluck('abbreviation')->toArray();
        $standardsMappingScales[count($standardsMappingScales)] = 'N/A';
        $standardsMappingScalesTitles = StandardScale::where('scale_category_id', 1)->pluck('title')->toArray();
        $standardsMappingScalesTitles[count($standardsMappingScales)] = StandardScale::find(0)->pluck('title')->first();

        // Get Standards Mapping Scale Colours for high-chart
        $standardMappingScalesIds = StandardScale::where('scale_category_id', 1)->pluck('standard_scale_id')->toArray();
        $standardMappingScalesIds[count($standardMappingScalesIds)] = 0;
        $standardMappingScalesColours = [];
        $freqOfMinistryStandardIds = [];          // used in a later step
        $coursesOfMinistryStandardIds = [];
        for ($i = 0; $i < count($standardMappingScalesIds); $i++) {
            $freqOfMinistryStandardIds[$standardMappingScalesIds[$i]] = [];
            $standardMappingScalesColours[$i] = (strtolower(StandardScale::where('standard_scale_id', $standardMappingScalesIds[$i])->pluck('colour')->first()) == '#ffffff' || strtolower(StandardScale::where('standard_scale_id', $standardMappingScalesIds[$i])->pluck('colour')->first()) == '#fff' ? '#6c757d' : StandardScale::where('standard_scale_id', $standardMappingScalesIds[$i])->pluck('colour')->first());
        }
        foreach ($freqOfMinistryStandardIds as $ms => $freqOfMinistryStandardId) {
            foreach ($standards as $standard) {
                $freqOfMinistryStandardIds[$ms][$standard->standard_id] = 0;
                $coursesOfMinistryStandardIds[$ms][$standard->standard_id] = [];
            }
        }

        $programCoursesFiltered = $program->courses()->where('standard_category_id', $standardCategory->standard_category_id)->get();

        $outputStandardOutcomeMaps = [];
        foreach ($programCoursesFiltered as $course) {
            // check that outcome map exists
            if (StandardsOutcomeMap::where('course_id', $course->course_id)->exists()) {
                foreach ($standards as $standard) {
                    $scale_id = StandardsOutcomeMap::where('course_id', $course->course_id)->where('standard_id', $standard->standard_id)->value('standard_scale_id');
                    $freqOfMinistryStandardIds[$scale_id][$standard->standard_id] += 1;
                    array_push($coursesOfMinistryStandardIds[$scale_id][$standard->standard_id], $course->course_id);
                }
            }
        }
        $frequencyOfMinistryStandardIds = $this->resetKeys($freqOfMinistryStandardIds);
        $coursesOfMinistryStandardResetKeys = $this->resetKeys($coursesOfMinistryStandardIds);
        $standardsMappingScalesTitles = $this->resetKeysSingle($standardsMappingScalesTitles);

        return [$namesStandards, $standardsMappingScalesTitles, $frequencyOfMinistryStandardIds, $coursesOfMinistryStandardResetKeys, $standardMappingScalesColours, $descriptionsStandards, $hasNoMS];
    }

    /**
     * Private helper function to create the learning outcomes sheet in the program summary spreadsheet
     *
     * @param  Spreadsheet  $spreadsheet
     * @param  int  $programId
     * @param  array  $primaryHeaderStyleArr is the style to use for primary headings
     * @return Worksheet
     */
    private function makeLearningOutcomesSheet(Spreadsheet $spreadsheet, int $programId, $styles): Worksheet
    {
        try {
            $program = Program::find($programId);
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Learning Outcomes');
            $uncategorizedPLOs = $program->programLearningOutcomes->where('plo_category_id', null)->values();

            // keeps track of which row to put each category in the learning outcomes sheet
            $categoryRowInPLOsSheet = 1;
            foreach ($program->ploCategories as $index => $category) {
                if ($plosInCategory = $category->plos()->get()) {
                    // add category title to learning outcomes sheet
                    $sheet->setCellValue('A'.strval($categoryRowInPLOsSheet), $category->plo_category);
                    // span category title over secondary headings
                    $sheet->mergeCells('A'.strval($categoryRowInPLOsSheet).':B'.strval($categoryRowInPLOsSheet));
                    $sheet->getStyle('A'.strval($categoryRowInPLOsSheet))->applyFromArray($styles['secondaryHeading']);

                    // add secondary header titles to learning outcomes sheet after the category title
                    $sheet->fromArray(['Learning Outcome', 'Short Phrase'], null, 'A'.strval($categoryRowInPLOsSheet + 1));
                    $sheet->getStyle('A'.strval($categoryRowInPLOsSheet + 1).':B'.strval($categoryRowInPLOsSheet + 1))->applyFromArray($styles['primaryHeading']);

                    foreach ($plosInCategory as $index => $plo) {
                        // create row to add to learning outcomes sheet with shortphrase and outcome
                        $ploArr = [$plo->pl_outcome, $plo->plo_shortphrase];
                        // add plo row to learning outcome sheets under secondary headings
                        $sheet->fromArray($ploArr, null, 'A'.strval($categoryRowInPLOsSheet + 2 + $index));
                    }

                    // if it's not the last increment position of next category heading by the number of plos in the current category
                    if ($index != $program->ploCategories->count() - 1) {
                        $categoryRowInPLOsSheet = $categoryRowInPLOsSheet + $category->plos->count() + 3;
                    }
                }
            }

            if ($uncategorizedPLOs->count() > 0) {
                // add uncategorized category title to learning outcomes sheet
                $sheet->setCellValue('A'.strval($categoryRowInPLOsSheet), 'Uncategorized');
                // span uncategorized category title over secondary headings
                $sheet->mergeCells('A'.strval($categoryRowInPLOsSheet).':B'.strval($categoryRowInPLOsSheet));
                $sheet->getStyle('A'.strval($categoryRowInPLOsSheet))->applyFromArray($styles['secondaryHeading']);

                // add secondary header titles to learning outcomes sheet after the category title
                $sheet->fromArray(['Short Phrase', 'Learning Outcome'], null, 'A'.strval($categoryRowInPLOsSheet + 1));
                $sheet->getStyle('A'.strval($categoryRowInPLOsSheet + 1).':B'.strval($categoryRowInPLOsSheet + 1))->applyFromArray($styles['primaryHeading']);

                foreach ($uncategorizedPLOs as $index => $plo) {
                    // create row to add to learning outcomes sheet with shortphrase and outcome
                    $ploArr = [$plo->pl_outcome, $plo->plo_shortphrase];
                    // add plo row to learning outcome sheets under secondary headings
                    $sheet->fromArray($ploArr, null, 'A'.strval($categoryRowInPLOsSheet + 2 + $index));
                }
            }

            return $sheet;

        } catch (Throwable $exception) {
            $message = 'There was an error downloading the spreadsheet overview for: '.$program->program;
            Log::error($message.' ...\n');
            Log::error('Code - '.$exception->getCode());
            Log::error('File - '.$exception->getFile());
            Log::error('Line - '.$exception->getLine());
            Log::error($exception->getMessage());

            return $exception;
        }
    }

    /**
     * Private helper function to create the mapping scales sheet in the program summary spreadsheet
     *
     * @param  Spreadsheet  $spreadsheet
     * @param  int  $programId
     * @param  array  $primaryHeaderStyleArr is the style to use for primary headings
     * @return Worksheet
     */
    private function makeMappingScalesSheet(Spreadsheet $spreadsheet, int $programId, $styles): Worksheet
    {
        try {
            $program = Program::find($programId);
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('Mapping Scale');
            $mappingScaleLevels = $program->mappingScaleLevels;

            if ($mappingScaleLevels->count() > 0) {
                $sheet->fromArray(['Colour', 'Mapping Scale', 'Abbreviation', 'Description'], null, 'A1');
                $sheet->getStyle('A1:D1')->applyFromArray($styles['primaryHeading']);

                foreach ($mappingScaleLevels as $index => $level) {
                    // create arr of scale values to add to mapping scales sheet
                    $scaleArr = [null,  $level->title, $level->abbreviation, $level->description];
                    // add arr of scale values to mapping scales sheet
                    $sheet->fromArray($scaleArr, null, 'A'.strval($index + 2));
                    // add the color for the map scale to the mapping scales sheet
                    $sheet->getStyle('A'.strval($index + 2))->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB(strtoupper(ltrim($level->colour, '#')));
                    $sheet->getStyle('A'.strval($index + 2))->getFill()
                        ->getEndColor()->setRGB(strtoupper(ltrim($level->colour, '#')));
                }
            }

            return $sheet;

        } catch (Throwable $exception) {
            $message = 'There was an error downloading the spreadsheet overview for: '.$program->program;
            Log::error($message.' ...\n');
            Log::error('Code - '.$exception->getCode());
            Log::error('File - '.$exception->getFile());
            Log::error('Line - '.$exception->getLine());
            Log::error($exception->getMessage());

            return $exception;
        }
    }

    /**
     * Private helper function to create the program outcome map sheet in the program summary spreadsheet
     *
     * @param  Spreadsheet  $spreadsheet
     * @param  int  $programId
     * @param  array  $primaryHeaderStyleArr is the style to use for primary headings
     * @return Worksheet
     */
    private function makeOutcomeMapSheet(Spreadsheet $spreadsheet, int $programId, $styles, $columns): Worksheet
    {
        try {
            // find this program
            $program = Program::find($programId);
            // create a sheet for outcome maps
            $sheet = $spreadsheet->createSheet();
            // set the sheet name
            $sheet->setTitle('Program MAP Table');
            // get this programs learning outcomes
            $programLearningOutcomes = $program->programLearningOutcomes;
            // get this programs mapping scales
            $mappingScaleLevels = $program->mappingScaleLevels;
            // get this programs courses
            $courses = $program->courses;
            // if there are no PLOs or courses in this program, return an empty sheet
            if ($programLearningOutcomes->count() < 1 && $courses->count() < 1) {
                return $sheet;
            }

            // add primary headings (courses and program learning outcomes) to program outcome map sheet
            $sheet->fromArray(['Courses', 'Program Learning Outcomes'], null, 'A1');
            // apply styling to the primary headings
            $sheet->getStyle('A1:B1')->applyFromArray($styles['primaryHeading']);
            // span program learning outcomes header over the number of learning outcomes
            $sheet->mergeCells('B1:'.$columns[$program->programLearningOutcomes->count()].'1');
            // create courses array to add to the outcome maps sheet
            $courses = [];
            foreach ($program->courses()->orderBy('course_code', 'asc')->orderBy('course_num', 'asc')->get() as $course) {
                $courses[$course->course_id] = $course->course_code.' '.$course->course_num;
            }
            // add courses to their column in the sheet
            $sheet->fromArray(array_chunk($courses, 1), null, 'A4');
            // apply a secondary header style and
            $sheet->getStyle('A4:A'.strval(4 + count($courses) - 1))->applyFromArray($styles['secondaryHeading']);
            // make courses font bold
            $sheet->getStyle('A4:A100')->getFont()->setBold(true);

            // for each plo, get the outcome map from its course mapping $PLOsToCoursesToOutcomeMap[$plo->pl_outcome_id][$course->course_id] = map
            $coursesToCLOs = $this->getCoursesOutcomes([], $program->courses()->orderBy('course_code', 'asc')->orderBy('course_num', 'asc')->get());
            $programOutcomeMaps = $this->getOutcomeMaps($program->programLearningOutcomes, $coursesToCLOs, []);
            $PLOsToCoursesToOutcomeMap = $this->createCDFArray($programOutcomeMaps, []);
            $PLOsToCoursesToOutcomeMap = $this->frequencyDistribution($programOutcomeMaps, $PLOsToCoursesToOutcomeMap);
            $PLOsToCoursesToOutcomeMap = $this->replaceIdsWithAbv($PLOsToCoursesToOutcomeMap, $programOutcomeMaps);
            $PLOsToCoursesToOutcomeMap = $this->assignColours($PLOsToCoursesToOutcomeMap);

            // $categoryColInMapSheet keeps track of which column to put each category in the program outcome map sheet. $alphabetUpper[1] = 'B'
            $categoryColInMapSheet = 1;
            foreach ($program->ploCategories as $category) {

                if ($category->plos->count() > 0) {
                    $plosInCategory = $category->plos()->get();
                    // add category to outcome map sheet
                    $sheet->setCellValue($columns[$categoryColInMapSheet].'2', $category->plo_category);
                    // apply a secondary header style to category heading
                    $sheet->getStyle($columns[$categoryColInMapSheet].'2')->applyFromArray($styles['secondaryHeading']);
                    // span category over the number of plos in the category
                    $sheet->mergeCells($columns[$categoryColInMapSheet].'2:'.$columns[$categoryColInMapSheet + $plosInCategory->count() - 1].'2');

                    // create an array of plos in this category to add to the sheet under its category
                    $plosInCategoryArr = $plosInCategory->map(function ($plo, $index) use ($PLOsToCoursesToOutcomeMap, $courses, $sheet, $columns, $categoryColInMapSheet) {
                        // create array of map scale abv
                        $ploToCourseMapArr = [];
                        // check if there is a map value for this plo and each course
                        foreach ($courses as $courseId => $courseCode) {
                            if (isset($PLOsToCoursesToOutcomeMap[$plo->pl_outcome_id][$courseId])) {
                                array_push($ploToCourseMapArr, $PLOsToCoursesToOutcomeMap[$plo->pl_outcome_id][$courseId]['map_scale_abv']);
                            } else {
                                array_push($ploToCourseMapArr, '');
                            }
                        }

                        // add array of map scale abv to the plo entry
                        $sheet->fromArray(array_chunk($ploToCourseMapArr, 1), null, $columns[$categoryColInMapSheet + $index].'4');

                        // if the plo has a shortphrase use it in the plo header, otherwise use the full outcome
                        if ($plo->plo_shortphrase) {
                            return $plo->plo_shortphrase;
                        } else {
                            return $plo->pl_outcome;
                        }

                    })->toArray();

                    // add plos in this category to the sheet
                    $sheet->fromArray($plosInCategoryArr, null, $columns[$categoryColInMapSheet].'3');
                    // update category position trackers for learning outcome sheet and outcome map sheet
                    $categoryColInMapSheet = $categoryColInMapSheet + $plosInCategory->count();
                }
            }

            // get uncategorized PLOs
            $uncategorizedPLOs = $programLearningOutcomes->where('plo_category_id', null)->values();
            if ($uncategorizedPLOs->count() > 0) {
                // add uncategorized category to sheet
                $sheet->setCellValue($columns[$categoryColInMapSheet].'2', 'Uncategorized');
                // apply secondary heading to uncategorized header
                $sheet->getStyle($columns[$categoryColInMapSheet].'2')->applyFromArray($styles['secondaryHeading']);
                // span uncategorized header over the number of uncategorized plos
                $sheet->mergeCells($columns[$categoryColInMapSheet].'2:'.$columns[$categoryColInMapSheet + $uncategorizedPLOs->count() - 1].'2');

                // create an array of uncategorized plos to add to the sheet under the uncategorized heading
                $uncategorizedPLOsArr = $uncategorizedPLOs->map(function ($plo, $index) use ($PLOsToCoursesToOutcomeMap, $courses, $sheet, $columns, $categoryColInMapSheet) {
                    // create array of map scale abv
                    $uncategorizedPLOsToCourseMapArr = [];
                    // check if there is a map value for this plo and each course
                    foreach ($courses as $courseId => $courseCode) {
                        if (isset($PLOsToCoursesToOutcomeMap[$plo->pl_outcome_id][$courseId])) {
                            array_push($uncategorizedPLOsToCourseMapArr, $PLOsToCoursesToOutcomeMap[$plo->pl_outcome_id][$courseId]['map_scale_abv']);
                        } else {
                            array_push($uncategorizedPLOsToCourseMapArr, '');
                        }
                    }

                    // add array of map scale abv to the plo entry
                    $sheet->fromArray(array_chunk($uncategorizedPLOsToCourseMapArr, 1), null, $columns[$categoryColInMapSheet + $index].'4');

                    // if the plo has a shortphrase use it in the plo header, otherwise use the full outcome
                    if ($plo->plo_shortphrase) {
                        return $plo->plo_shortphrase;
                    } else {
                        return $plo->pl_outcome;
                    }

                })->toArray();

                // add plos in this category to the sheet
                $sheet->fromArray($uncategorizedPLOsArr, null, $columns[$categoryColInMapSheet].'3');
            }

            // make the list of categories in the program outcome map sheet bold
            $sheet->getStyle('B2:Z2')->getFont()->setBold(true);
            // make the list of plos in the program outcome map sheet bold
            $sheet->getStyle('B3:Z3')->getFont()->setBold(true);

            // create a wizard factory for creating new conditional formatting rules
            $wizardFactory = new Wizard('B4:Z50');
            foreach ($mappingScaleLevels as $level) {
                // create a new conditional formatting rule based on the map scale level
                $wizard = $wizardFactory->newRule(Wizard::CELL_VALUE);
                $levelStyle = new Style(false, true);
                $levelStyle->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB(strtoupper(ltrim($level->colour, '#')));
                $levelStyle->getFill()
                    ->getEndColor()->setRGB(strtoupper(ltrim($level->colour, '#')));
                $wizard->equals($level->abbreviation)->setStyle($levelStyle);
                $conditionalStyles[] = $wizard->getConditional();
                // add conditional formatting rule to the outcome maps sheet
                $sheet->getStyle($wizard->getCellRange())->setConditionalStyles($conditionalStyles);
            }

            return $sheet;

        } catch (Throwable $exception) {
            $message = 'There was an error downloading the spreadsheet overview for: '.$program->program;
            Log::error($message.' ...\n');
            Log::error('Code - '.$exception->getCode());
            Log::error('File - '.$exception->getFile());
            Log::error('Line - '.$exception->getLine());
            Log::error($exception->getMessage());

            return $exception;
        }
    }

    /**
     * Delete the the temporarily saved charts for this program overview.
     *
     * @param  int  $programId
     * @param  array  $charts: array of chart urls
     */
    private function deleteCharts(int $programId, $charts)
    {
        $program = Program::find($programId);
        try {
            foreach ($charts as $chartUrl) {
                File::delete($chartUrl);
            }
        } catch (Throwable $exception) {
            $message = 'There was an error deleting the charts for the spreadsheet overview of: '.$program->program;
            Log::error($message.' ...\n');
            Log::error('Code - '.$exception->getCode());
            Log::error('File - '.$exception->getFile());
            Log::error('Line - '.$exception->getLine());
            Log::error($exception->getMessage());
        }
    }

    /**
     * Delete the saved spreadsheet file for this program if it exists.
     *
     * @param Request HTTP request
     * @param  int  $programId
     */
    public function delSpreadsheet(Request $request, int $programId)
    {
        try {
            $program = Program::find($programId);
            Storage::delete('public/program-'.$program->program_id.'.xlsx');
        } catch (Throwable $exception) {
            $message = 'There was an error deleting the saved spreadsheet overview for: '.$program->program;
            Log::error($message.' ...\n');
            Log::error('Code - '.$exception->getCode());
            Log::error('File - '.$exception->getFile());
            Log::error('Line - '.$exception->getLine());
            Log::error($exception->getMessage());
        }
    }

    public function resetKeysSingle($array)
    {
        $newArray = [];
        // Reset Keys for High-charts
        $i = 0;
        foreach ($array as $a) {
            $newArray[$i] = $a;
            $i++;
        }

        return $newArray;
    }

    public function resetKeys($array)
    {
        $newArray = [];
        // Reset Keys for High-charts
        $i = 0;
        foreach ($array as $a) {
            $j = 0;
            foreach ($a as $data) {
                $newArray[$i][$j] = $data;
                $j++;
            }
            $i++;
        }

        return $newArray;
    }

    public function generateHTMLTableMinistryStandards($namesStandards, $standardsMappingScalesTitles, $frequencyOfMinistryStandardIds, $coursesOfMinistryStandardResetKeys, $standardMappingScalesColours, $descriptionsStandards)
    {
        $output = '';

        if (! count($namesStandards) < 1) {
            $output .= '<table class="table table-light table-bordered table-sm"><tbody><tr class="table-primary"><th>Ministry Standards</th><th>Courses</th></tr>';
            $i = 0;
            foreach ($namesStandards as $standard) {
                $output .= '<tr><td class="col col-md-5"><b>'.$standard.'</b> - '.$descriptionsStandards[$i].'</td><td>';
                $j = 0;
                foreach ($standardsMappingScalesTitles as $standardsMappingScale) {
                    $output .= '<table class="table table-light table-bordered table-sm"><tr><td>';

                    $output .= '<div class="row d-flex align-items-center justify-content-center"><div class="col col-md-1 text-md-right"><div style="background-color:'.$standardMappingScalesColours[$j].'; height: 12px; width: 12px; border-radius: 6px;"></div></div>';
                    $output .= '<div class="col col-md-3 text-md-left">'.$standardsMappingScale.': '.$frequencyOfMinistryStandardIds[$j][$i].'</div>';

                    $output .= '<div class="col col-md-7 text-md-left">';
                    $k = 0;
                    foreach ($coursesOfMinistryStandardResetKeys[$j][$i] as $course_id) {
                        $code = Course::where('course_id', $course_id)->pluck('course_code')->first();
                        $num = Course::where('course_id', $course_id)->pluck('course_num')->first();
                        if ($k != 0) {
                            $output .= ', '.$code.' '.$num;
                        } else {
                            $output .= ' '.$code.' '.$num;
                        }
                        $k++;
                    }
                    $output .= '</div></div>';
                    $j++;

                    $output .= '</td></tr></table>';
                }
                $output .= '</td></tr>';
                $i++;
            }
            $output .= '</tbody></table>';
        } else {
            $output = '<div class="alert alert-warning wizard"><i class="bi bi-exclamation-circle-fill"></i>There are no ministry standards for the courses belonging to this program, or there are no courses matching the criteria.</div>';
        }

        return $output;
    }

    public function getCoursesOutcomes($coursesOutcomes, $programCourses)
    {
        // get all CLO's for each course in the program
        foreach ($programCourses as $programCourse) {
            $learningOutcomes = $programCourse->learningOutcomes;
            $coursesOutcomes[$programCourse->course_id] = $learningOutcomes;
        }

        return $coursesOutcomes;
    }

    public function getOutcomeMaps($allPLO, $coursesOutcomes, $arr)
    {
        // retrieves all the outcome mapping values for every clo and plo
        $count = 0;
        foreach ($allPLO as $plo) {
            // loop through CLOs to get map scale value
            foreach ($coursesOutcomes as $clos) {
                foreach ($clos as $clo) {
                    // Check if record exists in the db
                    if (! OutcomeMap::where(['l_outcome_id' => $clo->l_outcome_id, 'pl_outcome_id' => $plo->pl_outcome_id])->exists()) {
                        // if nothing is found then do nothing
                        // else if record (mapping_scale_id) is found then store it in the array
                    } else {
                        $count++;
                        $mapScaleValue = OutcomeMap::where(['l_outcome_id' => $clo->l_outcome_id, 'pl_outcome_id' => $plo->pl_outcome_id])->value('map_scale_id');
                        $arr[$count] = [
                            'pl_outcome_id' => $plo->pl_outcome_id,
                            'course_id' => $clo->course_id,
                            'map_scale_id' => $mapScaleValue,
                            'l_outcome_id' => $clo->l_outcome_id,
                        ];
                    }
                }
            }
        }

        return $arr;
    }

    public function createCDFArray($arr, $store)
    {
        // Initialize array for each pl_outcome_id with the value of null
        foreach ($arr as $ar) {
            $store[$ar['pl_outcome_id']] = null;
        }
        // Initialize Array for Storing
        foreach ($arr as $ar) {
            if ($store[$ar['pl_outcome_id']] == null || $store[$ar['pl_outcome_id']] == $ar['pl_outcome_id']) {
                $store[$ar['pl_outcome_id']] = [
                    $ar['course_id'] => [
                    ],
                ];
            } else {
                $store[$ar['pl_outcome_id']][$ar['course_id']] = [];
                $store[$ar['pl_outcome_id']][$ar['course_id']]['frequencies'] = [];
            }
        }

        return $store;
    }

    public function frequencyDistribution($arr, $store)
    {
        //Initialize Array for Frequency Distribution
        $freq = [];
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
        foreach ($freq as $plOutcomeId => $dist) {
            foreach ($dist as $courseId => $d) {
                $weight = 0;
                $tieResults = [];
                $id = null;
                //count the number of times a mapping scales appears for a program learning outcome
                foreach ($d as $ms_Id => $mapScaleWeight) {
                    //check if the current ($mapScaleWeight) > than the previously stored value
                    if ($weight < $mapScaleWeight) {
                        $weight = $mapScaleWeight;
                        $id = $ms_Id;
                    }
                }
                // Check if the largest weighted value ties with another value
                foreach ($d as $ms_Id => $mapScaleWeight) {
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
                            $stringResults .= ''.MappingScale::where('map_scale_id', $tieResult)->value('abbreviation').' / ';
                        } else {
                            $stringResults .= ''.MappingScale::where('map_scale_id', $tieResult)->value('abbreviation');
                        }
                    }
                    // Store the results array as the map_scale_value key
                    $store[$plOutcomeId][$courseId] += [
                        'map_scale_abv' => $stringResults,
                    ];
                    // Store a new array to be able to determine if the mapping scale value comes from the result of a tie
                    $store[$plOutcomeId][$courseId] += [
                        'map_scale_id_tie' => true,
                    ];
                    // Store the frequencies
                    $store[$plOutcomeId][$courseId]['frequencies'] = $freq[$plOutcomeId][$courseId];
                } else {
                    // If no tie is present, store the strongest weighted map_scale_value
                    $store[$plOutcomeId][$courseId] = [
                        'map_scale_id' => array_search($weight, $d),
                    ];
                    $store[$plOutcomeId][$courseId] += [
                        'map_scale_abv' => MappingScale::where('map_scale_id', array_search($weight, $d))->value('abbreviation'),
                    ];
                    // Store the frequencies
                    $store[$plOutcomeId][$courseId]['frequencies'] = $freq[$plOutcomeId][$courseId];
                }
            }
        }

        return $store;
    }

    public function replaceIdsWithAbv($store, $arr)
    {
        //Initialize Array for Frequency Distribution
        $freq = [];
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
        foreach ($freq as $plOutcomeId => $dist) {
            foreach ($dist as $courseId => $d) {
                // Store the frequencies
                $store[$plOutcomeId][$courseId]['frequencies'] = $freq[$plOutcomeId][$courseId];
            }
        }

        return $store;
    }

    public function assignColours($store)
    {
        // Assign a colour to store based
        foreach ($store as $plOutcomeId => $s) {
            foreach ($s as $courseId => $msv) {
                // If a tie exists assign it the colour white
                if (array_key_exists('map_scale_id_tie', $msv)) {
                    $mapScaleColour = '#FFFFFF';
                    $store[$plOutcomeId][$courseId] += [
                        'colour' => $mapScaleColour,
                    ];
                } else {
                    // Search for the mapping scale colour in the db, then assign it to the array
                    $mapScaleColour = MappingScale::where('map_scale_id', $msv['map_scale_id'])->value('colour');

                    if ($mapScaleColour == null) {
                        $mapScaleColour = '#FFFFFF';
                    }
                    $store[$plOutcomeId][$courseId] += [
                        'colour' => $mapScaleColour,
                    ];
                }
            }
        }

        return $store;
    }

    public function duplicate(Request $request, $program_id): RedirectResponse
    {
        //
        $this->validate($request, [
            'program' => 'required',
        ]);

        $oldProgram = Program::find($program_id);

        $program = new Program;
        $program->program = $request->input('program');
        $program->level = $oldProgram->level;
        $program->department = $oldProgram->department;
        $program->faculty = $oldProgram->faculty;
        $program->status = -1;

        // get users name for last_modified_user
        $user = User::find(Auth::id());
        $program->last_modified_user = $user->name;

        $program->save();

        // This array is used to keep track of the id's for each category duplicated
        // This is used for the program learning outcomes step to determine which plo belongs to which category
        $historyCategories = [];
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
            if ($plo->plo_category_id == null) {
                $newProgramLearningOutcome->plo_category_id = null;
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
            if ($mapScale->mapping_scale_categories_id == null) {
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
        if ($programUser->save()) {
            $request->session()->flash('success', 'Program has been successfully duplicated');
        } else {
            $request->session()->flash('error', 'There was an error duplicating the program');
        }

        return redirect()->route('home');
    }
}
