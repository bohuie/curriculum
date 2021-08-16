<?php

namespace App\Http\Controllers;

use App\Models\MappingScale;
use App\Models\MappingScaleProgram;
use Illuminate\Http\Request;

class MappingScaleController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            
            'title'=> 'required',
            'abbreviation'=> 'required',
            'description'=> 'required',
            'colour'=> 'required',

            ]);

        $ms = new MappingScale;
        $ms->title = $request->input('title');
        $ms->abbreviation = $request->input('abbreviation');
        $ms->description = $request->input('description');
        $ms->colour = $request->input('colour');
        $ms->save();

        
        $msp = new MappingScaleProgram;
        $msp->map_scale_id = $ms->map_scale_id;
        $msp->program_id = $request->input('program_id');
        
        if($msp->save()){
            $request->session()->flash('success', 'Mapping scale level added');
        }else{
            $request->session()->flash('error', 'There was an error adding the mapping scale level');
        }
        
        return redirect()->route('programWizard.step2', $request->input('program_id'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MappingScale  $mappingScale
     * @return \Illuminate\Http\Response
     */
    public function show(MappingScale $mappingScale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MappingScale  $mappingScale
     * @return \Illuminate\Http\Response
     */
    public function edit(MappingScale $mappingScale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MappingScale  $mappingScale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $map_scale_id)
    {
        //
        $this->validate($request, [
            
            'title'=> 'required',
            'abbreviation'=> 'required',
            'description'=> 'required',
            'colour'=> 'required',

            ]);

        $ms = MappingScale::where('map_scale_id', $map_scale_id)->first();
        $ms->title = $request->input('title');
        $ms->abbreviation = $request->input('abbreviation');
        $ms->description = $request->input('description');
        $ms->colour = $request->input('colour');
        
        if($ms->save()){
            $request->session()->flash('success', 'Mapping scale level updated');
        }else{
            $request->session()->flash('error', 'There was an error updating the mapping scale level');
        }
        
        return redirect()->route('programWizard.step2', $request->input('program_id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MappingScale  $mappingScale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $map_scale_id)
    {
        $map_scale = MappingScale::where('map_scale_id', $map_scale_id)->first();
        $mapping_scale_categories_id = $map_scale->mapping_scale_categories_id;

        if ($mapping_scale_categories_id != null) {
            // if the mapping scale is null delete from the mapping scale program
            $msp = MappingScaleProgram::where('program_id', $request->input('program_id'))->where('map_scale_id', $map_scale_id);
          
            if($msp->delete()){
                $request->session()->flash('success', 'Mapping scale level deleted');
            }else{
                $request->session()->flash('error', 'There was an error deleting the mapping scale level');
            }
        } else {
            // if the mapping scale does not belong to a category the delete from mapping scales
            $ms = MappingScale::where('map_scale_id', $map_scale_id)->first();
        
            if($ms->delete()){
                $request->session()->flash('success', 'Mapping scale level deleted');
            }else{
                $request->session()->flash('error', 'There was an error deleting the mapping scale level');
            }
        }
        
        return redirect()->route('programWizard.step2', $request->input('program_id'));
    }

    public function addDefaultMappingScale(Request $request) {
        $mapping_scale_categories_id = $request->input('mapping_scale_categories_id');
        
        // Return currently mapped scales for a program
        $msp = MappingScaleProgram::join('mapping_scales', 'mapping_scale_programs.map_scale_id', '=', 'mapping_scales.map_scale_id')->where('program_id',  $request->input('program_id') )->get();
        // loops through mapping scales
        foreach($msp as $m) {
            $ms = MappingScale::where('map_scale_id', $m->map_scale_id)->first();
            if ($m->mapping_scale_categories_id != null) {
                $ms->programs()->detach($request->input('program_id'));
            }
        }

        $mappingScales = MappingScale::where('mapping_scale_categories_id', $mapping_scale_categories_id)->get();
        // add mapping scales to mapping scale programs
        foreach ($mappingScales as $mappingScale) {
            $msp = new MappingScaleProgram;
            $msp->map_scale_id = $mappingScale->map_scale_id;
            $msp->program_id = $request->input('program_id');
            
            if($msp->save()){
                $request->session()->flash('success', 'Default mapping scale value set');
            }else{
                $request->session()->flash('error', 'There was an error deleting the plo category');
            }
        }

        return redirect()->route('programWizard.step2', $request->input('program_id'));
    }
}
