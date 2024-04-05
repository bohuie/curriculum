<?php

namespace App\Http\Controllers;

use App\Models\PLOCategory;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class PLOCategoryController extends Controller
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

        // try update PLO categories
        try {
            $programId = $request->input('program_id');
            // get this program
            $program = Program::find($programId);
            // get the current plo categories
            $currentPLOCategories = $request->input('current_plo_categories');
            // get the new plo categories
            $newPLOCategories = $request->input('new_plo_categories');
            // case: delete all program learning outcome categories
            if (! $currentPLOCategories && ! $newPLOCategories) {
                $program->ploCategories()->delete();
            }
            // get the saved PLO categories for this program
            $ploCategories = $program->ploCategories;
            // update current plo categories
            foreach ($ploCategories as $ploCategory) {
                if (array_key_exists($ploCategory->plo_category_id, $currentPLOCategories)) {
                    // save and update plo category
                    $ploCategory->plo_category = $currentPLOCategories[$ploCategory->plo_category_id];
                    $ploCategory->save();
                } else {
                    // remove plo category from program
                    // TODO: update plo category of plos
                    $ploCategory->delete();
                }
            }
            // add new plo categories
            if ($newPLOCategories) {
                foreach ($newPLOCategories as $index => $newPLOCategory) {
                    $newPLOCat = new PLOCategory;
                    $newPLOCat->plo_category = $newPLOCategory;
                    $newPLOCat->program_id = $programId;
                    $newPLOCat->save();
                }
            }
            // update courses 'updated_at' field
            $program->touch();

            // get users name for last_modified_user
            $user = User::find(Auth::id());
            $program->last_modified_user = $user->name;
            $program->save();
            $request->session()->flash('success', 'Your PLO categories were updated successfully!');

        } catch (Throwable $exception) {
            $request->session()->flash('error', 'There was an error updating your PLO Categories');
        } finally {
            return redirect()->route('programWizard.step1', $programId);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(PLOCategory $pLOCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(PLOCategory $pLOCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\PLOCategory  $pLOCategory
     */
    public function update(Request $request, $plo_category_id): RedirectResponse
    {
        //
        $this->validate($request, [

            'category' => 'required',
        ]);

        $c = PLOCategory::where('plo_category_id', $plo_category_id)->first();
        $c->plo_category = $request->input('category');

        $program = Program::find($request->input('program_id'));
        // get users name for last_modified_user
        $user = User::find(Auth::id());
        $program->last_modified_user = $user->name;
        $program->save();

        if ($c->save()) {
            // update courses 'updated_at' field
            $program = Program::find($request->input('program_id'));
            $program->touch();

            $request->session()->flash('success', 'Plo cateogry updated');
        } else {
            $request->session()->flash('error', 'There was an error updating the plo category');
        }

        return redirect()->route('programWizard.step1', $request->input('program_id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PLOCategory  $pLOCategory
     */
    public function destroy(Request $request, $plo_category_id): RedirectResponse
    {
        //
        $c = PLOCategory::where('plo_category_id', $plo_category_id)->first();

        $program = Program::find($request->input('program_id'));
        // get users name for last_modified_user
        $user = User::find(Auth::id());
        $program->last_modified_user = $user->name;
        $program->save();

        if ($c->delete()) {
            // update courses 'updated_at' field
            $program = Program::find($request->input('program_id'));
            $program->touch();

            $request->session()->flash('success', 'Plo cateogry deleted');
        } else {
            $request->session()->flash('error', 'There was an error deleting the plo category');
        }

        return redirect()->route('programWizard.step1', $request->input('program_id'));
    }
}
