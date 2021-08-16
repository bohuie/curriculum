<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CustomAssessmentMethodsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CustomAssessmentMethodsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CustomAssessmentMethodsCrudController extends CrudController
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
        CRUD::setModel(\App\Models\CustomAssessmentMethods::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/custom-assessment-methods');
        CRUD::setEntityNameStrings('custom assessment methods', 'custom assessment methods');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
         $this->crud->addColumn([
            'name' => 'custom_method_id', // The db column name
            'label' => "Id", // Table column heading
            'type' => 'number',
            'searchLogic' => function($query, $column, $searchTerm){
                $query ->orWhere('custom_method_id', 'like', '%'.$searchTerm.'%');
            }
          ]);
        
        $this->crud->addColumn([
            'name' => 'custom_methods', // The db column name
            'label' => "Assessment Method", // Table column heading
            'type' => 'Text',
            'searchLogic' => function($query, $column, $searchTerm){
                $query ->orWhere('custom_methods', 'like', '%'.$searchTerm.'%');
            }
          ]);
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
        CRUD::setValidation(CustomAssessmentMethodsRequest::class);

        CRUD::addField([
            'name'  => 'custom_methods',
            'type' => 'valid_text',
            'label' => 'Assessment Method',
            'attributes' => [
                'req' => true
            ],
        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
