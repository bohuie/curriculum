<?php

namespace App\Http\Controllers;

use App\Helpers\ReadOutcomesFilter;
use App\Models\Course;
use App\Models\LearningOutcome;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Throwable;

class LearningOutcomeController extends Controller
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
        // try to update CLOs
        try {
            $courseId = $request->input('course_id');
            $currentCLOs = $request->input('current_l_outcome');
            $currentShortPhrases = $request->input('current_l_outcome_short_phrase');
            // $newCLOs = array_reverse($request->input('new_l_outcomes'));
            // $newShortPhrases = array_reverse($request->input('new_short_phrases'));
            $newCLOs = $request->input('new_l_outcomes');
            $newShortPhrases = $request->input('new_short_phrases');

            // case: delete all course learning outcomes
            if (! $currentCLOs && ! $newCLOs) {
                Course::find($courseId)->learningOutcomes()->delete();
            }
            // get the course
            $course = Course::find($courseId);
            // get the saved CLOs for this course
            $clos = $course->learningOutcomes;
            // check if clos have been reordered
            $hasBeenReordered = false;
            foreach ($clos as $clo) {
                if ($clo->pos_in_alignment != 0) {
                    $hasBeenReordered = true;
                    break;
                }
            }
            // update current clos
            foreach ($clos as $clo) {
                if (array_key_exists($clo->l_outcome_id, $currentCLOs)) {
                    // save Clo, l_outcome and ShortPhrase
                    $clo->l_outcome = $currentCLOs[$clo->l_outcome_id];
                    $clo->clo_shortphrase = $currentShortPhrases[$clo->l_outcome_id];
                    $clo->save();
                } else {
                    // remove clo from course
                    $clo->delete();
                }
            }
            if ($newCLOs) {
                foreach ($newCLOs as $index => $newCLO) {
                    $newLearningOutcome = new LearningOutcome;
                    $newLearningOutcome->l_outcome = $newCLO;
                    $newLearningOutcome->clo_shortphrase = $newShortPhrases[$index];
                    $newLearningOutcome->course_id = $courseId;
                    // update pos_in_alignment if the other clos for the course have non zero values for pos_in_alignment
                    if ($hasBeenReordered) {
                        $newLearningOutcome->pos_in_alignment = $clos->count() + $index + 1;
                    }
                    $newLearningOutcome->save();
                }
            }
            // update courses 'updated_at' field
            $course->touch();

            // get users name for last_modified_user
            $user = User::find(Auth::id());
            $course->last_modified_user = $user->name;
            $course->save();

            $request->session()->flash('success', 'Your course learning outcomes were updated successfully!');
        } catch (Throwable $exception) {
            $request->session()->flash('error', 'There was an error updating your course learning outcomes');
        } finally {
            return redirect()->route('courseWizard.step1', $request->input('course_id'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(LearningOutcome $learningOutcome)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LearningOutcome  $learningOutcome
     * @return \Illuminate\Http\Response
     */
    public function edit($l_outcome_id)
    {
        //
        $lo = LearningOutcome::where('l_outcome_id', $l_outcome_id)->first();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\LearningOutcome  $learningOutcome
     */
    public function update(Request $request, $l_outcome_id): RedirectResponse
    {
        //
        $this->validate($request, [
            'l_outcome' => 'required',
        ]);

        $lo = LearningOutcome::where('l_outcome_id', $l_outcome_id)->first();
        $lo->l_outcome = $request->input('l_outcome');
        $lo->clo_shortphrase = $request->input('title');

        if ($lo->save()) {
            $request->session()->flash('success', 'Course learning outcome updated');
        } else {
            $request->session()->flash('error', 'There was an error updating the course learning outcome');
        }

        return redirect()->route('courseWizard.step1', $request->input('course_id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LearningOutcome  $learningOutcome
     */
    public function destroy(Request $request, $l_outcome_id): RedirectResponse
    {
        //
        $lo = LearningOutcome::where('l_outcome_id', $l_outcome_id)->first();

        if ($lo->delete()) {
            // update courses 'updated_at' field
            $course = Course::find($request->input('course_id'));
            $course->touch();

            // get users name for last_modified_user
            $user = User::find(Auth::id());
            $course->last_modified_user = $user->name;
            $course->save();

            $request->session()->flash('success', 'Course learning outcome has been deleted');
        } else {
            $request->session()->flash('error', 'There was an error deleting the course learning outcome');
        }

        return redirect()->route('courseWizard.step1', $request->input('course_id'));
    }

    /* Import clos from a file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        // $this->validate($request, [
        //     'upload'=> 'required|mimes:csv,xlsx,xlx,xls|max:2048',
        // ]);
        $courseId = $request->input('course_id');
        $file = $request->file('upload');
        $clientFileName = $file->getClientOriginalName();
        $path = $file->storeAs(
            'temporary', $clientFileName
        );

        $absolutePath = storage_path('app'.DIRECTORY_SEPARATOR.'temporary'.DIRECTORY_SEPARATOR.$clientFileName);

        /**  Create a new reader of the type defined by $clientFileName extension  **/
        $reader = IOFactory::createReaderForFile($absolutePath);
        /**  Advise the reader that we only want to load cell data, not cell formatting info  **/
        $reader->setReadDataOnly(true);
        // a read filter can be used to set rules on which cells should be read from a file
        $reader->setReadFilter(new ReadOutcomesFilter(0, 30, ['A', 'B']));
        /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($absolutePath);
        $worksheet = $spreadsheet->getActiveSheet();

        foreach ($worksheet->getRowIterator() as $rowIndex => $row) {
            // skip header row
            if ($rowIndex == 1) {
                continue;
            }
            // get cell iterator
            $cellIterator = $row->getCellIterator();
            // loop through cells only when value is set
            $cellIterator->setIterateOnlyExistingCells(true);
            $learningOutcome = new LearningOutcome;
            // set clo course id
            $learningOutcome->course_id = $courseId;

            foreach ($cellIterator as $cell) {
                // get column index of cell
                $cellColumnIndex = Coordinate::columnIndexFromString($cell->getColumn());
                switch ($cellColumnIndex) {
                    case 1:
                        // set CLO value
                        $cloValue = $cell->getValue();
                        if ($cloValue) {
                            $learningOutcome->l_outcome = $cloValue;
                        }
                        break;
                    case 2:
                        // set CLO Short Phrase
                        $cloShortPhrase = $cell->getValue();
                        if ($cloShortPhrase) {
                            $learningOutcome->clo_shortphrase = $cloShortPhrase;
                        }
                        break;
                    default:
                        break;
                }
            }
            // save the new plo
            if ($learningOutcome->l_outcome) {
                $learningOutcome->save();
            }
        }
        // delete file on server
        Storage::delete($path);
        // before clearing the spreadsheet from memory, "break" the cyclic references to worksheets.
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        // return
        return redirect()->back();

    }
}
