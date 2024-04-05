<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OptionalPriorityRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;

/**
 * Class OptionalPriorityCrudController
 *
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OptionalPriorityCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\OptionalPriorities::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/optional-priority');
        CRUD::setEntityNameStrings('optional priority', 'optional priorities');

        // Hide the preview button
        $this->crud->denyAccess('show');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     */
    protected function setupListOperation(): void
    {
        // Priority
        $this->crud->addColumn([
            'name' => 'op_id', // The db column name
            'label' => 'Optional Priority ID', // Table column heading
            'type' => 'number',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('op_id', 'like', '%'.$searchTerm.'%');
            },
        ]);

        $this->crud->addColumn([
            'name' => 'optional_priority', // The db column name
            'label' => 'Optional Priority', // Table column heading
            'type' => 'text',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('optional_priority', 'like', '%'.$searchTerm.'%');
            },
        ]);

        $this->crud->addColumn([
            'label' => 'Subcategory Name', // Table column heading
            'type' => 'strip_select',
            'name' => 'optionalPrioritySubcategory', // The db column name
            'entity' => 'optionalPrioritySubcategory',
            'attribute' => 'subcat_name',
            'model' => App\Models\OptionalPrioritySubcategories::class,
        ]);

        $this->crud->addColumn([
            'name' => 'subcat_id', // The db column name
            'label' => 'Subcat ID', // Table column heading
            'type' => 'number',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('subcat_id', 'like', '%'.$searchTerm.'%');
            },
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     */
    protected function setupCreateOperation(): void
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
            'label' => 'Optional Priority&nbsp;&nbsp;<span style="color:red">*</span>', // Table column heading
            'type' => 'valid_textarea',
            'attributes' => ['req' => 'true'],
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
            'name' => 'isCheckable', // The db column name
            'label' => 'Is Checkable?&nbsp;&nbsp;<span style="color:red">*</span>', // Table column heading
            'type' => 'radio',
            'options' => [
                // the key will be stored in the db, the value will be shown as label;
                0 => 'Not a checkable Strategic Priority',
                1 => 'Checkable Strategic Priority',
            ],
            'attributes' => ['req' => 'true'],
        ]);

        $this->crud->addField([
            'label' => 'Subcategory Name', // Table column heading
            'type' => 'select',
            'name' => 'subcat_id', // The db column name
            'entity' => 'optionalPrioritySubcategory',
            'attribute' => 'subcat_name',
            'model' => \App\Models\OptionalPrioritySubcategories::class,
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     */

    // Edit
    protected function setupUpdateOperation(): void
    {
        CRUD::setValidation(OptionalPriorityRequest::class);
        $op_id_num = \DB::table('optional_priorities')->count();

        // Priority
        $this->crud->addField([
            'name' => 'op_id', // The db column name
            'label' => 'OptionalPriority Id', // Table column heading
            'type' => 'number',
            'default' => $op_id_num + 1,
            'attributes' => ['readonly' => 'readonly',
            ],
        ]);
        $this->crud->addField([
            'name' => 'optional_priority', // The db column name
            'label' => 'Optional Priority&nbsp;&nbsp;<span style="color:red">*</span>', // Table column heading
            'type' => 'valid_textarea',
            'attributes' => ['req' => 'true'],
        ]);
        $this->crud->addField([
            'name' => 'isCheckable', // The db column name
            'label' => 'Is Checkable?&nbsp;&nbsp;<span style="color:red">*</span>', // Table column heading
            'type' => 'radio',
            'options' => [
                // the key will be stored in the db, the value will be shown as label;
                0 => 'Not a checkable Strategic Priority',
                1 => 'Checkable Strategic Priority',
            ],
            'attributes' => ['req' => 'true'],
        ]);

        // Subcategory
        // $this->crud->addField([
        //     'name' => 'subcat_id', // The db column name
        //     'label' => "Subcat Id",// Table column heading
        //     'type' => 'number',
        // ]);

        $this->crud->addField([
            'label' => 'Subcategory Name', // Table column heading
            'type' => 'select',
            'name' => 'optionalPrioritySubcategory', // The db column name
            'entity' => 'optionalPrioritySubcategory',
            'attribute' => 'subcat_name',
            'model' => \App\Models\OptionalPrioritySubcategories::class,
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
            'label' => 'Optional Priority ID', // Table column heading
            'type' => 'Text',
        ]);
        $this->crud->addColumn([
            'name' => 'optional_priority', // The db column name
            'label' => 'Optional Priority', // Table column heading
            'type' => 'Text',
        ]);

        // SubCategory
        $this->crud->addColumn([
            'name' => 'subcat_id', // The db column name
            'label' => 'Subcat ID', // Table column heading
            'type' => 'Text',
        ]);
        $this->crud->addColumn([
            'label' => 'Subcategory Name', // Table column heading
            'type' => 'select',
            'name' => 'subcat_name', // The db column name
            'entity' => 'optionalPrioritySubcategory',
            'attribute' => 'subcat_name',
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
