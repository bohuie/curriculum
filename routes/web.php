<?php

use App\Http\Controllers\HomeController;
use App\Models\Invite;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Mail\Invitation;
use App\Models\LearningOutcome;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('pages.landing');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/{course}/submit','CourseController@submit')->name('home.submit');

Route::get('/about', 'AboutController@index')->name('about');

Route::get('/faq', 'FAQController@index')->name('FAQ');
Route::get('/terms', 'TermsController@index')->name('terms');

// route to view a syllabus
Route::get('/syllabusGenerator/{syllabusId?}', 'SyllabusController@index')->name('syllabus');
// route to save a syllabus
Route::post('/syllabusGenerator/{syllabusId?}', 'SyllabusController@save')->name('syllabus.save');
// route to import course info into a syllabus
Route::get('/syllabusGenerator/import/course','SyllabusController@getCourseInfo');
// route to delete a syllabus
Route::delete('/syllabusGenerator/{syllabusId}', 'SyllabusController@destroy')->name('syllabus.delete');
// route to assign a syllabus collaborator
Route::post('/syllabus/{syllabusId}/assign','SyllabusUserController@store')->name('syllabus.assign');
// route to unassign a syllabus collaborator
Route::delete('/syllabi/{syllabusId}/unassign', 'SyllabusUserController@destroy')->name('syllabus.unassign');
// route to download a syllabus
Route::post('/syllabi/{syllabusId}/word','SyllabusController@syllabusToWordDoc')->name('syllabus.word');
// rout to duplicate syllabi
Route::get('/syllabus/{syllabusId}/duplicate','SyllabusController@duplicate')->name('syllabus.duplicate');
// route for syllabus collaborator functions
Route::get('/syllabusUser','SyllabusUserController@leave')->name('syllabusUser.leave');
Route::get('/syllabusUserTransfer','SyllabusUserController@transferOwnership')->name('syllabusUser.transferOwnership');

Route::resource('/programs','ProgramController');
Route::get('/programs/{program}/submit','ProgramController@submit')->name('programs.submit');
//PDF for Program summary
Route::get('/programs/{program}/pdf','ProgramController@pdf')->name('programs.pdf');
Route::get('/programs/{program}/duplicate','ProgramController@duplicate')->name('programs.duplicate');


Route::resource('/courses','CourseController');
Route::post('/courses', 'CourseController@store')->name('courses.store');

Route::post('/courses/{course}/assign','CourseUserController@store')->name('courses.assign');
Route::delete('/courses/{course}/unassign','CourseUserController@destroy')->name('courses.unassign');
Route::get('/courseUser','CourseUserController@leave')->name('courseUser.leave');
Route::get('/courseUserTransfer','CourseUserController@transferOwnership')->name('courseUser.transferOwnership');

Route::get('/courses/{course}/submit','CourseController@submit')->name('courses.submit');
Route::get('/courses/{course}/summary','CourseController@show')->name('courses.summary');
Route::post('/courses/{course}/outcomeDetails','CourseController@outcomeDetails')->name('courses.outcomeDetails');
Route::get('/courses/{course}/pdf','CourseController@pdf')->name('courses.pdf');
Route::get('/courses/{course}/remove','CourseController@removeFromProgram')->name('courses.remove');
Route::get('/courses/{course}/emailCourseInstructor','CourseController@emailCourseInstructor')->name('courses.emailCourseInstructor');
Route::get('/courses/{course}/duplicate','CourseController@duplicate')->name('courses.duplicate');

Route::resource('/lo','LearningOutcomeController')->only(['store','update','edit', 'destroy']);
Route::post('/import/clos', 'LearningOutcomeController@import')->name('courses.outcomes.import');

Route::resource('/plo','ProgramLearningOutcomeController');
Route::post('/import/plos', 'ProgramLearningOutcomeController@import')->name('program.outcomes.import');


Route::resource('/la','LearningActivityController');

Route::post('/ajax/custom_activities','CustomLearningActivitiesController@store' );
Route::post('/ajax/custom_methods','CustomAssessmentMethodsController@store' );

Route::resource('/am','AssessmentMethodController');

Route::resource('/outcomeMap','OutcomeMapController');
//Route for standards mapping
Route::resource('/standardsOutcomeMap', 'StandardsOutcomeMapController');

Route::resource('/mappingScale','MappingScaleController');
Route::post('/mappingScale/addDefaultMappingScale','MappingScaleController@addDefaultMappingScale')->name('mappingScale.addDefaultMappingScale');

Route::resource('/ploCategory','PLOCategoryController');

Route::resource('/programUser','ProgramUserController', ['except'=>'destroy']);
Route::post('/program/{programId}/collaborator/add', 'ProgramUserController@store')->name('programUser.add');
Route::delete('/programUser','ProgramUserController@delete')->name('programUser.destroy');
Route::get('/programUser','ProgramUserController@leave')->name('programUser.leave');
Route::get('/programUserTransfer','ProgramUserController@transferOwnership')->name('programUser.transferOwnership');

// Program wizard controller used to sent info from database to the blade page
Route::get('/programWizard/{program}/step1','ProgramWizardController@step1')->name('programWizard.step1');
Route::get('/programWizard/{program}/step2','ProgramWizardController@step2')->name('programWizard.step2');
Route::get('/programWizard/{program}/step3','ProgramWizardController@step3')->name('programWizard.step3');
Route::get('/programWizard/{program}/step4','ProgramWizardController@step4')->name('programWizard.step4');

// Program step3 add existing courses to a program
Route::post('/programWizard/{program}/step3/addCoursesToProgram', 'CourseProgramController@addCoursesToProgram')->name('courseProgram.addCoursesToProgram');
// Program step3 edit required status
Route::post('/programWizard/{program}/step3/editCourseRequired', 'CourseProgramController@editCourseRequired')->name('courseProgram.editCourseRequired');

// Program step 4 Used to get frequency distribution tables 
Route::get('/programWizard/{program}/get-courses', 'ProgramWizardController@getCourses');
Route::get('/programWizard/{program}/get-required', 'ProgramWizardController@getRequiredCourses');
Route::get('/programWizard/{program}/get-non-required', 'ProgramWizardController@getNonRequiredCourses');
Route::get('/programWizard/{program}/get-first', 'ProgramWizardController@getFirstCourses');
Route::get('/programWizard/{program}/get-second', 'ProgramWizardController@getSecondCourses');
Route::get('/programWizard/{program}/get-third', 'ProgramWizardController@getThirdCourses');
Route::get('/programWizard/{program}/get-fourth', 'ProgramWizardController@getFourthCourses');
Route::get('/programWizard/{program}/get-graduate', 'ProgramWizardController@getGraduateCourses');

// Program step 4 chart filters
// learning activity filter routes
Route::get('/programWizard/{program}/get-la', 'ProgramWizardController@getLearningActivities');
Route::get('/programWizard/{program}/get-la-first-year', 'ProgramWizardController@getFirstYearLearningActivities');
Route::get('/programWizard/{program}/get-la-second-year', 'ProgramWizardController@getSecondYearLearningActivities');
Route::get('/programWizard/{program}/get-la-third-year', 'ProgramWizardController@getThirdYearLearningActivities');
Route::get('/programWizard/{program}/get-la-fourth-year', 'ProgramWizardController@getFourthYearLearningActivities');
Route::get('/programWizard/{program}/get-la-graduate', 'ProgramWizardController@getGraduateLearningActivities');
// assessment method filter routes
Route::get('/programWizard/{program}/get-am', 'ProgramWizardController@getAssessmentMethods');
Route::get('/programWizard/{program}/get-am-first-year', 'ProgramWizardController@getAssessmentMethodsFirstYear');
Route::get('/programWizard/{program}/get-am-second-year', 'ProgramWizardController@getAssessmentMethodsSecondYear');
Route::get('/programWizard/{program}/get-am-third-year', 'ProgramWizardController@getAssessmentMethodsThirdYear');
Route::get('/programWizard/{program}/get-am-fourth-year', 'ProgramWizardController@getAssessmentMethodsFourthYear');
Route::get('/programWizard/{program}/get-am-graduate', 'ProgramWizardController@getAssessmentMethodsGraduate');
// optional priorities filter routes
Route::get('/programWizard/{program}/get-op', 'ProgramWizardController@getOptionalPriorities');
Route::get('/programWizard/{program}/get-op-first-year', 'ProgramWizardController@getOptionalPrioritiesFirstYear');

// Course wizard controller used to sent info from database to the blade page
Route::get('/courseWizard/{course}/step1','CourseWizardController@step1')->name('courseWizard.step1');
Route::get('/courseWizard/{course}/step2','CourseWizardController@step2')->name('courseWizard.step2');
Route::get('/courseWizard/{course}/step3','CourseWizardController@step3')->name('courseWizard.step3');
Route::get('/courseWizard/{course}/step4','CourseWizardController@step4')->name('courseWizard.step4');
Route::get('/courseWizard/{course}/step5','CourseWizardController@step5')->name('courseWizard.step5');
Route::get('/courseWizard/{course}/step6','CourseWizardController@step6')->name('courseWizard.step6');
Route::get('/courseWizard/{course}/step7','CourseWizardController@step7')->name('courseWizard.step7');


// Save optional PLOs
Route::post('/optionals','OptionalPriorities@store')->name('storeOptionalPLOs');

// Invatation route
Route::get('/invite', 'InviteController@index')->name('requestInvitation');

// route used to sent the invitation email
Route::post('/invitations','InviteController@store')->name('storeInvitation');

// UnderConstruction page
Route::get('/construction', function () {
    return view('pages.construction');
});

// Admin Email Page
Route::get('/email','AdminEmailController@index')->name('email');
Route::post('/email', 'AdminEmailController@send')->name('email.send');

Auth::routes();

// register backpack auth routes manually 
Route::group(['middleware' => 'web', 'prefix' => config('backpack.base.route_prefix')], function () {
    Route::auth();
    Route::get('logout', 'Auth\LoginController@logout');
});

// account information page and update method
// *** Routes not working local, but work on testing/staging.. ***
// Route::get('/accountInformation',[AccountInformationController::class, 'index'])->name('accountInformation');
// Route::post('/accountInformation-update',[AccountInformationController::class, 'update'])->name('accountInformation.update');
// *** These Routes work locally but not on staging ***
Route::get('/accountInformation','auth\AccountInformationController@index')->name('accountInformation');
Route::post('/accountInformation-update','auth\AccountInformationController@update')->name('accountInformation.update');

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; //Return anything
});