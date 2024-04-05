<?php

namespace App\Http\Controllers;

use App\Models\custom_learning_activities;
use Illuminate\Http\Request;

class CustomLearningActivitiesController extends Controller
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
            'custom_activities' => 'required',
        ]);

        $custom_activity = $request->custom_activities;

        foreach ($custom_activity as $activity) {
            $la = new Custom_learning_activities;
            $la->custom_activities = $activity;

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
    public function show(custom_learning_activities $custom_learning_activities)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(custom_learning_activities $custom_learning_activities)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, custom_learning_activities $custom_learning_activities)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(custom_learning_activities $custom_learning_activities)
    {
        //
    }
}
