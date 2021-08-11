<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StandardCategoryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;


/**
 * Class StandardCategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StandardCategoryCrudController extends CrudController
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
        CRUD::setModel(\App\Models\StandardCategory::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/standard-category');
        CRUD::setEntityNameStrings('standard category', 'standard categories');

        // Hide the preview button 
        $this->crud->denyAccess('show');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('sc_name');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(StandardCategoryRequest::class);        
        
        $this->crud->addField([
            'name' => 'sc_name', // The db column name
            'label' => "Standard Category Name&nbsp;&nbsp;<span style=color:red>*</span>", // Table column heading
            'type' => 'valid_text',
            'attributes' => [
                        'req' => 'true',
                        ],
         ]);
        
      
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        
        $this->crud->addField([
            'name' => 'sc_name', // The db column name
            'label' => "Standard Category Name&nbsp;&nbsp;<span style=color:red>*</span>", // Table column heading
            'attributes' => [
                        'req' => 'true',
                        ],
            'type' => 'valid_text'
         ]);
        
        $this->crud->addField([   // repeatable
            'name'  => 'Standardtable',
            'label' => 'Standards',
            'type'  => 'repeatable',
            'entity' => 'standards',
            'fields' => [
                [
                    'name'    => 'standard_id',
                    'type'    => 'Text',
                    'label'   => 'Id',
                    'attributes' => [
                        'disabled' => 'true',
                        ],
                    'wrapper' => ['class' => 'form-group col-md-2'],
                ],
                [
                    'name'    => 's_shortphrase',
                    'type'    => 'text',
                    'label'   => 'Standard Shortphrase&nbsp;&nbsp;<span style=color:red>*</span>',
                    'attributes' => [
                        'req' => 'true',
                        ],
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
                [
                    'name'    => 's_outcome',
                    'type'    => 'textarea',
                    'label'   => 'Standard Outcome&nbsp;&nbsp;<span style=color:red>*</span>', 
                    'attributes' => [
                        'req' => 'true',
                        ],
                ],              
            ],

            // optional
            'new_item_label'  => 'Add Group', // customize the text of the button
            'init_rows' => 0, // number of empty rows to be initialized, by default 1
            'min_rows' => 0, // minimum rows allowed, when reached the "delete" buttons will be hidden
            'max_rows' => 10 // maximum rows allowed, when reached the "new item" button will be hidden

        ]);
    }
    
     use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as traitDestroy; }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        //delete all children starting with the leafmost objects. they have to be accessed using the id's of their parent records however (either the cloID or the courseID in this case)
        $scID = filter_input(INPUT_SERVER,'PATH_INFO');        
        $scID = explode("/",$scID)[3];
        $r = DB::table('standards')->where('standard_category_id', '=', $scID)->delete();
        //this deletes the course record itself.
        return $this->crud->delete($id);
    }
}
