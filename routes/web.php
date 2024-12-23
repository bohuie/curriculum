<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AdminEmailController;
use App\Http\Controllers\AssessmentMethodController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseProgramController;
use App\Http\Controllers\CourseUserController;
use App\Http\Controllers\CourseWizardController;
use App\Http\Controllers\CustomAssessmentMethodsController;
use App\Http\Controllers\CustomLearningActivitiesController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\LearningActivityController;
use App\Http\Controllers\LearningOutcomeController;
use App\Http\Controllers\MappingScaleController;
use App\Http\Controllers\OptionalPriorities;
use App\Http\Controllers\OutcomeMapController;
use App\Http\Controllers\PLOCategoryController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProgramLearningOutcomeController;
use App\Http\Controllers\ProgramUserController;
use App\Http\Controllers\ProgramWizardController;
use App\Http\Controllers\StandardsOutcomeMapController;
use App\Http\Controllers\SyllabusController;
use App\Http\Controllers\SyllabusUserController;
use App\Http\Controllers\TermsController;
use App\Mail\Invitation;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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

//Currently need to force HTTPS for Unit Testing to function properly, looking into a fix now.
//URL::forceScheme('https');

Route::get('/', function () {
    return view('pages.landing');
});

Auth::routes(['verify' => true]);

// Route to get what programs a course belongs to
Route::get('/course/{courseId}/programs', [CourseController::class, 'getPrograms']);

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/home/{course}/submit', [CourseController::class, 'submit'])->name('home.submit');

Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/faq', [FAQController::class, 'index'])->name('FAQ');
Route::get('/terms', [TermsController::class, 'index'])->name('terms');

// route to view a syllabus
Route::get('/syllabusGenerator/{syllabusId?}', [SyllabusController::class, 'index'])->name('syllabus');
// route to save a syllabus
Route::post('/syllabusGenerator/{syllabusId?}', [SyllabusController::class, 'save'])->name('syllabus.save');
// route to import course info into a syllabus
Route::get('/syllabusGenerator/import/course', [SyllabusController::class, 'getCourseInfo']);
// route to delete a syllabus
Route::delete('/syllabusGenerator/{syllabusId}', [SyllabusController::class, 'destroy'])->name('syllabus.delete');
// route to assign a syllabus collaborator
Route::post('/syllabus/{syllabusId}/assign', [SyllabusUserController::class, 'store'])->name('syllabus.assign');
// route to unassign a syllabus collaborator
Route::delete('/syllabi/unassign', [SyllabusUserController::class, 'destroy'])->name('syllabus.unassign');
// route to download a syllabus
Route::post('/syllabi/{syllabusId}/{ext}', [SyllabusController::class, 'download'])->name('syllabus.download');
// rout to duplicate syllabi
Route::get('/syllabus/{syllabusId}/duplicate', [SyllabusController::class, 'duplicate'])->name('syllabus.duplicate');
// route for syllabus collaborator functions
Route::post('/syllabusUser', [SyllabusUserController::class, 'leave'])->name('syllabusUser.leave');
Route::post('/syllabusUserTransfer', [SyllabusUserController::class, 'transferOwnership'])->name('syllabusUser.transferOwnership');

Route::resource('/programs', ProgramController::class);
Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
Route::get('/programs/{program}/submit', [ProgramController::class, 'submit'])->name('programs.submit');
Route::post('/programs/{program}/update', [ProgramController::class, 'update'])->name('programs.update');
Route::delete('/programs/{program}/delete', [ProgramController::class, 'destroy'])->name('programs.destroy');
// Program Summary PDF routes
Route::get('/programs/{program}/pdf', [ProgramController::class, 'pdf'])->name('programs.pdf');
Route::delete('/programs/{program}/pdf', [ProgramController::class, 'deletePDF'])->name('programs.delete.pdf');

// Program Summary Spreadsheet routes
Route::get('/programs/{program}/spreadsheet', [ProgramController::class, 'spreadsheet'])->name('programs.spreadsheet');
Route::delete('/programs/{program}/spreadsheet', [ProgramController::class, 'delSpreadsheet'])->name('programs.delete.spreadsheet');
Route::get('/programs/{program}/downloadUserGuide', [ProgramController::class, 'downloadUserGuide'])->name('programs.downloadUserGuide');

// Program Summary raw data spreadsheet routes
Route::get('/programs/{program}/dataSpreadsheet', [ProgramController::class, 'dataSpreadsheet'])->name('programs.dataSpreadsheet');

Route::get('/programs/{program}/duplicate', [ProgramController::class, 'duplicate'])->name('programs.duplicate');

Route::resource('/courses', CourseController::class);
Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');

Route::post('/courses/{course}/assign', [CourseUserController::class, 'store'])->name('courses.assign');
Route::delete('/courses/{course}/unassign', [CourseUserController::class, 'destroy'])->name('courses.unassign');
Route::get('/courseUser', [CourseUserController::class, 'leave'])->name('courseUser.leave');
Route::post('/courseUserTransfer', [CourseUserController::class, 'transferOwnership'])->name('courseUser.transferOwnership');

Route::get('/courses/{course}/submit', [CourseController::class, 'submit'])->name('courses.submit');
Route::post('/courses/{course}/outcomeDetails', [CourseController::class, 'outcomeDetails'])->name('courses.outcomeDetails');
Route::post('/courses/{course}/amReorder', [CourseController::class, 'amReorder'])->name('courses.amReorder');
Route::post('/courses/{course}/loReorder', [CourseController::class, 'loReorder'])->name('courses.loReorder');
Route::post('/courses/{course}/tlaReorder', [CourseController::class, 'tlaReorder'])->name('courses.tlaReorder');
Route::get('/courses/{course}/pdf', [CourseController::class, 'pdf'])->name('courses.pdf');

// Route for spreadsheet download in course 
Route::get('/courses/{course}/dataSpreadsheet', [CourseController::class, 'dataSpreadsheet'])->name('courses.dataSpreadsheet');

Route::delete('/courses/{course}/pdf', [CourseController::class, 'deletePDF'])->name('courses.delete.pdf');
Route::get('/courses/{course}/remove', [CourseController::class, 'removeFromProgram'])->name('courses.remove');
Route::get('/courses/{course}/emailCourseInstructor', [CourseController::class, 'emailCourseInstructor'])->name('courses.emailCourseInstructor');
Route::post('/courses/{course}/duplicate', [CourseController::class, 'duplicate'])->name('courses.duplicate');
Route::delete('/courses/{course}/destroy', [CourseController::class, 'destroy'])->name('courses.destroy');

// Route::resource('/lo','LearningOutcomeController')->only(['store','update','edit', 'destroy']);
Route::resource('/lo', LearningOutcomeController::class);
Route::post('/import/clos', [LearningOutcomeController::class, 'import'])->name('courses.outcomes.import');
Route::post('/store/clos', [LearningOutcomeController::class, 'store'])->name('courses.outcomes.store');


Route::resource('/plo', ProgramLearningOutcomeController::class);
Route::post('/plo/store', [ProgramLearningOutcomeController::class, 'store'])->name('program.outcomes.store');
Route::post('/import/plos', [ProgramLearningOutcomeController::class, 'import'])->name('program.outcomes.import');
Route::delete('/plo/{program}/delete', [ProgramLearningOutcomeController::class, 'destroy'])->name('plo.destroy');
Route::post('/plo/{program}/update', [ProgramLearningOutcomeController::class, 'update'])->name('plo.update');

Route::resource('/la', LearningActivityController::class);
Route::post('/la/store', [LearningActivityController::class, 'store'])->name('la.store');

Route::post('/ajax/custom_activities', [CustomLearningActivitiesController::class, 'store']);
Route::post('/ajax/custom_methods', [CustomAssessmentMethodsController::class, 'store']);
Route::post('/store/la', [LearningActivityController::class, 'store'])->name('la.store');

Route::resource('/am', AssessmentMethodController::class);
Route::post('/am/store', [AssessmentMethodController::class, 'store'])->name('am.store');

Route::resource('/outcomeMap', OutcomeMapController::class);
Route::post('/store/OutcomeMap', [OutcomeMapController::class, 'store'])->name('outcomeMap.store');
//Route for standards mapping
Route::resource('/standardsOutcomeMap', StandardsOutcomeMapController::class);
Route::post('/store/standardsOutcomeMap', [StandardsOutcomeMapController::class, 'store'])->name('standardsOutcomeMap.store');

Route::resource('/mappingScale', MappingScaleController::class);
Route::post('/mappingScale/store', [MappingScaleController::class, 'store'])->name('program.mappingScale.store');
Route::post('/mappingScale/addDefaultMappingScale', [MappingScaleController::class, 'addDefaultMappingScale'])->name('mappingScale.addDefaultMappingScale');
Route::delete('/mappingScale/{program}/delete', [MappingScaleController::class, 'destroy'])->name('mappingScale.destroy');
Route::post('/mappingScale/{program}/update', [MappingScaleController::class, 'update'])->name('mappingScale.update');

Route::resource('/ploCategory', PLOCategoryController::class);
Route::post('/ploCategory/store', [PLOCategoryController::class, 'store'])->name('program.category.store');
Route::delete('/ploCategory/{program}/delete', [PLOCategoryController::class, 'destroy'])->name('program.category.destroy');
Route::post('/ploCategory/{program}/update', [PLOCategoryController::class, 'update'])->name('program.category.update');

Route::resource('/programUser', ProgramUserController::class);
Route::post('/program/{programId}/collaborator/add', [ProgramUserController::class, 'store'])->name('programUser.add');
Route::delete('/programUser/delete', [ProgramUserController::class, 'delete'])->name('programUser.destroy');
Route::get('/programUser/leave', [ProgramUserController::class, 'leave'])->name('programUser.leave');
Route::get('/programUserTransfer', [ProgramUserController::class, 'transferOwnership'])->name('programUser.transferOwnership');

// Program wizard controller used to sent info from database to the blade page
Route::get('/programWizard/{program}/step1', [ProgramWizardController::class, 'step1'])->name('programWizard.step1');
Route::get('/programWizard/{program}/step2', [ProgramWizardController::class, 'step2'])->name('programWizard.step2');
Route::get('/programWizard/{program}/step3', [ProgramWizardController::class, 'step3'])->name('programWizard.step3');
Route::get('/programWizard/{program}/step4', [ProgramWizardController::class, 'step4'])->name('programWizard.step4');

// Program step3 add existing courses to a program
Route::post('/programWizard/{program}/step3/addCoursesToProgram', [CourseProgramController::class, 'addCoursesToProgram'])->name('courseProgram.addCoursesToProgram');
// Program step3 edit required status
Route::post('/programWizard/{program}/step3/editCourseRequired', [CourseProgramController::class, 'editCourseRequired'])->name('courseProgram.editCourseRequired');

// Program step 4 Used to get frequency distribution tables
Route::get('/programWizard/{program}/get-courses', [ProgramWizardController::class, 'getCourses']);
Route::get('/programWizard/{program}/get-required', [ProgramWizardController::class, 'getRequiredCourses']);
Route::get('/programWizard/{program}/get-non-required', [ProgramWizardController::class, 'getNonRequiredCourses']);
Route::get('/programWizard/{program}/get-first', [ProgramWizardController::class, 'getFirstCourses']);
Route::get('/programWizard/{program}/get-second', [ProgramWizardController::class, 'getSecondCourses']);
Route::get('/programWizard/{program}/get-third', [ProgramWizardController::class, 'getThirdCourses']);
Route::get('/programWizard/{program}/get-fourth', [ProgramWizardController::class, 'getFourthCourses']);
Route::get('/programWizard/{program}/get-graduate', [ProgramWizardController::class, 'getGraduateCourses']);

// Program step 4 chart filters
// learning activity filter routes
Route::get('/programWizard/{program}/get-la', [ProgramWizardController::class, 'getLearningActivities']);
Route::get('/programWizard/{program}/get-la-first-year', [ProgramWizardController::class, 'getFirstYearLearningActivities']);
Route::get('/programWizard/{program}/get-la-second-year', [ProgramWizardController::class, 'getSecondYearLearningActivities']);
Route::get('/programWizard/{program}/get-la-third-year', [ProgramWizardController::class, 'getThirdYearLearningActivities']);
Route::get('/programWizard/{program}/get-la-fourth-year', [ProgramWizardController::class, 'getFourthYearLearningActivities']);
Route::get('/programWizard/{program}/get-la-graduate', [ProgramWizardController::class, 'getGraduateLearningActivities']);
// assessment method filter routes
Route::get('/programWizard/{program}/get-am', [ProgramWizardController::class, 'getAssessmentMethods']);
Route::get('/programWizard/{program}/get-am-first-year', [ProgramWizardController::class, 'getAssessmentMethodsFirstYear']);
Route::get('/programWizard/{program}/get-am-second-year', [ProgramWizardController::class, 'getAssessmentMethodsSecondYear']);
Route::get('/programWizard/{program}/get-am-third-year', [ProgramWizardController::class, 'getAssessmentMethodsThirdYear']);
Route::get('/programWizard/{program}/get-am-fourth-year', [ProgramWizardController::class, 'getAssessmentMethodsFourthYear']);
Route::get('/programWizard/{program}/get-am-graduate', [ProgramWizardController::class, 'getAssessmentMethodsGraduate']);
// Ministry Standards
Route::get('/programWizard/{program}/get-ms', [ProgramWizardController::class, 'getMinistryStandards']);
// optional priorities filter routes
Route::get('/programWizard/{program}/get-op', [ProgramWizardController::class, 'getOptionalPriorities']);
Route::get('/programWizard/{program}/get-op-first-year', [ProgramWizardController::class, 'getOptionalPrioritiesFirstYear']);
Route::get('/programWizard/{program}/get-op-second-year', [ProgramWizardController::class, 'getOptionalPrioritiesSecondYear']);
Route::get('/programWizard/{program}/get-op-third-year', [ProgramWizardController::class, 'getOptionalPrioritiesThirdYear']);
Route::get('/programWizard/{program}/get-op-fourth-year', [ProgramWizardController::class, 'getOptionalPrioritiesFourthYear']);
Route::get('/programWizard/{program}/get-op-graduate', [ProgramWizardController::class, 'getOptionalPrioritiesGraduate']);

// Course wizard controller used to sent info from database to the blade page
Route::get('/courseWizard/{course}/step1', [CourseWizardController::class, 'step1'])->name('courseWizard.step1');
Route::get('/courseWizard/{course}/step2', [CourseWizardController::class, 'step2'])->name('courseWizard.step2');
Route::get('/courseWizard/{course}/step3', [CourseWizardController::class, 'step3'])->name('courseWizard.step3');
Route::get('/courseWizard/{course}/step4', [CourseWizardController::class, 'step4'])->name('courseWizard.step4');
Route::get('/courseWizard/{course}/step5', [CourseWizardController::class, 'step5'])->name('courseWizard.step5');
Route::get('/courseWizard/{course}/step6', [CourseWizardController::class, 'step6'])->name('courseWizard.step6');
Route::get('/courseWizard/{course}/step7', [CourseWizardController::class, 'step7'])->name('courseWizard.step7');

// Save optional PLOs
Route::post('/optionals', [OptionalPriorities::class, 'store'])->name('storeOptionalPLOs');

// Invatation route
Route::get('/invite', [InviteController::class, 'index'])->name('requestInvitation');

// route used to sent the invitation email
Route::post('/invitations', [InviteController::class, 'store'])->name('storeInvitation');

// UnderConstruction page
Route::get('/construction', function () {
    return view('pages.construction');
});

// Admin Email Page
Route::get('/email', [AdminEmailController::class, 'index'])->name('email');
Route::post('/email', [AdminEmailController::class, 'send'])->name('email.send');

Auth::routes();

// register backpack auth routes manually
Route::middleware('web')->prefix(config('backpack.base.route_prefix'))->group(function () {
    Route::auth();
    Route::get('logout', [Auth\LoginController::class, 'logout']);
});

// account information page and update method
// *** Routes not working local, but work on testing/staging.. ***
// Route::get('/accountInformation',[AccountInformationController::class, 'index'])->name('accountInformation');
// Route::post('/accountInformation-update',[AccountInformationController::class, 'update'])->name('accountInformation.update');
// *** These Routes work locally but not on staging ***
Route::get('/accountInformation', [Auth\AccountInformationController::class, 'index'])->name('accountInformation');
Route::post('/accountInformation-update', [Auth\AccountInformationController::class, 'update'])->name('accountInformation.update');

Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');

    return 'DONE'; //Return anything
});
