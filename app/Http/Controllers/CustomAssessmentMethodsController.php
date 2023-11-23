<?php

namespace App\Http\Controllers;

use App\Models\custom_assessment_methods;
use Illuminate\Http\Request;

class CustomAssessmentMethodsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        $this->validate($request, [
            'custom_methods' => 'required',
        ]);

        $custom_methods = $request->custom_methods;

        foreach ($custom_methods as $method) {
            $la = new Custom_assessment_methods;
            $la->custom_methods = $method;

            if ($la->save()) {
                $request->session()->flash('success', 'New teaching/learning activity added');
            } else {
                $request->session()->flash('error', 'There was an error adding the teaching/learning activity');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(custom_assessment_methods $custom_assessment_methods)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(custom_assessment_methods $custom_assessment_methods)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, custom_assessment_methods $custom_assessment_methods)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(custom_assessment_methods $custom_assessment_methods)
    {
        //
    }
}
