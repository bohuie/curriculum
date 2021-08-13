@extends('layouts.app')

@section('content')

<div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('programs.wizard.header')

            <div class="card">

                <h3 class="card-header wizard">
                    Program Learning Outcomes (PLOs)

                    <div style="float: right;">
                        <button id="ploHelp" style="border: none; background: none; outline: none;" data-bs-toggle="modal" href="#guideModal">
                            <i class="bi bi-question-circle" style="color:#002145;"></i>
                        </button>
                    </div>
                    <div class="text-left">
                        @include('layouts.guide')
                    </div>
                </h3>

                <div class="card-body">

                    <h6 class="card-subtitle mb-4 text-center lh-lg">
                        Program-level learning outcomes (PLOs) are the knowledge, skills and attributes that students are expected to attain by the end of a program of study.
                        You can add, edit and delete program outcomes. <strong>It is recommended that a program has 6 - 8 PLOs max</strong>. You can also add program outcome categories to group outcomes.                    
                    </h6>


                    <div class="card m-3">
                        <h5 class="card-header wizard text-start">
                            Categories (Can be used to group PLOs)
                            <button type="button" class="btn bg-primary text-white btn-sm col-2 float-right" data-toggle="modal" data-target="#addCategoryModal">
                                <i class="bi bi-plus pr-2"></i>PLO Category
                            </button>
                        </h5>

                        <div class="card-body">
                            @if($ploCategories->count() < 1)
                                <div class="alert alert-warning wizard">
                                    <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no PLO categories set for this program yet.                    
                                </div>

                            @else
                                <table class="table table-light table-bordered" >
                                    <tr class="table-primary">
                                        <th>PLO Category</th>
                                        <th class="text-center w-25">Actions</th>
                                    </tr>

                                    @foreach($ploCategories as $category)
                                    <tr>
                                        <td>
                                            {{$category->plo_category}}
                                        </td>

                                        <td class="text-center align-middle">                                            
                                            <button type="button" style="width:60px;" class="btn btn-secondary btn-sm m-1" data-toggle="modal" data-target="#editCategoryModal{{$category->plo_category_id}}">
                                                Edit
                                            </button>

                                            <button style="width:60px;" type="button" class="btn btn-danger btn-sm btn btn-danger btn-sm m-1" data-toggle="modal" data-target="#deleteCategories{{$category->plo_category_id}}">
                                                Delete
                                            </button>

                                            <!-- Edit Category Modal -->
                                            <div class="modal fade" id="editCategoryModal{{$category->plo_category_id}}" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editCategoryModalLabel">Edit
                                                                    Program Learning Outcome Category</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <form method="POST"
                                                                action="{{ action('PLOCategoryController@update', $category->plo_category_id) }}">
                                                                @csrf
                                                                {{method_field('PUT')}}

                                                                <div class="modal-body">

                                                                    <div class="form-group row">
                                                                        <label for="category" class="col-md-4 col-form-label text-md-right">Category Name</label>

                                                                        <div class="col-md-8">
                                                                        <input id="category" type="text" class="form-control @error('category') is-invalid @enderror" name="category" value="{{$category->plo_category}}" autofocus>

                                                                            @error('category')
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>

                                                                    <input type="hidden" class="form-check-input" name="program_id" value={{$program->program_id}}>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary col-2 btn-sm" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary col-2 btn-sm">Save</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                            </div>
                                            <!-- End of Edit Category Modal  -->

                                            <!-- Delete Confirmation Modal -->
                                            <div class="modal fade" id="deleteCategories{{$category->plo_category_id}}" tabindex="-1" role="dialog" aria-labelledby="deleteCategories{{$category->plo_category_id}}" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Delete Confirmation</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                            Are you sure you want to delete {{$category->plo_category}}?
                                                            </div>

                                                            <form action="{{route('ploCategory.destroy', $category->plo_category_id)}}" method="POST">
                                                                @csrf
                                                                {{method_field('DELETE')}}
                                                                <input type="hidden" class="form-check-input " name="program_id"
                                                                    value={{$program->program_id}}>

                                                                <div class="modal-footer">
                                                                    <button style="width:60px" type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                                                    <button style="width:60px" type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                                </div>

                                                            </form>
                                                        </div>
                                                    </div>
                                            </div>
                                            <!-- End of Category Delete Confirmation Modal -->
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            @endif
                        </div>

                        <div class="card-footer p-3">

                            <!-- Add PLO category Modal -->
                            <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addCategoryModalLabel">Add a Program Learning Outcome Category</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <form method="POST" action="{{ action('PLOCategoryController@store') }}">
                                            @csrf

                                            <div class="modal-body">
                                                <div class="form-group row">
                                                    <label for="category" class="col-md-4 col-form-label text-md-right">Category Name</label>

                                                    <div class="col-md-8">
                                                        <input id="category" type="text" class="form-control @error('category') is-invalid @enderror" name="category" autofocus>

                                                        @error('category')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror

                                                    </div>
                                                </div>

                                                <input type="hidden" class="form-check-input" name="program_id" value={{$program->program_id}}>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary col-2 btn-sm" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary col-2 btn-sm">Add</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Add PLO category Modal  -->
                        </div>
                    </div>

                    <!-- Program Learning Outcomes -->
                    <div class="card m-3">
                        <h5 class="card-header wizard text-start">
                            Program Learning Outcomes (PLOs)
                            <button type="button" class="btn bg-primary text-white btn-sm col-2 float-right" data-toggle="modal" data-target="#addPLOModal">
                                <i class="bi bi-plus pr-2"></i>PLO
                            </button>
                        </h5>
                        <div class="card-body">

                            @if ( count($plos) < 1)
                                <div class="alert alert-warning wizard">
                                    <i class="bi bi-exclamation-circle-fill"></i>There are no program learning outcomes for this program.                  
                                </div>
                            @else
                                <table class="table table-light table-bordered table" style="width: 100%; margin: auto; table-layout:auto;">
                                    <tbody>
                                        <!--Categories for PLOs -->
                                        @foreach ($ploCategories as $plo)
                                            @if ($plo->plo_category != NULL)
                                                @if ($plo->plos->count() > 0)
                                                    <tr class="mt-5">
                                                        <th class="text-left" colspan="3" style="background-color: #ebebeb;">{{$plo->plo_category}}</th>
                                                    </tr>
                                                    <tr class="table-primary">
                                                        <th class="text-left" colspan="2">Program Learning Outcome</th>
                                                        <th class="text-center w-25" colspan="1">Actions</th>
                                                    </tr>
                                                @else
                                                    <tr class="mt-5">
                                                        <th class="text-left" colspan="3" style="background-color: #ebebeb;">{{$plo->plo_category}}</th>
                                                    </tr>
                                                    <tr class="alert alert-warning wizard">
                                                        <th colspan="3" style="background-color: #fff3cd;"><i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no program learning outcomes set for this PLO category. </th>              
                                                    </tr>
                                                @endif
                                            @endif
                                            <!-- Categorized PLOs -->
                                            @foreach($ploProgramCategories as $index => $ploCat)
                                                @if ($plo->plo_category_id == $ploCat->plo_category_id)
                                                    <tr>
                                                        <td colspan="2">
                                                            <span style="font-weight: bold;">{{$ploCat->plo_shortphrase}}</span><br>
                                                            {{$ploCat->pl_outcome}}
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" style="width:60px;" class="btn btn-secondary btn-sm m-1" data-toggle="modal" data-target="#editPLO{{$ploCat->pl_outcome_id}}">
                                                                Edit
                                                            </button>
                                                            <button style="width:60px;" type="button" class="btn btn-danger btn-sm btn btn-danger btn-sm m-1" data-toggle="modal" data-target="#deletePLO{{$ploCat->pl_outcome_id}}">
                                                                Delete
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endif
                                                <!-- Delete PLO Confirmation Model -->
                                                <div class="modal fade" id="deletePLO{{$ploCat->pl_outcome_id}}" tabindex="-1" role="dialog" aria-labelledby="deletePLO{{$ploCat->pl_outcome_id}}" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Delete Confirmation</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                Are you sure you want to delete {{$ploCat->plo_shortphrase}}?
                                                            </div>

                                                            <form action="{{route('plo.destroy', $ploCat->pl_outcome_id)}}" method="POST">
                                                                @csrf
                                                                {{method_field('DELETE')}}
                                                                <input type="hidden" class="form-check-input " name="program_id" value={{$program->program_id}}>

                                                                <div class="modal-footer">
                                                                    <button style="width:60px" type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                                                    <button style="width:60px" type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                                </div>

                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End of Delete PLO Confirmation Modal -->

                                                <!-- Edit PLO Modal -->
                                                <div class="modal fade" id="editPLO{{$ploCat->pl_outcome_id}}" tabindex="-1" role="dialog" aria-labelledby="editPLO{{$ploCat->pl_outcome_id}}" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editPLOModalLabel">Edit Program Learning Outcome (PLO)</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form method="POST" action="{{ action('ProgramLearningOutcomeController@update', $ploCat->pl_outcome_id) }}">
                                                                @csrf
                                                                {{method_field('PUT')}}
                                                                <div class="modal-body">
                                                                    <div class="form-group row">
                                                                        <label for="plo" class="col-md-4 col-form-label text-md-right">Program Learning Outcome (PLO)</label>
                                                                        <div class="col-md-8">
                                                                            <textarea id="plo" class="form-control" @error('plo') is-invalid @enderror rows="3" name="plo" required autofocus>{{$ploCat->pl_outcome}}</textarea>
                                                                            @error('plo')
                                                                                <span class="invalid-feedback" role="alert">
                                                                                    <strong>{{ $message }}</strong>
                                                                                </span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="title" class="col-md-4 col-form-label text-md-right">Short Phrase</label>
                                                                        <div class="col-md-8">
                                                                            <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{$ploCat->plo_shortphrase}}" autofocus maxlength="50">
                                                                            @error('title')
                                                                                <span class="invalid-feedback" role="alert">
                                                                                    <strong>{{ $message }}</strong>
                                                                                </span>
                                                                            @enderror
                                                                            <small class="form-text text-muted">
                                                                                Having a short phrase helps with data visualization at the end of this process <b>(4 words max)</b>.
                                                                            </small>
                                                                        </div>
                                                                    </div>
                                                                    @if(count($ploCategories) > 0)
                                                                        <div class="form-group row">
                                                                            <label for="category" class="col-md-4 col-form-label text-md-right">PLO Category</label>
                    
                                                                            <div class="col-md-8">
                                                                                
                                                                                <select class="custom-select" name="category" id="category" required autofocus>
                                                                                    <option value="{{$ploCat->plo_category_id}}" selected>{{$ploCat->plo_category}}</option>
                                                                                    @foreach($ploCategories as $c)
                                                                                        @if ($c->plo_category_id != $ploCat->plo_category_id)
                                                                                            <option value="{{$c->plo_category_id}}">{{$c->plo_category}}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                    <option value="">None</option>
                                                                                </select>
                    
                                                                                @error('category')
                                                                                    <span class="invalid-feedback" role="alert">
                                                                                        <strong>{{ $message }}</strong>
                                                                                    </span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    <input type="hidden" class="form-check-input" name="program_id" value={{$program->program_id}}>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary col-2 btn-sm" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary col-2 btn-sm">Save</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End of Edit PLO Modal -->
                                            @endforeach
                                            <tr>
                                                <th  colspan="3" class="" style="border-color: #FFF; background-color:#FFF;"></th>
                                            </tr>
                                        @endforeach
                                        <!--UnCategorized PLOs -->
                                        @if($hasUncategorized)
                                            <tr>
                                                <th class="text-left" colspan="3" style="background-color: #ebebeb;">Uncategorized PLOs</th>
                                            </tr>
                                            <tr class="table-primary">
                                                <th class="text-left" colspan="2">Program Learning Outcome</th>
                                                <th class="text-center" colspan="1">Actions</th>
                                            </tr>
                                        @endif
                                        @foreach($unCategorizedPLOS as $unCatIndex => $unCatplo)
                                            <tr>
                                                <td colspan="2">
                                                    <span style="font-weight: bold;">{{$unCatplo->plo_shortphrase}}</span><br>
                                                    {{$unCatplo->pl_outcome}}
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" style="width:60px;" class="btn btn-secondary btn-sm m-1" data-toggle="modal" data-target="#editPLOunCat{{$unCatplo->pl_outcome_id}}">
                                                        Edit
                                                    </button>
                                                    <button style="width:60px;" type="button" class="btn btn-danger btn-sm btn btn-danger btn-sm m-1" data-toggle="modal" data-target="#deletePLO{{$unCatplo->pl_outcome_id}}">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                            <!-- Delete PLO Confirmation Model -->
                                            <div class="modal fade" id="deletePLO{{$unCatplo->pl_outcome_id}}" tabindex="-1" role="dialog" aria-labelledby="deletePLO{{$unCatplo->pl_outcome_id}}" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Delete Confirmation</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to delete {{$unCatplo->plo_shortphrase}}?
                                                        </div>
                                                        <form action="{{route('plo.destroy', $unCatplo->pl_outcome_id)}}" method="POST">
                                                            @csrf
                                                            {{method_field('DELETE')}}
                                                            <input type="hidden" class="form-check-input " name="program_id" value={{$program->program_id}}>
                                                            <div class="modal-footer">
                                                                <button style="width:60px" type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                                                <button style="width:60px" type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End of Delete PLO Confirmation Modal -->

                                            <!-- Edit PLO Modal -->
                                            <div class="modal fade" id="editPLOunCat{{$unCatplo->pl_outcome_id}}" tabindex="-1" role="dialog" aria-labelledby="editPLOunCat{{$unCatplo->pl_outcome_id}}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editPLOModalLabel">Edit Program Learning Outcome (PLO)</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form method="POST" action="{{ action('ProgramLearningOutcomeController@update', $unCatplo->pl_outcome_id) }}">
                                                            @csrf
                                                            {{method_field('PUT')}}
                                                            <div class="modal-body">
                                                                <div class="form-group row">
                                                                    <label for="plo" class="col-md-4 col-form-label text-md-right">Program Learning Outcome (PLO)</label>
                                                                    <div class="col-md-8">
                                                                        <textarea id="plo" class="form-control" @error('plo') is-invalid @enderror rows="3" name="plo" required autofocus>{{$unCatplo->pl_outcome}}</textarea>
                                                                        @error('plo')
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="title" class="col-md-4 col-form-label text-md-right">Short Phrase</label>
                                                                    <div class="col-md-8">
                                                                        <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{$unCatplo->plo_shortphrase}}" autofocus maxlength="50">
                                                                        @error('title')
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                        <small class="form-text text-muted">
                                                                            Having a short phrase helps with data visualization at the end of this process <b>(4 words max)</b>.
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                                @if(count($ploCategories) > 0)
                                                                    <div class="form-group row">
                                                                        <label for="category" class="col-md-4 col-form-label text-md-right">PLO Category</label>
                
                                                                        <div class="col-md-8">
                                                                            
                                                                            <select class="custom-select" name="category" id="category" required autofocus>
                                                                                <option value="" selected>None</option>
                                                                                @foreach($ploCategories as $c)
                                                                                    <option value="{{$c->plo_category_id}}">{{$c->plo_category}}</option>
                                                                                @endforeach
                                                                            </select>
                
                                                                            @error('category')
                                                                                <span class="invalid-feedback" role="alert">
                                                                                    <strong>{{ $message }}</strong>
                                                                                </span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                <input type="hidden" class="form-check-input" name="program_id" value={{$program->program_id}}>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary col-2 btn-sm" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary col-2 btn-sm">Save</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End of Edit PLO Modal -->
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                        <div class="card-footer p-3">
                            <!-- Add PLO Modal -->
                            <div class="modal fade" id="addPLOModal" tabindex="-1" role="dialog"
                                aria-labelledby="addPLOModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addPLOModalLabel">Add a Program Learning Outcome</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <form method="POST" action="{{ action('ProgramLearningOutcomeController@store') }}">
                                            @csrf

                                            <div class="modal-body">

                                                <div class="form-group row">
                                                    <label for="plo" class="col-md-4 col-form-label text-md-right">Program Learning Outcome</label>

                                                    <div class="col-md-8">
                                                        <textarea id="plo" class="form-control" @error('plo') is-invalid @enderror rows="3" name="plo" required autofocus></textarea>

                                                        @error('plo')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="title" class="col-md-4 col-form-label text-md-right">Short Phrase</label>

                                                    <div class="col-md-8">
                                                        <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" autofocus
                                                        placeholder="E.g. Citing Sources, Scientific Method" maxlength="50">

                                                        @error('title')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror

                                                        <small class="form-text text-muted">
                                                            Having a short phrase helps with data visualization at the end of this process <b>(4 words max)</b>.
                                                        </small>
                                                    </div>
                                                </div>
                                                
                                                @if(count($ploCategories)>0)
                                                    <div class="form-group row">
                                                        <label for="category" class="col-md-4 col-form-label text-md-right">PLO Category</label>

                                                        <div class="col-md-8">

                                                            <select class="custom-select" name="category" id="category" required autofocus>
                                                                <option value="" disabled selected>Choose...</option>
                                                                @foreach($ploCategories as $c)
                                                                    <option value="{{$c->plo_category_id}}">{{$c->plo_category}}</option>
                                                                @endforeach
                                                                <option value="">None</option>
                                                            </select>

                                                            @error('category')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                <input type="hidden" class="form-check-input" name="program_id"
                                                    value={{$program->program_id}}>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary col-2 btn-sm"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary col-2 btn-sm">Add</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Add PLO Modal -->
                        </div>
                    </div>
                    <!-- End Program Learning Outcomes -->

                </div>
                <div class="card-footer">
                    <div class="card-body mb-4">
                        <a href="{{route('programWizard.step2', $program->program_id)}}">
                            <button class="btn btn-sm btn-primary col-3 float-right">Mapping Scales<i class="bi bi-arrow-right mr-2"></i></button>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $("form").submit(function () {
            // prevent duplicate form submissions
            $(this).find(":submit").attr('disabled', 'disabled');
            $(this).find(":submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

        });
    });
</script>
@endsection
