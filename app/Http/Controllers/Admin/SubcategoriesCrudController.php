<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SubcategoriesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;

use App\Models\OptionalPrioritySubcategories;
/**
 * Class SubcategoriesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SubcategoriesCrudController extends CrudController
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
        CRUD::setModel(\App\Models\OptionalPrioritySubcategories::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/subcategories');
        CRUD::setEntityNameStrings('subcategories', 'subcategories');
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
            'name' =>'subcat_id',
            'label'=>"Subcategory ID",
            'type' =>'number',
            'searchLogic' => function($query, $column, $searchTerm){
                $query ->orWhere('subcat_id', 'like', '%'.$searchTerm.'%');
            }
        ]);

        $this->crud->addColumn([
            'name' => 'subcat_name',
            'label'=>"Subcategory Name",
            'type' =>'strip_text',
            'searchLogic' => function($query, $column, $searchTerm){
                $query ->orWhere('subcat_name', 'like', '%'.$searchTerm.'%');
            }
        ]);

        $this->crud->addColumn([
            'name' => 'cat_id',
            'label'=>"Category ID",
            'type' =>'number',
            'searchLogic' => function($query, $column, $searchTerm){
                $query ->orWhere('cat_id', 'like', '%'.$searchTerm.'%');
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
        CRUD::setValidation(SubcategoriesRequest::class);
        $subid = \DB::table('optional_priority_subcategories')->count();
       /* $this->crud->addField([
            'name'=>'subcat_id',
            'label'=>'Subcategory ID',
            'type' => 'number',
            'default' =>$subid+1,
            'attributes'=>['readonly'=>'readonly',
                           ],
        ]);*/

        $this->crud->addField([
            'label' => 'Category Name',// Table column heading
            'type' => 'select',
            'name' => 'cat_id', // The db column name
            'entity' =>'OptionalPriorityCategories',
            'attribute' =>'cat_name',
            'model' => 'App\Models\OptionalPriorityCategories',
        ]);


        $this->crud->addField([
            'name'=>'subcat_name',
            'label'=>'Subcategory Name',
            'type'=>'valid_textarea',
            'req' => 'true',
            'attributes' => [ 'req' => 'true']
        ]);

        $this->crud->addField([
            'name'=>'subcat_desc',
            'label'=>'Subcategory Description',
            'type'=>'textarea',
            'req' => 'true',
            'attributes' => [ 'req' => 'true']
        ]);
       
       
        $this->crud->addField([
            'name'=>'subcat_postamble',
            'label'=>'Subcategory Postamble',
            'type'=>'textarea',
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
        CRUD::setValidation(SubcategoriesRequest::class);
        $subid = \DB::table('optional_priority_subcategories')->count();
        /*$this->crud->addField([
            'name'=>'subcat_id',
            'label'=>'Subcategory ID',
            'type' => 'number',
            'default' =>$subid+1,
            'attributes'=>['readonly'=>'readonly',
                           ],
        ]);*/ //this is an autoincrement field, the crud panel will create the record and it will be generated at that point.
        
        $this->crud->addField([
            'label' => 'Category Name',// Table column heading
            'type' => 'select',
            'name' => 'cat_id', // The db column name
            'entity' =>'OptionalPriorityCategories',
            'attribute' =>'cat_name',
            'model' => 'App\Models\OptionalPriorityCategories',
        ]);
        
        $this->crud->addField([
            'name'=>'subcat_name',
            'label'=>'Subcategory Name',
            'type'=>'valid_textarea',
            'attributes' => [ 'req' => 'true']
        ]);

        $this->crud->addField([
            'name'=>'subcat_desc',
            'label'=>'Subcategory Description',
            'type'=>'textarea',
            'attributes' => [ 'req' => 'true']
        ]);

    

        $this->crud->addField([
            'name'=>'subcat_postamble',
            'label'=>'Subcategory Postamble',
            'type'=>'textarea',
        ]);

    }
    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false);
        $this->crud->addColumn([
            'name' => 'subcat_id',
            'label'=>"Subcategory ID",
            'type' =>'number'
        ]);
        $this->crud->addColumn([
            'label' => 'Category Name',// Table column heading
            'type' => 'select',
            'name' => 'OptionalPriorityCategories', // The db column name
            'entity' =>'OptionalPriorityCategories',
            'attribute' =>'cat_name',
            'model' => 'App\Models\OptionalPriorityCategories',
        ]);
        $this->crud->addColumn([
            'name' => 'subcat_name',
            'label'=>"Subcategory Name",
            'type' =>'strip_text'
        ]);
        $this->crud->addColumn([
            'name' => 'subcat_desc',
            'label'=>"Subcategory Description",
            'type' =>'Text'
        ]);
        $this->crud->addColumn([
            'name' => 'cat_id',
            'label'=>"Category ID",
            'type' =>'number'
        ]);
        $this->crud->addColumn([
            'name' => 'subcat_postamble',
            'label'=>"Subcategory Postamble",
            'type' =>'Text'
        ]);

    }
    
     use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as traitDestroy; }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        //delete all children starting with the leafmost objects. they have to be accessed using the id's of their parent records however (either the cloID or the courseID in this case)
        $opscID = filter_input(INPUT_SERVER,'PATH_INFO');        
        $opscID = explode("/",$opscID)[3];       
        $r = DB::table('optional_priorities')->where('subcat_id', '=', $opscID)->delete();
        //this deletes the record itself.
        return $this->crud->delete($id);
    }
}
