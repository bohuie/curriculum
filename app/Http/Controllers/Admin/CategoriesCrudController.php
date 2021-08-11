<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoriesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;

use App\Models\OptionalPriorityCategories;
/**
 * Class CategoriesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CategoriesCrudController extends CrudController
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
        CRUD::setModel(\App\Models\OptionalPriorityCategories::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/categories');
        CRUD::setEntityNameStrings('categories', 'categories');
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
        $this->crud->addColumn([
            'name' =>'cat_id',
            'label'=>"Category ID",
            'type' =>'number',
            'searchLogic' => function($query, $column, $searchTerm){
                $query ->orWhere('cat_id', 'like', '%'.$searchTerm.'%');
            }
        ]);

        $this->crud->addColumn([
            'name'=>'cat_name',
            'label'=>"Category Name",
            'type'=>'Text',
            'searchLogic' => function($query, $column, $searchTerm){
                $query ->orWhere('cat_name', 'like', '%'.$searchTerm.'%');
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
        CRUD::setValidation(CategoriesRequest::class);
       // $catId = \DB::table('optional_priority_categories')->count();
        
       /* $this->crud->addField([
            'name'=>'cat_id',
            'label'=>'Category ID',
            'type' =>'number',
            'default'=>$catId+1,
            'attributes'=>['readonly'=>'readonly',
                           ],
        ]);*/

        $this->crud->addField([
            'name'=>'cat_name',
            'label'=>'Category Name&nbsp;&nbsp;<span style=color:red>*</span>',
            'type' =>'valid_text',
            'attributes' => [ 'req' => 'true']
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
            'name'=>'cat_id',
            'label'=>'Category ID',
            'type' =>'number',            
            'attributes'=>['readonly'=>'readonly',
                           ],
        ]);

        $this->crud->addField([
            'name'=>'cat_name',
            'label'=>'Category Name&nbsp;&nbsp;<span style=color:red>*</span>',
            'type' =>'valid_text',
            'attributes' => [ 'req' => 'true']
        ]);
    }
    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false);
        $this->crud->addColumn([
            'name' =>'cat_id',
            'label' =>"Category ID",
            'type' =>'number',
        ]);
        $this->crud->addColumn([
            'name' =>'cat_name',
            'label' =>"Category Name",
            'type' =>'Text',
        ]);
    }
    
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as traitDestroy; }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        //delete all children starting with the leafmost objects. they have to be accessed using the id's of their parent records however (either the cloID or the courseID in this case)
        $opcID = filter_input(INPUT_SERVER,'PATH_INFO');        
        $opcID = explode("/",$opcID)[3];
        
        //deleting records
        $r = DB::table('optional_priority_subcategories')->where('cat_id', '=', $opcID)->delete();
        //this deletes the course record itself.
        return $this->crud->delete($id);
    }
}
