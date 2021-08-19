<?php


namespace App\Http\Controllers\Admin;

use App\Http\Requests\MappingScaleProgramRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use App\Models\Program;

/**
 * This will be used to open an inline(create) panel for creation of these related records while in the context of the programCrudController view
 *
 * @author Mat
 */
class MappingScaleProgramCrudController extends CrudController{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;
    
    public function setup()
    {
        CRUD::setModel(\App\Models\MappingScaleProgram::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mappingScaleProgram');
        CRUD::setEntityNameStrings('mappingScaleProgram', 'mappingScalePrograms');

        //$this->crud->denyAccess('create');
    }
    
     protected function setupCreateOperation()
    {
        CRUD::setValidation(\App\Http\Requests\MappingScaleProgramRequest::class);
        
        
        
    }
    
      protected function setupInlineCreateOperation()
    {
        CRUD::setValidation(\App\Http\Requests\MappingScaleProgramRequest::class);
        
        $this->crud->addField( [
                    'name'    => 'map_scale_id',
                    'type'    => 'select',
                    'label'   => 'Id',
                    'entity'  => 'mappingScaleLevels',
                    'model' => 'App\Models\MappingScaleProgram',
                    'attribute' => 'title',
                    //'attributes' => ['disabled' => 'true'],
                    'wrapper' => ['class' => 'form-group col-md-2'],                     
         ]);
         
                 
         $req =  $this->crud->getRequest()->request->all();
         $val = null;
         if(isset($req['main_form_fields'])){
             foreach($req['main_form_fields'] as $f){
                 if(isset($f['name']) && $f['name'] == "program_id"){
                     $val = $f['value'];
                 }
             }
         }
         else{
             $val = $req['program_id'];
         }
         
         //takes the value directly from parameters the second time. so confusing
           $this->crud->addField([
            'name' => 'program_id', // The db column name
            'value' => $val, // Table column heading 'value' => (isset($req['main_form_fields'])) ? $req['main_form_fields'][12]['value'] : $req['course_id']
            'type' => 'hidden'
         ]);
        
    }
}
