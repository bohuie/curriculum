<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StandardsScaleCategoryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class StandardsScaleCategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StandardsScaleCategoryCrudController extends CrudController
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
        CRUD::setModel(\App\Models\StandardsScaleCategory::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/standards-scale-category');
        CRUD::setEntityNameStrings('standards scale category', 'standards scale categories');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('name');
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
        CRUD::setValidation(StandardsScaleCategoryRequest::class);

        $this->crud->addField([
            'name' => 'name', // The db column name
            'label' => "Standard Scale Category Name", // Table column heading
            'type' => 'valid_text',
            'attributes' => [
                        'req' => 'true',
                        ],
         ]);

         $this->crud->addField([
            'name' => 'description', // The db column name
            'label' => "Description", // Table column heading
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
              $this->crud->addField([
            'name' => 'name', // The db column name
            'label' => "Standard Scale Category Name", // Table column heading
            'type' => 'valid_text',
            'attributes' => [
                  'req' => 'true',
                  ],
         ]);
              
         $this->crud->addField([
            'name' => 'description', // The db column name
            'label' => "Description", // Table column heading
            'type' => 'Text'
         ]);
        
        $this->crud->addField([   // repeatable
            'name'  => 'Scaletable',
            'label' => 'Scales',
            'type'  => 'repeatable',
            'entity' => 'ministryStandardScales',
            
            'fields' => [
                [
                    'name'    => 'standard_scale_id',
                    'type'    => 'Text',
                    'label'   => 'Id',
                    'attributes' => ['disabled' => 'true'],
                    'wrapper' => ['class' => 'form-group col-md-2'],
                ],
                [
                    'name'    => 'title',
                    'type'    => 'Text',
                    'label'   => 'Title',
                    'attributes' => [
                        'req' => 'true',
                        ],
                    'wrapper' => ['class' => 'form-group col-md-5'],
                ],
                [
                    'name'    => 'abbreviation',
                    'type'    => 'text',
                    'label'   => 'Abbreviation',
                    'attributes' => [
                        'req' => 'true',
                        ],
                    'wrapper' => ['class' => 'form-group col-md-3'],
                    
                ],   
                /*[
                    'name'    => 'colour',
                    'type'    => 'text',
                    'label'   => 'Colour',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                    
                ], */
                [
                    'name'    => 'colour',
                    'type'    => 'color_picker',
                    'label'   => 'Colour',
                    'default'              => '#000000',
                    'wrapper' => ['class' => 'form-group col-md-2'],
                ], 
                [
                    'name'    => 'description',
                    'type'    => 'textarea',
                    'label'   => 'Description',  
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
        $sscID = filter_input(INPUT_SERVER,'PATH_INFO');        
        $sscID = explode("/",$sscID)[3];
        $r = DB::table('standard_scales')->where('scale_category_id', '=', $sscID)->delete();
        //this deletes the course record itself.
        return $this->crud->delete($id);
    }
}
