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
            'custom_methods'=> 'required',
            ]);

        $custom_methods = $request->custom_methods;

        foreach($custom_methods as $method) {
            $la = new Custom_assessment_methods;
            $la->custom_methods = $method;

            if($la->save()){
                $request->session()->flash('success', 'New teaching/learning activity added');
            }else{
                $request->session()->flash('error', 'There was an error adding the teaching/learning activity');
            }
        }
    }
}
