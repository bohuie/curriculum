<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MappingScaleCategoryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;

/**
 * Class MappingScaleCategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MappingScaleCategoryCrudController extends CrudController
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
        CRUD::setModel(\App\Models\MappingScaleCategory::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mapping-scale-category');
        CRUD::setEntityNameStrings('mapping scale category', 'mapping scale categories');
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
        CRUD::column('msc_title');
        CRUD::column('description');

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
        CRUD::setValidation(MappingScaleCategoryRequest::class);

       $this->crud->addField([
            'name' => 'msc_title', // The db column name
            'label' => "Category name&nbsp;&nbsp;<span style=color:red>*</span>", // Table column heading
            'type' => 'valid_text',
            'attributes' => [
                        'req' => 'true',
                        ],
         ]);

         $this->crud->addField([
            'name' => 'description', // The db column name
            'label' => "Description&nbsp;&nbsp;<span style=color:red>*</span>", // Table column heading
            'type' => 'textarea',
             'attributes' => [
                        'req' => 'true',
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
        
        $this->crud->addField([   // repeatable
            'name'  => 'Mappingtable',
            'label' => 'Scales',
            'type'  => 'repeatable',
            'entity' => 'MappingScales',
            
            'fields' => [
                 [
                    'name'    => 'map_scale_id',
                    'type'    => 'Text',
                    'label'   => '',
                    'attributes' => ['disabled' => 'true', 'hidden' => true],
                    
                ],
                [
                    'name' => 'title',
                    'label' => 'Title&nbsp;&nbsp;<span style=\"color:red\">*</span>',
                    'type' => 'text',
                    'attributes' => [
                        'req' => 'true',
                        ],
                    'wrapper' => ['class' => 'form-group col-md-7'],
                ],
                [
                    'name' => 'abbreviation',
                    'label' => 'Abbreviation&nbsp;&nbsp;<span style=\"color:red\">*</span>',
                    'type' => 'text',
                    'attributes' => [
                        'req' => 'true',
                        ],
                    'wrapper' => ['class' => 'form-group col-md-2'],
                ],
                [
                    'name' => 'colour',
                    'label' => 'Colour',
                    'type' => 'color_picker',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name' => 'description',
                    'label' => 'Description&nbsp;&nbsp;<span style=\"color:red\">*</span>',
                    'type' => 'text',
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
}
