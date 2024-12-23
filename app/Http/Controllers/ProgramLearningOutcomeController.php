<?php

namespace App\Http\Controllers;

// composer generates this autoload.php file so you can start using the classes in dependencies without any extra work

use App\Helpers\ReadOutcomesFilter;
use App\Models\CourseProgram;
use App\Models\PLOCategory;
use App\Models\Program;
use App\Models\ProgramLearningOutcome;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Throwable;

class ProgramLearningOutcomeController extends Controller
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
        // validate request data
        $this->validate($request, [
            'program_id' => 'required',
        ]);

        try {
            $programId = $request->input('program_id');
            // get this program
            $program = Program::find($programId);
            // get the current plos, their shortphrases and categories
            $currentPLOs = $request->input('current_pl_outcome');
            $currentPLOShortphrases = $request->input('current_pl_outcome_short_phrase');
            $currentPLOCategories = $request->input('current_plo_category');
            // get the new plos, their shortphrases and their categories
            $newPLOs = $request->input('new_pl_outcome');
            $newPLOShortphrases = $request->input('new_pl_outcome_short_phrase');
            $newPLOCategories = $request->input('new_plo_category');
            // case: delete all program learning outcomes
            if (! $currentPLOs && ! $newPLOs) {
                $program->programLearningOutcomes()->delete();
            }
            // get the saved plos for this program
            $plos = $program->programLearningOutcomes;
            // update current plos
            foreach ($plos as $plo) {
                if (array_key_exists($plo->pl_outcome_id, $currentPLOs)) {
                    // save and update plo
                    $plo->pl_outcome = $currentPLOs[$plo->pl_outcome_id];
                    $plo->plo_shortphrase = $currentPLOShortphrases[$plo->pl_outcome_id];
                    $plo->plo_category_id = $currentPLOCategories[$plo->pl_outcome_id];
                    $plo->save();
                } else {
                    // remove plo from program
                    $plo->delete();
                }
            }
            // add new plos
            if ($newPLOs) {
                foreach ($newPLOs as $index => $plo) {
                    $newPLO = new ProgramLearningOutcome;
                    $newPLO->pl_outcome = $plo;
                    $newPLO->plo_shortphrase = $newPLOShortphrases[$index];
                    $newPLO->plo_category_id = $newPLOCategories[$index];
                    $newPLO->program_id = $programId;
                    $newPLO->save();
                }
                // new unmapped plos added, update map_status
                CourseProgram::where('program_id', $request->input('program_id'))->update(['map_status' => 0]);
            }
            // update which user last modified this program
            $user = User::find(Auth::id());
            $program->last_modified_user = $user->name;
            $program->save();
            $request->session()->flash('success', 'Your program learning outcomes were updated successfully!');
        } catch (Throwable $exception) {
            $message = 'There was an error updating your program learning outcomes';
            Log::error($message.' ...\n');
            Log::error('Code - '.$exception->getCode());
            Log::error('File - '.$exception->getFile());
            Log::error('Line - '.$exception->getLine());
            Log::error($exception->getMessage());
            $request->session()->flash('error', $message);
        } finally {
            return redirect()->route('programWizard.step1', $request->input('program_id'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ProgramLearningOutcome $programLearningOutcome)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ProgramLearningOutcome $programLearningOutcome)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\ProgramLearningOutcome  $programLearningOutcome
     */
    public function update(Request $request, $programLearningOutcomeID): RedirectResponse
    {
        // validate request input
        $this->validate($request, [
            'program_id' => 'required',
            'plo' => 'required',
        ]);

        $plo = ProgramLearningOutcome::where('pl_outcome_id', $programLearningOutcomeID)->first();
        $plo->pl_outcome = $request->input('plo');
        $plo->plo_shortphrase = $request->input('title');
        $plo->plo_category_id = $request->input('category');

        if ($plo->save()) {
            // update courses 'updated_at' field
            $program = Program::find($request->input('program_id'));
            $program->touch();
            // get users name for last_modified_user
            $user = User::find(Auth::id());
            $program->last_modified_user = $user->name;
            $program->save();

            $request->session()->flash('success', 'Program learning outcome updated');
        } else {
            $request->session()->flash('error', 'There was an error updating the program learning outcome');
        }

        return redirect()->route('programWizard.step1', $request->input('program_id'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $programId): RedirectResponse
    {
        //
        $plo = ProgramLearningOutcome::where('pl_outcome_id', $programId);

        $program = Program::find($request->input('program_id'));
        // get users name for last_modified_user
        $user = User::find(Auth::id());
        $program->last_modified_user = $user->name;
        $program->save();

        if ($plo->delete()) {
            // update courses 'updated_at' field
            $program = Program::find($request->input('program_id'));
            $program->touch();

            $request->session()->flash('success', 'Program learning outcome has been deleted');
        } else {
            $request->session()->flash('error', 'There was an error deleting the program learning outcome');
        }

        return redirect()->route('programWizard.step1', $request->input('program_id'));
    }

    public function import(Request $request)
    {
        // $this->validate($request, [
        //     'upload'=> 'required|mimes:csv,xlsx,xlx,xls|max:2048',
        // ]);
        $programId = $request->input('program_id');
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
        $worksheets = $spreadsheet->getAllSheets();
        foreach ($worksheets as $worksheet) {
            // create a program learning outcome category
            $worksheetTitle = $worksheet->getTitle();
            Log::debug('Add PLO category: '.$worksheetTitle);
            $ploCategory = PLOCategory::create([
                'plo_category' => $worksheetTitle,
                'program_id' => $programId,
            ]);
            foreach ($worksheet->getRowIterator() as $rowIndex => $row) {
                // skip header row
                if ($rowIndex == 1) {
                    continue;
                }
                // get cell iterator
                $cellIterator = $row->getCellIterator();
                // loop through cells only when value is set
                $cellIterator->setIterateOnlyExistingCells(true);
                $plo = new ProgramLearningOutcome;
                // set plo program id
                $plo->program_id = $programId;
                // set plo category
                $plo->plo_category_id = $ploCategory->plo_category_id;

                foreach ($cellIterator as $cell) {
                    // get column index of cell
                    $cellColumnIndex = Coordinate::columnIndexFromString($cell->getColumn());
                    switch ($cellColumnIndex) {
                        case 1:
                            // set PLO value
                            $ploValue = $cell->getValue();
                            if ($ploValue) {
                                $plo->pl_outcome = $ploValue;
                            }
                            break;
                        case 2:
                            // set PLO Short Phrase
                            $ploShortPhrase = $cell->getValue();
                            if ($ploShortPhrase) {
                                $plo->plo_shortphrase = $ploShortPhrase;
                            }
                            break;
                        default:
                            break;
                    }
                }
                // save the new plo
                if ($plo->pl_outcome) {
                    $plo->save();
                }
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
