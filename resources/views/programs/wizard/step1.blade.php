@extends('layouts.app')

@section('content')

<!-- start of add plo category modal -->
<div id="addPLOCategoryModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="addPLOCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content "  >
            <div class="modal-header">
                <h5 class="modal-title" id="addPLOCategoryModalLabel"><i class="bi bi-pencil-fill btn-icon mr-2"></i> PLO Categories</h5>
            </div>

            <div class="modal-body">
                <form id="addPLOCategoryForm" class="needs-validation" novalidate>
                    <div class="row justify-content-between align-items-end m-2">
                        <div class="col-10">
                            <label for="PLOCategory" class="form-label fs-6">
                                <b>PLO Category</b>
                                <i class="bi bi-info-circle-fill" data-bs-toggle="tooltip" data-bs-placement="right" title="Program learning outcome (PLO) categories can be used to group PLOs"></i>
                            </label>
                            <input id="PLOCategory" class="form-control" required>
                            <div class="invalid-tooltip">Please provide a PLO category.</div>                                            
                        </div>
                        <div class="col-2">
                            <button id="addPLOCategoryBtn" type="submit" class="btn btn-primary col">Add</button>
                        </div>
                    </div>
                </form>
                <div class="row justify-content-center">
                    <div class="col-8">
                        <hr>
                    </div>
                </div> 
                
                <div class="row m-1">
                    <table id="addPLOCategoryTbl" class="table table-light table-borderless">
                        <thead>
                            <tr class="table-primary">
                                <th>PLO Category</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($ploCategories as $index => $category)
                            <tr>
                                <td>
                                    <input id="category{{$category->plo_category_id}}" type="text" class="form-control" name="current_plo_categories[{{$category->plo_category_id}}]" value = "{{$category->plo_category}}" form="savePLOCategoryChanges" required spellcheck="true" style="white-space: pre">
                                </td>
                                <td class="text-center">
                                    <i class="bi bi-x-circle-fill text-danger fs-4 btn" onclick="deleteRow(this)"></i>
                                </td>
                            </tr>
                        @endforeach                                               
                        </tbody>
                    </table>                                    
                </div>
            </div>
            <form method="POST" id="savePLOCategoryChanges" action="{{ action([\App\Http\Controllers\PLOCategoryController::class, 'store']) }}">
                @csrf
                <div class="modal-footer">
                    <input type="hidden" name="program_id" value="{{$program->program_id}}" form="savePLOCategoryChanges">
                    <button id="cancelPLOCategoryForm" type="button" class="btn btn-secondary col-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn col-3" >Save Changes</button>
                </div>
            </form>    
        </div>
    </div>
</div>
<!-- End of add PLO Category modal -->

<!-- Add PLO Modal -->
<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" id="addPLOModal" aria-labelledby="addPLOModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered" role="document" style="width:80%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPLOModalLabel"><i class="bi bi-pencil-fill btn-icon mr-2"></i> Program Learning Outcomes (PLOs)
                </h5>
            </div>

            <div class="modal-body text-left">
                <form id="addPLOForm" class="needs-validation" novalidate>
                    <div class="form-group row align-items-end">
                        <div class="col-6">
                            <label for="pl_outcome" class="form-label fs-6">
                                <span class="requiredField">* </span>
                                <b>Program Learning Outcome (PLO)</b>
                                <div><small class="form-text text-muted" style="font-size:12px"><a href="https://tips.uark.edu/using-blooms-taxonomy/" target="_blank" rel="noopener noreferrer"><b><i class="bi bi-box-arrow-up-right"></i> Click here</b></a> for tips to write effective PLOs.</small></div>
                            </label>
                
                            <textarea id="pl_outcome" rows="3" class="form-control" name="pl_outcome" required autofocus placeholder="E.g. Develop..." style="resize:none"></textarea>
                            <div class="invalid-tooltip">
                                You must input a program learning outcome or competency.
                            </div>
                        </div>
                        <div class="col-4 ">
                            <label for="ploShortphrase" class="form-label fs-6">
                                <b>Short Phrase</b>
                                <div><small class="form-text text-muted" style="font-size:12px"><b><i class="bi bi-exclamation-circle-fill text-warning" data-bs-toggle="tooltip" data-bs-placement="left" title="Having a short phrase helps with visualizing your program overview at the end of the mapping process"></i> 50 character limit.</b></small></div>
                            </label>
                            <input type="text" id="ploShortphrase" class="form-control mb-1" name="title" autofocus placeholder="E.g. Experimental Design..." maxlength="50" style="resize:none">
                            <div class="form-floating">
                                <select class="form-select custom-select" style="font-size:12px;" name="category" id="ploCategory">
                                    <option value="" selected>None</option>
                                    @foreach($ploCategories as $c)
                                        <option value="{{$c->plo_category_id}}">{{$c->plo_category}}</option>
                                    @endforeach
                                </select>
                                <label for="ploCategory">PLO Category</label>
                            </div>
                        </div>
                        <div class="col-2">
                            <button id="addPLOBtn" type="submit" class="btn btn-lg btn-primary col-10 fw-bold" style="height:3.65rem"><i class="bi bi-plus"></i> Add</button>
                        </div>
                    </div>
                </form>
                <div class="row justify-content-center">
                    <div class="col-8">
                        <hr>
                    </div>
                </div>                

                <div class="row m-1">
                    <table id="addPLOTbl" class="table table-light table-borderless">
                        <thead>
                            <tr class="table-primary">
                                <th class="text-left" width="40%">Program Learning Outcomes or Competencies</th>
                                <th class="text-left" width="20%">Short Phrase</th>
                                <th class="text-left" width="30%">Category</th>
                                <th class="text-center" width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($program->programLearningOutcomes as $index => $pl_outcome)
                            <tr>
                                <td>
                                    <textarea name="current_pl_outcome[{{$pl_outcome->pl_outcome_id}}]" value="{{$pl_outcome->pl_outcome}}" id="pl_outcome{{$pl_outcome->pl_outcome_id}}" class="form-control @error('pl_outcome') is-invalid @enderror" form="savePLOChanges" required style="resize:none">{{$pl_outcome->pl_outcome}}</textarea>
                                </td>
                                <td>
                                    <textarea type="text" name="current_pl_outcome_short_phrase[{{$pl_outcome->pl_outcome_id}}]" id="pl_outcome_short_phrase{{$pl_outcome->pl_outcome_id}}" class="form-control @error('clo_shortphrase') is-invalid @enderror"  form="savePLOChanges" maxlength="50" style="resize:none">{{$pl_outcome->plo_shortphrase}}</textarea>
                                </td>
                                <td>                  
                                    <select class="form-select form-control" name="current_plo_category[{{$pl_outcome->pl_outcome_id}}]" style="height:4.7rem" id="plo_category{{$pl_outcome->pl_outcome_id}}" form="savePLOChanges">
                                        @if ($pl_outcome->category)
                                            <option value="{{$pl_outcome->category->plo_category_id}}" selected>{{$pl_outcome->category->plo_category}}</option>
                                            @foreach($ploCategories as $ploCat)
                                                @if ($ploCat->plo_category_id != $pl_outcome->category->plo_category_id)
                                                    <option value="{{$ploCat->plo_category_id}}">{{$ploCat->plo_category}}</option>
                                                @endif
                                            @endforeach
                                            <option value="">None</option>
                                        @else 
                                            <option value="" selected>None</option>
                                            @foreach ($ploCategories as $ploCat)
                                                <option value="{{$ploCat->plo_category_id}}">{{$ploCat->plo_category}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td class="text-center align-middler">
                                    <i class="bi bi-x-circle-fill text-danger fs-4 btn" onclick="deleteRow(this)"></i>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <form method="POST" id="savePLOChanges" action="{{ action([\App\Http\Controllers\ProgramLearningOutcomeController::class, 'store']) }}">
                @csrf
                <div class="modal-footer">
                    <input type="hidden" name="program_id" value="{{$program->program_id}}" form="savePLOChanges">
                    <button id="cancelAddPLOForm" type="button" class="btn btn-secondary col-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success col-3">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End of Add PLO Modal -->


<div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('programs.wizard.header')

            <div class="card">

                <h3 class="card-header wizard">
                    Program Learning Outcomes

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
                    <div class="alert alert-primary d-flex align-items-center ml-3 mr-3" role="alert" style="text-align:justify">
                        <i class="bi bi-info-circle-fill pr-2 fs-3"></i>                        
                        <div>
                            Program learning outcomes (PLOs) are the knowledge, skills and attributes that students are expected to attain by the end of a program of study. Add, edit and delete PLOs below. 
                            Categories can be used to group PLOs. 
                            You may use an excel spreadsheet to import multiple PLOs/Categories (use row 1 for headers and begin list of PLOs on row 2). Follow the template below to save them on your computer first, and then upload them to this page.

                            <strong> Please note this website can only support a total of 20 PLOs per program (future updates will allow for more PLOs)</strong>.
                        </div>
                    </div>

                    <form method="POST" class="col-6 ml-1" action="{{ action([\App\Http\Controllers\ProgramLearningOutcomeController::class, 'import']) }}" enctype="multipart/form-data">
                        @csrf
                        <a href="{{asset('import_samples/import-plos-template.xlsx')}}" download><i class="bi bi-download mb-1"></i> import-plos-template.xlsx</a>
                        <div class="input-group">
                            <input type="hidden" name="program_id" value="{{$program->program_id}}">
                            <input type="file" name="upload" class="form-control" aria-label="Upload" required accept=".xlsx,.xls,.csv">
                            <button class="btn bg-primary text-white" type="submit" >Import PLOs<i class="bi bi-box-arrow-in-down-left pl-2"></i></button>
                        </div>
                    </form>

                    <div class="card m-3">
                        <h5 class="card-header wizard text-start">
                            Categories (Can be used to group PLOs)
                            <button type="button" class="btn bg-primary text-white btn-sm col-2 float-right" data-bs-toggle="modal" data-bs-target="#addPLOCategoryModal">
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
                                            <button type="button" style="width:60px;" class="btn btn-secondary btn-sm m-1" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{$category->plo_category_id}}">
                                                Edit
                                            </button>

                                            <button style="width:60px;" type="button" class="btn btn-danger btn-sm btn btn-danger btn-sm m-1" data-bs-toggle="modal" data-bs-target="#deleteCategories{{$category->plo_category_id}}">
                                                Delete
                                            </button>

                                            <!-- Edit Category Modal -->
                                            <div class="modal fade editCatModal" id="editCategoryModal{{$category->plo_category_id}}" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true" data-categoryid="{{$category->plo_category_id}}">
                                                    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editCategoryModalLabel">Edit
                                                                    Program Learning Outcome Category</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <form action="{{route('program.category.update', $category->plo_category_id)}}" method="POST">
                                                                @csrf
                                                                {{method_field('POST')}}

                                                                <div class="modal-body">
                                                                    <div class="form-floating mb-3">
                                                                        <input id="editCatInput-{{$category->plo_category_id}}" type="text" class="form-control" placeholder="E.g. Artificial Intelligence" autofocus name="category" value="{{$category->plo_category}}" required>
                                                                        <label for="category-{{$category->plo_category_id}}">Category Name</label>
                                                                    </div>

                                                                    <input type="hidden" class="form-check-input" name="program_id" value={{$program->program_id}}>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary col-2 btn-sm" data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary col-2 btn-sm">Save</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                            </div>
                                            <!-- End of Edit Category Modal  -->

                                            <!-- Delete Confirmation Modal -->
                                            <div class="modal fade" id="deleteCategories{{$category->plo_category_id}}" tabindex="-1" role="dialog" aria-labelledby="deleteCategories{{$category->plo_category_id}}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Delete Confirmation</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                            Are you sure you want to delete category: {{$category->plo_category}}?
                                                            </div>

                                                            <form action="{{route('program.category.destroy', $category->plo_category_id)}}" method="POST">
                                                                @csrf
                                                                {{method_field('DELETE')}}
                                                                <input type="hidden" class="form-check-input " name="program_id"
                                                                    value={{$program->program_id}}>

                                                                <div class="modal-footer">
                                                                    <button style="width:60px" type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
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
                    </div>

                    <!-- Program Learning Outcomes -->
                    <div class="card m-3">
                        <h5 class="card-header wizard text-start">
                            Program Learning Outcomes (PLOs)
                            <button type="button" class="btn bg-primary text-white btn-sm col-2 float-right" data-bs-toggle="modal" data-bs-target="#addPLOModal">
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
                                        <?php $count = 0 ?>
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
                                                        <td class="text-center" style="width: 10%;">{{$defaultShortFormsIndex[$ploCat->pl_outcome_id]}}</td>
                                                        <td>
                                                            <span style="font-weight: bold;">{{$ploCat->plo_shortphrase}}</span><br>
                                                            {{$ploCat->pl_outcome}}
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" style="width:60px;" class="btn btn-secondary btn-sm m-1" data-bs-toggle="modal" data-bs-target="#editPLO{{$ploCat->pl_outcome_id}}">
                                                                Edit
                                                            </button>
                                                            <button style="width:60px;" type="button" class="btn btn-danger btn-sm btn btn-danger btn-sm m-1" data-bs-toggle="modal" data-bs-target="#deletePLO{{$ploCat->pl_outcome_id}}">
                                                                Delete
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endif
                                                
                                                <!-- Delete PLO Confirmation Model -->
                                                <div class="modal fade" id="deletePLO{{$ploCat->pl_outcome_id}}" tabindex="-1" role="dialog" aria-labelledby="deletePLO{{$ploCat->pl_outcome_id}}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Delete Confirmation</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                @if ($ploCat->plo_shortphrase)
                                                                    Are you sure you want to delete program learning outcome: {{$ploCat->plo_shortphrase}}?
                                                                @else 
                                                                    Are you sure you want to delete this program learning outcome?
                                                                @endif
                                                            </div>

                                                            <form action="{{route('plo.destroy', $ploCat->pl_outcome_id)}}" method="POST">
                                                                @csrf
                                                                {{method_field('DELETE')}}
                                                                <input type="hidden" class="form-check-input " name="program_id" value={{$program->program_id}}>

                                                                <div class="modal-footer">
                                                                    <button style="width:60px" type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                                    <button style="width:60px" type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                                </div>

                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End of Delete PLO Confirmation Modal -->

                                                <!-- Edit PLO Modal -->
                                                <div class="modal fade" data-bs-keyboard="false" id="editPLO{{$ploCat->pl_outcome_id}}" tabindex="-1" role="dialog" aria-labelledby="editPLO{{$ploCat->pl_outcome_id}}" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editPLOModalLabel">Edit Program Learning Outcome (PLO)</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            
                                                            <form action="{{route('plo.update', $ploCat->pl_outcome_id)}}" method="POST">
                                                                @csrf
                                                                {{method_field('POST')}}
                                                                <div class="modal-body">
                                                                    <div class="form-floating mb-3">
                                                                        <textarea id="editPLOinput{{$ploCat->pl_outcome_id}}" name="plo" class="form-control" placeholder="E.g. Develop..."  style="height: 100px" required>{{$ploCat->pl_outcome}}</textarea>
                                                                        <label for="editPLOinput{{$ploCat->pl_outcome_id}}"><span class="requiredField">* </span>Program Learning Outcome</label>
                                                                    </div>

                                                                    <div class="form-floating mb-3">
                                                                        <input type="text" class="form-control" id="editPLOShortphraseInput{{$ploCat->pl_outcome_id}}" placeholder="E.g. Experimental Design" value="{{$ploCat->plo_shortphrase}}" name="title" maxlength="50">
                                                                        <label for="editPLOShortphraseInput{{$ploCat->pl_outcome_id}}">Short Phrase</label>
                                                                        <small class="ml-2 form-text text-muted" style="font-size:12px"><i class="bi bi-exclamation-circle-fill text-warning mr-1" title=""></i> Having a short phrase helps with visualizing your program overview at the end of the mapping process (50 character limit)</small>                                     
                                                                    </div>
                                                                    <div class="form-floating mb-3">
                                                                        <select class="form-select" name="category" id="editPLOCatSelect{{$ploCat->pl_outcome_id}}" style="font-size:14px">
                                                                            @if ($ploCat)
                                                                                <option value="{{$ploCat->plo_category_id}}" selected>{{$ploCat->plo_category}}</option>
                                                                                @foreach($ploCategories as $ploCategory)
                                                                                    @if ($ploCategory->plo_category_id != $ploCat->plo_category_id)
                                                                                        <option value="{{$ploCategory->plo_category_id}}">{{$ploCategory->plo_category}}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                                <option value="">None</option>
                                                                            @else 
                                                                                <option value="" selected>None</option>
                                                                                @foreach ($ploCategories as $ploCategory)
                                                                                    <option value="{{$ploCategory->plo_category_id}}">{{$ploCategory->plo_category}}</option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                        <label for="editPLOCatSelect{{$ploCat->pl_outcome_id}}"><span class="requiredField">* </span>PLO Category</label>
                                                                    </div>

                                                                    <input type="hidden" class="form-check-input" name="program_id" value={{$program->program_id}}>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary col-2 btn-sm" data-bs-dismiss="modal">Close</button>
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
                                                <td class="text-center" style="width: 10%;">{{$defaultShortFormsIndex[$unCatplo->pl_outcome_id]}}</td>
                                                <td>
                                                    <span style="font-weight: bold;">{{$unCatplo->plo_shortphrase}}</span><br>
                                                    {{$unCatplo->pl_outcome}}
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" style="width:60px;" class="btn btn-secondary btn-sm m-1" data-bs-toggle="modal" data-bs-target="#editPLOunCat{{$unCatplo->pl_outcome_id}}">
                                                        Edit
                                                    </button>
                                                    <button style="width:60px;" type="button" class="btn btn-danger btn-sm btn btn-danger btn-sm m-1" data-bs-toggle="modal" data-bs-target="#deletePLO{{$unCatplo->pl_outcome_id}}">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                            <!-- Delete PLO Confirmation Model -->
                                            <div class="modal fade" id="deletePLO{{$unCatplo->pl_outcome_id}}" tabindex="-1" role="dialog" aria-labelledby="deletePLO{{$unCatplo->pl_outcome_id}}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Delete Confirmation</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @if ($unCatplo->plo_shortphrase)
                                                                Are you sure you want to delete program learning outcome: {{$unCatplo->plo_shortphrase}}?
                                                            @else 
                                                                Are you sure you want to delete this program learning outcome?
                                                            @endif
                                                        </div>
                                                        <form action="{{route('plo.destroy', $unCatplo->pl_outcome_id)}}" method="POST">
                                                            @csrf
                                                            {{method_field('DELETE')}}
                                                            <input type="hidden" class="form-check-input " name="program_id" value={{$program->program_id}}>
                                                            <div class="modal-footer">
                                                                <button style="width:60px" type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                                <button style="width:60px" type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End of Delete PLO Confirmation Modal -->

                                            <div class="modal fade" id="editPLOunCat{{$unCatplo->pl_outcome_id}}" tabindex="-1" role="dialog" aria-labelledby="editPLOunCat{{$unCatplo->pl_outcome_id}}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editPLOModalLabel">Edit Program Learning Outcome (PLO)</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        
                                                        <form action="{{route('plo.update', $unCatplo->pl_outcome_id)}}" method="POST">
                                                            @csrf
                                                            {{method_field('POST')}}
                                                            <div class="modal-body">
                                                                <div class="form-floating mb-3">
                                                                    <textarea id="editPLOinput{{$unCatplo->pl_outcome_id}}" name="plo" class="form-control" placeholder="E.g. Develop..."  style="height: 100px" required>{{$unCatplo->pl_outcome}}</textarea>
                                                                    <label for="editPLOinput{{$unCatplo->pl_outcome_id}}"><span class="requiredField">* </span>Program Learning Outcome</label>
                                                                </div>

                                                                <div class="form-floating mb-3">
                                                                    <input type="text" class="form-control" id="editPLOShortphraseInput{{$unCatplo->pl_outcome_id}}" placeholder="E.g. Experimental Design" value="{{$unCatplo->plo_shortphrase}}" name="title" maxlength="50">
                                                                    <label for="editPLOShortphraseInput{{$unCatplo->pl_outcome_id}}">Short Phrase</label>
                                                                    <small class="ml-2 form-text text-muted" style="font-size:12px"><i class="bi bi-exclamation-circle-fill text-warning mr-1" title=""></i> Having a short phrase helps with visualizing your program overview at the end of the mapping process (50 character limit)</small>                                     
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <select class="form-select" name="category" id="editPLOCatSelect{{$unCatplo->pl_outcome_id}}" style="font-size:14px">
                                                                        @if ($unCatplo->plo_category_id)
                                                                            <option value="{{$unCatplo->plo_category_id}}" selected>{{$unCatplo->plo_category}}</option>
                                                                            @foreach($ploCategories as $ploCategory)
                                                                                @if ($ploCategory->plo_category_id != $unCatplo->plo_category_id)
                                                                                    <option value="{{$unCatplo->plo_category_id}}">{{$ploCategory->plo_category}}</option>
                                                                                @endif
                                                                            @endforeach
                                                                            <option value="">None</option>
                                                                        @else 
                                                                            <option value="" selected>None</option>
                                                                            @foreach ($ploCategories as $ploCategory)
                                                                                <option value="{{$ploCategory->plo_category_id}}">{{$ploCategory->plo_category}}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    <label for="editPLOCatSelect{{$unCatplo->pl_outcome_id}}"><span class="requiredField">* </span>PLO Category</label>
                                                                </div>
                                                                <input type="hidden" class="form-check-input" name="program_id" value={{$program->program_id}}>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary col-2 btn-sm" data-bs-dismiss="modal">Close</button>
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
                    </div>
                    <!-- End Program Learning Outcomes -->

                </div>
                <div class="card-footer">
                    <div class="card-body mb-4">
                        <a href="{{route('programWizard.step2', $program->program_id)}}">
                            <button class="btn btn-sm btn-primary col-3 float-right">Mapping Scales <i class="bi bi-arrow-right mr-2"></i></button>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        // $("form").submit(function () {
        //     // prevent duplicate form submissions
        //     $(this).find(":submit").attr('disabled', 'disabled');
        //     $(this).find(":submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

        // });

        // Enables functionality of tool tips
        $('[data-bs-toggle="tooltip"]').tooltip({html:true});

        // autofocus edit category name input field in edit category modals
        Array.from(document.getElementsByClassName('editCatModal')).forEach(function(editCatModal) {
            editCatModal.addEventListener('shown.bs.modal', function() {
                var categoryId = editCatModal.dataset.categoryid;
                document.getElementById('editCatInput-' + categoryId).focus();

            });
        });

        $('#addPLOCategoryForm').submit(function (event) {
            // prevent default form submission handling
            event.preventDefault();
            event.stopPropagation();
            // check if input fields contain data
            if ($('#PLOCategory').val().length != 0) {
                addPLOCategory();
                // reset form 
                $(this).trigger('reset');
                $(this).removeClass('was-validated');
            } else {
                // mark form as validated
                $(this).addClass('was-validated');
            }
            // readjust modal's position 
            document.querySelector('#addPLOCategoryModal').handleUpdate();

        });

        $('#addPLOForm').submit(function (event) {
            // prevent default form submission handling
            event.preventDefault();
            event.stopPropagation();
            // check if input fields contain data
            if ($('#pl_outcome').val().length != 0) {
                addPLO();
                removeHTMLSelectDuplicates();
                // reset form 
                $(this).trigger('reset');
                $(this).removeClass('was-validated');
            } else {
                // mark form as validated
                $(this).addClass('was-validated');
            }
            // readjust modal's position
            var addP 
            document.querySelector('#addPLOModal').handleUpdate();
        });

        $('#cancelPLOCategoryForm').click(function(event) {
            $('#addPLOCategoryTbl tbody').html(`
                @foreach($ploCategories as $index => $category)
                    <tr>
                        <td>
                            <input id="category{{$category->plo_category_id}}" type="text" class="form-control"name="current_plo_categories[{{$category->plo_category_id}}]" value = "{{$category->plo_category}}" form="savePLOCategoryChanges" required spellcheck="true" style="white-space: pre">
                        </td>
                        <td class="text-center">
                            <i class="bi bi-x-circle-fill text-danger fs-4 btn" onclick="deleteRow(this)"></i>
                        </td>
                    </tr>
                @endforeach                                               
            `);
        });

        $('#cancelAddPLOForm').click(function(event) {
            $('#addPLOTbl tbody').html(`
                @foreach($program->programLearningOutcomes as $index => $pl_outcome)
                    <tr>
                        <td>
                            <textarea name="current_pl_outcome[{{$pl_outcome->pl_outcome_id}}]" value="{{$pl_outcome->pl_outcome}}" id="pl_outcome{{$pl_outcome->pl_outcome_id}}" class="form-control @error('pl_outcome') is-invalid @enderror" form="savePLOChanges" required style="resize:none">{{$pl_outcome->pl_outcome}}</textarea>
                        </td>
                        <td>
                            <textarea type="text" name="current_pl_outcome_short_phrase[{{$pl_outcome->pl_outcome_id}}]" id="pl_outcome_short_phrase{{$pl_outcome->pl_outcome_id}}" class="form-control @error('clo_shortphrase') is-invalid @enderror"  form="savePLOChanges" maxlength="50" style="resize:none">{{$pl_outcome->plo_shortphrase}}</textarea>
                        </td>
                        <td>                  
                            <select class="form-select form-control" name="current_plo_category[{{$pl_outcome->pl_outcome_id}}]" style="height:4.7rem" id="plo_category{{$pl_outcome->pl_outcome_id}}" form="savePLOChanges" required>
                                @if ($pl_outcome->category)
                                    <option value="{{$pl_outcome->category->plo_category_id}}" selected>{{$pl_outcome->category->plo_category}}</option>
                                    @foreach($ploCategories as $ploCat)
                                        @if ($ploCat->plo_category_id != $pl_outcome->category->plo_category_id)
                                            <option value="{{$ploCat->plo_category_id}}">{{$ploCat->plo_category}}</option>
                                        @endif
                                    @endforeach
                                    <option value="">None</option>
                                @else 
                                    <option value="" selected>None</option>
                                    @foreach ($ploCategories as $ploCat)
                                        <option value="{{$ploCat->plo_category_id}}">{{$ploCat->plo_category}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                        <td class="text-center align-middler">
                            <i class="bi bi-x-circle-fill text-danger fs-4 btn" onclick="deleteRow(this)"></i>
                        </td>
                    </tr>
                @endforeach
            `);
        });

    });

    function deleteRow(submitter) {
        $(submitter).parents('tr').remove();
    }

    function addPLOCategory() {
        // prepend plo category to the table
        $('#addPLOCategoryTbl tbody').append(`
            <tr>
                <td>
                    <input type="text" class="form-control" name="new_plo_categories[]" value="${$('#PLOCategory').val()}" placeholder="Eg. Communication Skills" form="savePLOCategoryChanges" required >
                </td>
                <td class="text-center">
                    <i class="bi bi-x-circle-fill text-danger fs-4 btn" onclick="deleteRow(this)"></i>
                </td>
            </tr>        
        `);
    }

    function addPLO() {
        // prepend assessment method to the table
        $('#addPLOTbl tbody').append(`
            <tr>
                <td>
                    <textarea name="new_pl_outcome[]" class="form-control" form="savePLOChanges" required style="resize:none">${$('#pl_outcome').val()}</textarea>
                </td>
                <td>
                    <textarea type="text" name="new_pl_outcome_short_phrase[]" class="form-control"  form="savePLOChanges" maxlength="50" style="resize:none">${$('#ploShortphrase').val()}</textarea>
                </td>
                <td>                  
                    <select class="newPLOCatSelect unchecked form-select form-control" name="new_plo_category[]" style="height:4.7rem" form="savePLOChanges">
                        <option selected value="${$('#ploCategory option:selected').val()}">${$( "#ploCategory option:selected" ).text()}</option>
                        @foreach ($ploCategories as $ploCat)
                            <option value="{{$ploCat->plo_category_id}}">{{$ploCat->plo_category}}</option>
                        @endforeach
                        ${$('#ploCategory').val().length == 0 ? '' : '<option value="">None</option>'}
                    </select>
                </td>
                <td class="text-center align-middle">
                    <i class="bi bi-x-circle-fill text-danger fs-4 btn" onclick="deleteRow(this)"></i>
                </td>
            </tr>        
        `);
    }

    function removeHTMLSelectDuplicates() {
        // get select elements that haven't been checked for duplicates
        var selects = $('.newPLOCatSelect.unchecked');
        $(selects).each(function(index, select) {
            // track values used in the select
            var usedVals = {};
            Array.from(select.options).forEach(function(option){
                if(usedVals[option.text]) {
                    // remove option if it already exists in usedVals
                    $(option).remove();
                } else {
                    usedVals[option.text] = option.value;
                }
            });
            // remove unchecked class
            $(select).removeClass('unchecked');
        });
    }
</script>
@endsection
