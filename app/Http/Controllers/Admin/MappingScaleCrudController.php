<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MappingScalesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MappingScalesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MappingScaleCrudController extends CrudController
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
        CRUD::setModel(\App\Models\MappingScale::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mapping-scales');
        CRUD::setEntityNameStrings('mapping scale', 'mapping scales');

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
        CRUD::column('title');
        CRUD::column('abbreviation');
        CRUD::column('colour');


            //'wrapper'=> ['class' => 'form-group col-md-2'],
            /*'searchLogic' => function($query, $column, $searchTerm){
                $query ->orWhere('cat_id', 'like', '%'.$searchTerm.'%');
            }*/

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
        $this->crud->addField([
            'name' => 'title',
            'label' => 'Title&nbsp;&nbsp;<span style=color:red>*</span>',
            'type' => 'valid_text',
            'attributes' => [
                    'req' => true
            ]
        ]);
        $this->crud->addField([
            'name' => 'abbreviation',
            'label' => 'Abbreviation&nbsp;&nbsp;<span style=color:red>*</span>',
            'type' => 'text',
            'attributes' => [
                    'req' => true
            ]
        ]);
        $this->crud->addField([
            'name' => 'description',
            'label' => 'Description&nbsp;&nbsp;<span style=color:red>*</span>',
            'type' => 'text',
            'attributes' => [
                    'req' => true
            ]
        ]);
        $this->crud->addField([
            'name' => 'colour',
            'label' => 'Colour',
            'type' => 'color_picker'
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
        //$this->setupCreateOperation();
        $this->crud->addField([
            'name' => 'title',
            'label' => 'Title&nbsp;&nbsp;<span style=color:red>*</span>',
            'type' => 'text',
            'attributes' => [
                    'req' => true
            ]
        ]);
        $this->crud->addField([
            'name' => 'abbreviation',
            'label' => 'Abbreviation&nbsp;&nbsp;<span style=color:red>*</span>',
            'type' => 'text',
            'attributes' => [
                    'req' => true
            ]
        ]);
        $this->crud->addField([
            'name' => 'description',
            'label' => 'Description&nbsp;&nbsp;<span style=color:red>*</span>',
            'type' => 'text',
            'attributes' => [
                    'req' => true
            ]
        ]);
        $this->crud->addField([
            'name' => 'colour',
            'label' => 'Colour',
            'type' => 'color_picker'
        ]);
    }

}