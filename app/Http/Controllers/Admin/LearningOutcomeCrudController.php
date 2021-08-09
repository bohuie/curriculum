<?php


namespace App\Http\Controllers\Admin;

use App\Http\Requests\CourseRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use App\Models\Course;

/**
 * This will be used to open an inline(create) panel for creation of these related records while in the context of the courseCrudController view
 *
 * @author Mat
 */
class LearningOutcomeCrudController extends CrudController{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;
    
    public function setup()
    {
        CRUD::setModel(\App\Models\LearningOutcome::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/learningOutcome');
        CRUD::setEntityNameStrings('learningOutcome', 'learningOutcomes');

        //$this->crud->denyAccess('create');
    }
    
     protected function setupCreateOperation()
    {
        CRUD::setValidation(\App\Http\Requests\LearningOutcomeRequest::class);
        
         $this->crud->addField([
            'name' => 'clo_shortphrase', // The db column name
            'label' => "CLO Shortphrase", // Table column heading
            'type' => 'Text'
         ]);
         
         $this->crud->addField([
            'name' => 'l_outcome', // The db column name
            'label' => "Learning Outcome", // Table column heading
            'type' => 'Text'
         ]);
        
    }
    
      protected function setupInlineCreateOperation()
    {
        CRUD::setValidation(\App\Http\Requests\LearningOutcomeRequest::class);
        
         $this->crud->addField([
            'name' => 'clo_shortphrase', // The db column name
            'label' => "CLO Shortphrase", // Table column heading
            'type' => 'Text'
         ]);
         
         $this->crud->addField([
            'name' => 'l_outcome', // The db column name
            'label' => "Learning Outcome", // Table column heading
            'type' => 'Text'
         ]);         
         $req =  $this->crud->getRequest()->request->all();
         $val = null;
         if(isset($req['main_form_fields'])){
             foreach($req['main_form_fields'] as $f){
                 if(isset($f['name']) && $f['name'] == "course_id"){
                     $val = $f['value'];
                 }
             }
         }
         else{
             $val = $req['course_id'];
         }
         
         //takes the value directly from parameters the second time. so confusing
           $this->crud->addField([
            'name' => 'course_id', // The db column name
            'value' => $val, // Table column heading 'value' => (isset($req['main_form_fields'])) ? $req['main_form_fields'][12]['value'] : $req['course_id']
            'type' => 'hidden'
         ]);
        
    }
}
