<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('course', 'CourseCrudController');
    Route::crud('program', 'ProgramCrudController');
    Route::crud('user', 'UserCrudController');
    Route::crud('learningOutcome', 'LearningOutcomeCrudController');
   // Route::crud('mappingScaleProgram', 'MappingScaleProgramCrudController');
    Route::crud('optional-priority', 'OptionalPriorityCrudController');
    Route::crud('subcategories', 'SubcategoriesCrudController');
    Route::crud('categories', 'CategoriesCrudController');
    Route::crud('standard-category', 'StandardCategoryCrudController');
    Route::crud('standards-scale-category', 'StandardsScaleCategoryCrudController');
    Route::crud('mapping-scales', 'MappingScaleCrudController');
    Route::crud('mapping-scale-category', 'MappingScaleCategoryCrudController');
    Route::crud('custom-assessment-methods', 'CustomAssessmentMethodsCrudController');
    Route::crud('custom-learning-activities', 'CustomLearningActivitiesCrudController');
}); // this should be the absolute last line of this file