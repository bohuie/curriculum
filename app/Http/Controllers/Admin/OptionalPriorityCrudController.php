<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OptionalPriorityRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;

use App\Models\OptionalPriorities;
/**
 * Class OptionalPriorityCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OptionalPriorityCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\OptionalPriorities::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/optional-priority');
        CRUD::setEntityNameStrings('optional priority', 'optional priorities');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {   
        // Priority
        $this->crud->addColumn([
            'name' => 'op_id', // The db column name
            'label' => 'Optional Priority ID',// Table column heading
            'type' => 'number',
            'searchLogic' => function($query, $column, $searchTerm){
                $query ->orWhere('op_id', 'like', '%'.$searchTerm.'%');
            }
        ]);

        // Subcategory
        /*$this->crud->addColumn([
            'name' => 'subcat_desc', // The db column name
            'label' => "Subcategory desc",// Table column heading
            'type' => 'Text'
        ]);*/
        $this->crud->addColumn([
            'name' => 'subcat_id', // The db column name
            'label' => 'Subcat ID',// Table column heading
            'type' => 'number',
            'searchLogic' => function($query, $column, $searchTerm){
                $query ->orWhere('subcat_id', 'like', '%'.$searchTerm.'%');
            }
        ]);
        $removeHTML = strip_tags('subcat_name');
        $this->crud->addColumn([
            'label' => 'Subcategory Name',// Table column heading
            'type' => 'strip_select',
            'name' => 'OptionalPrioritySubcategories', // The db column name
            'entity' =>'OptionalPrioritySubcategories',
            'attribute' => $removeHTML,
            'model' => 'App\Models\OptionalPrioritySubcategories',
        ]);
       
        // Category
        /*$this->crud->addColumn([
            'name' => 'cat_name', // The db column name
            'label' => "Category Name",// Table column heading
            'type' => 'Text'
         ]);*/

        /* $this->crud->addColumn([
            'name' => 'cat_desc', // The db column name
            'label' => "Category desc",// Table column heading
            'type' => 'Text'
        ]);*/
        
        $this->crud->addColumn([
            'name' => 'optional_priority', // The db column name
            'label' => "Optional Priority",// Table column heading
            'type' => 'strip_text',
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(OptionalPriorityRequest::class);
        $op_id_num = \DB::table('optional_priorities')->count();
        
        // Priority
        /*$this->crud->addField([
            'name' => 'op_id', // The db column name
            'label' => "OptionalPriority Id",// Table column heading
            'type' => 'number',
            'default' => $op_id_num + 1,
            'attributes'=>['readonly'=>'readonly',
                           ],
        ]);*/
        $this->crud->addField([
            'name' => 'optional_priority', // The db column name
            'label' => "Optional Priority",// Table column heading
            'type' => 'valid_textarea',
            'attributes' => [ 'req' => 'true']
        ]);
        
        // Category
        /*$this->crud->addField([
            'name' => 'cat_name', // The db column name
            'label' => "Category Name",// Table column heading
            'type' => 'Text'
           ]);*/

        /* $this->crud->addColumn([
            'name' => 'cat_desc', // The db column name
            'label' => "Category desc",// Table column heading
            'type' => 'Text'
        ]);*/

       // SubCategory
       /*$this->crud->addField([
            'name' => 'subcat_id', // The db column name
            'label' => "Subcat Id",// Table column heading
            'type' => 'number',
            'default' => '1',
        ]);*/

        $this->crud->addField([
            'label' => "Subcategory Name",// Table column heading
            'type' => 'select',
            'name' => 'subcat_id', // The db column name
            'entity' =>'OptionalPrioritySubcategories',
            'attribute' =>'subcat_name',
            'model' => "App\Models\OptionalPrioritySubcategories",
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */

     // Edit 
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(OptionalPriorityRequest::class);
        $op_id_num = \DB::table('optional_priorities')->count();
        
        // Priority
        $this->crud->addField([
            'name' => 'op_id', // The db column name
            'label' => "OptionalPriority Id",// Table column heading
            'type' => 'number',
            'default' => $op_id_num + 1,
            'attributes'=>['readonly'=>'readonly',
                           ],
        ]);
        $this->crud->addField([
            'name' => 'optional_priority', // The db column name
            'label' => "Optional Priority",// Table column heading
            'type' => 'valid_textarea',
            'attributes' => [ 'req' => 'true']
        ]);

        // Subcategory
        // $this->crud->addField([
        //     'name' => 'subcat_id', // The db column name
        //     'label' => "Subcat Id",// Table column heading
        //     'type' => 'number',
        // ]);

        $this->crud->addField([
            'label' => 'Subcategory Name',// Table column heading
            'type' => 'select',
            'name' => 'OptionalPrioritySubcategories', // The db column name
            'entity' =>'OptionalPrioritySubcategories',
            'attribute' =>'subcat_name',
            'model' => "App\Models\OptionalPrioritySubcategories",
        ]);

        // Category

    }

    // Preveiw Operation
    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false);
        // Priority
        $this->crud->addColumn([
            'name' => 'op_id', // The db column name
            'label' => "Optional Priority ID",// Table column heading
            'type' => 'Text',
        ]);
        $this->crud->addColumn([
            'name' => 'optional_priority', // The db column name
            'label' => "Optional Priority",// Table column heading
            'type' => 'Text'
        ]);

        // SubCategory
        $this->crud->addColumn([
            'name' => 'subcat_id', // The db column name
            'label' => "Subcat ID",// Table column heading
            'type' => 'Text'
        ]);
        $this->crud->addColumn([
            'label' => 'Subcategory Name',// Table column heading
            'type' => 'select',
            'name' => 'subcat_name', // The db column name
            'entity' =>'OptionalPrioritySubcategories',
            'attribute' =>'subcat_name',
            'model' => App\Models\OptionalPrioritySubcategories::class,
        ]);

    }
    
     use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as traitDestroy; }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');        
        return $this->crud->delete($id);
    }

}
