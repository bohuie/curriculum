<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProgramRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\MappingScale;

/**
 * Class ProgramCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProgramCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Program::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/program');
        CRUD::setEntityNameStrings('program', 'programs');
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
            'name' => 'program', // The db column name
            'label' => "Program", // Table column heading
            'type' => 'Text',
            'searchLogic' => function($query, $column, $searchTerm){
                $query ->orWhere('program_id', 'like', '%'.$searchTerm.'%');
            }
          ]);

        $this->crud->addColumn([
            'name' => 'faculty', // The db column name
            'label' => "Faculty/School", // Table column heading
            'type' => 'Text'
          ]);
        
        $this->crud->addColumn([
            'name' => 'department', // The db column name
            'label' => "Department", // Table column heading
            'type' => 'Text'
          ]);

        $this->crud->addColumn([
            'name' => 'level', // The db column name
            'label' => "Level", // Table column heading
            'type' => 'Text',
            'searchLogic' => function($query, $column, $searchTerm){
                $query ->orWhere('level', 'like', '%'.$searchTerm.'%');
            }
          ]);
        
        $this->crud->addColumn([   // radio
            'name'        => 'status', // the name of the db column
            'label'       => 'Status', // the input label
            'type'        => 'radio',
            'options'     => [
                // the key will be stored in the db, the value will be shown as label; 
                -1 => "❗Not Configured",
                1 => "✔️Active"
            ],
            // optional
            //'inline'      => false, // show the radios all on the same line?
        ]);

        $this->crud->addColumn([
            'name'      => 'row_number',
            'type'      => 'row_number',
            'label'     => '#',
            'orderable' => false,
        ])->makeFirstColumn();

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
        CRUD::setValidation(ProgramRequest::class);

        $this->crud->addField([
            'name' => 'program', // The db column name
            'label' => "Program Title", // Table column heading
            'type' => 'valid_text',
            'attributes' => [ 'req' => 'true']
          ]);

        $this->crud->addField([
            'name' => 'faculty', // The db column name
            'label' => "Faculty/School", // Table column heading
            'type' => 'radio',
            'options'     => [
                        // the key will be stored in the db, the value will be shown as label; 
                        "School of Engineering" => "School of Engineering",
                        "Okanagan School of Education" => "Okanagan School of Education",
                        "Faculty of Arts and Social Sciences" => "Faculty of Arts and Social Sciences",
                        "Faculty of Creative and Critical Studies" => "Faculty of Creative and Critical Studies",
                        "Faculty of Science" => "Faculty of Science",
                        "School of Health and Exercise Sciences" => "School of Health and Exercise Sciences",
                        "School of Nursing" => "School of Nursing",
                        "School of Social Work" => "School of Social Work",
                        "Faculty of Management" => "Faculty of Management",
                        "College of Graduate studies" => "College of Graduate studies",
                        "Faculty of Arts and Sciences" => "Faculty of Arts and Sciences",
                        "Faculty of Medicine" => "Faculty of Medicine",
                        "Other" => "Other"
                    ],
            
          ]);
        
        $this->crud->addField([
            'name' => 'department', // The db column name
            'label' => "Department", // Table column heading
            'type' => 'radio',
            'options'     => [
                // the key will be stored in the db, the value will be shown as label; 
                "Community, Culture and Global Studies" => "Community, Culture and Global Studies",
                "Economics, Philosophy and Political Science" => "Economics, Philosophy and Political Science",
                "History and Sociology" => "History and Sociology",
                "Psychology" => "Psychology",
                "Creative Studies" => "Creative Studies",
                "Languages and World Literature" => "Languages and World Literature",
                "English and Cultural Studies" => "English and Cultural Studies",
                "Biology" => "Biology",
                "Chemistry" => "Chemistry",
                "Computer Science, Mathematics, Physics and Statistics" => "Computer Science, Mathematics, Physics and Statistics",
                "Earth, Environmental and Geographic Sciences" => "Earth, Environmental and Geographic Sciences",
                "Other" => "Other"
            ],
    
          ]);

        $this->crud->addField([   // CustomHTML
            'name'  => 'helper',
            'type'  => 'custom_html',
            'value' => '<small class="form-text text-muted">The department field is optional, you do not have to select an option</small>'
        ]);

        $this->crud->addField([
            'name' => 'level', // The db column name
            'label' => "Level", // Table column heading
            'type' => 'radio',
            'options'     => [
                // the key will be stored in the db, the value will be shown as label; 
                "Undergraduate" => "Undergraduate",
                "Graduate" => "Graduate",
                "Other" => "Other"
                
            ],
          ]);
        
        $this->crud->addField([   // radio
            'name'        => 'status', // the name of the db column
            'label'       => 'Status', // the input label
            'type'        => 'radio',
            'options'     => [
                // the key will be stored in the db, the value will be shown as label; 
                -1 => "Not Configured",
                1 => "Active"
            ],
            // optional
            //'inline'      => false, // show the radios all on the same line?
        ]);

        $this->crud->addField([  
            // any type of relationship
            'name'         => 'users', // name of relationship method in the model
            'type'         => 'select2_multiple',
            'label'        => 'Program Administrators', // Table column heading
            // OPTIONAL
            'entity'       => 'users', // the method that defines the relationship in your Model
            'attribute'    => 'email', // foreign key attribute that is shown to user
            'model'        => "App\Models\User", // foreign key model
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
        
        $prgID = filter_input(INPUT_SERVER,'PATH_INFO');        
        $prgID = explode("/",$prgID)[3];
        
        $this->crud->addField([
                    'name'    => 'MappingScaleLevels',
                    'type'    => 'check_details',
                    'label'   => 'Map Scales',
                    'entity'    => 'mappingScaleLevels', // the method that defines the relationship in your Model
                    'model'     => "App\Models\MappingScale", // foreign key model
                    'attribute' => [
                        'title', // foreign key attribute that is shown to user
                        'colour',
                    ],
                    'category_relation' => 'mapping_scale_categories-mapping_scale_categories_id-msc_title-description', 
                    //the Entity and foreign key used to categorize the checkboxes, if any. followed by category header and hint respectively
                    'pivot'     => true, // on create&update, do you need to add/delete pivot table entries?
        ]);
        
        $this->crud->addField([
                    'name'    => 'Courses',
                    'type'    => 'check_details',
                    'label'   => 'Courses',
                    'entity'    => 'courses', // the method that defines the relationship in your Model
                    'model'     => "App\Models\Course", // foreign key model
                    'attribute' => [
                        'course_code', // foreign key attribute that is shown to user
                        'course_num',
                        'course_title',
                    ],
                    'category_attribute' => 'course_code', //the attribute to group by 
                    'pivot'     => true, // on create&update, do you need to add/delete pivot table entries?
        ]);
     
        
    }

    protected function setupShowOperation()
    {
        $this->crud->addColumn([
            'name' => 'program', // The db column name
            'label' => "Program", // Table column heading
            'type' => 'Text'
          ]);

        $this->crud->addColumn([
            'name' => 'faculty', // The db column name
            'label' => "Faculty/School", // Table column heading
            'type' => 'Text'
          ]);
        
        $this->crud->addColumn([
            'name' => 'department', // The db column name
            'label' => "Department", // Table column heading
            'type' => 'Text'
          ]);

        $this->crud->addColumn([
            'name' => 'level', // The db column name
            'label' => "Level", // Table column heading
            'type' => 'Text'
          ]);
        
        $this->crud->addColumn([   // radio
            'name'        => 'status', // the name of the db column
            'label'       => 'Status', // the input label
            'type'        => 'radio',
            'options'     => [
                // the key will be stored in the db, the value will be shown as label; 
                -1 => "❗Not Configured",
                1 => "✔️Active"
            ],
            // optional
            //'inline'      => false, // show the radios all on the same line?
        ]);

        $this->crud->addColumn([  
            // any type of relationship
            'name'         => 'users', // name of relationship method in the model
            'type'         => 'select_multiple',
            'label'        => 'Program Administrators', // Table column heading
            // OPTIONAL
            'entity'       => 'users', // the method that defines the relationship in your Model
            'attribute'    => 'email', // foreign key attribute that is shown to user
            'model'        => App\Models\User::class, // foreign key model
         ]);
    }
}
