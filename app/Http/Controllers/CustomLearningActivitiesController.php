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
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'custom_activities'=> 'required',
            ]);

        $custom_activity = $request->custom_activities;

        foreach($custom_activity as $activity) {
            $la = new Custom_learning_activities;
            $la->custom_activities = $activity;

            if($la->save()){
                $request->session()->flash('success', 'New teaching/learning activity added');
            }else{
                $request->session()->flash('error', 'There was an error adding the teaching/learning activity');
            }
        }
    }
}
