<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ModulesController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Admin\AdminAuthController;

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

Route::get('/', [IndexController::class, 'index'])->name('index');

Auth::routes(['verify' => true]);

Route::get('/home', [HomeController::class, 'home'])->name('home');
Route::get('/installation', [HomeController::class, 'install'])->name('installation');
Route::put('/installation/process', [HomeController::class, 'installUpdate'])->name('installation-process');
Route::get('/info', [HomeController::class, 'info'])->name('info');
Route::put('/info/process', [HomeController::class, 'infoProcess'])->name('info-process');
Route::post('/home/step-3-change', [HomeController::class, 'step3dateChange'])->name('userStep3Change');
Route::post('/home/step-6-change', [HomeController::class, 'step6DateChange'])->name('userStep6Change');
Route::post('/home/step-6-accept', [HomeController::class, 'step6DateAccept'])->name('userStep6Accept');

Route::group(['prefix' => 'modules'], function() {
    Route::get('/{id}',[ModulesController::class, 'index'])->name('modules');
    Route::get('/{id}/quiz', [ModulesController::class, 'quiz'])->name('quiz');
    Route::put('/{id}/restart', [ModulesController::class, 'restartQuiz'])->name('quiz_restart');
    Route::put('/{id}/rewatch', [ModulesController::class, 'restartVideo'])->name('video_restart');
    Route::post('/add-message', [ModulesController::class, 'add_message'])->name('add-message');
    Route::post('/mod-note',[ModulesController::class, 'moduleNote'])->name('module-note');
    Route::post('/answer', [ModulesController::class, 'store'])->name('answer');
    Route::post('/upload', [ModulesController::class, 'uploadFiles'])->name('upload-files');
    Route::post('/ajax', [ModulesController::class, 'AjaxUpload'])->name('ajax-upload');
    Route::post('/ajax-delete', [ModulesController::class, 'AjaxDelete'])->name('ajax-delete');
    Route::post('/delete', [ModulesController::class, 'deleteFiles'])->name('delete-file');
    Route::post('/submit-review', [ModulesController::class, 'submitReview'])->name('submit-review');
    Route::post('/revise-date', [ModulesController::class, 'reviseDate'])->name('revise-date');
});
Route::get('request-cert', [ModulesController::class, 'requestCert'])->name('request-cert');
Route::get('/final', [ModulesController::class, 'finalTest'])->name('final-test');
Route::post('/final/post', [ModulesController::class, 'finalPost'])->name('final-post');
Route::get('/results', [ModulesController::class, 'quizResults'])->name('result');

// Admin section
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function() {
   Route::get('/login', [AdminAuthController::class, 'getLogin'])->name('adminLogin');
   Route::post('/login', [AdminAuthController::class, 'postLogin'])->name('adminLoginPost');
   Route::post('/logout', [AdminAuthController::class, 'adminLogout'])->name('adminLogout');
   Route::get('/forgot-password', [AdminAuthController::class, 'passwordForgot'])->name('admin-forgot');
   Route::post('/forgot-password/process',[AdminAuthController::class, 'passwordForgotProcess'])->name('admin-forgot-process');
   Route::get('/reset-password', [AdminAuthController::class, 'passwordReset'])->name('admin-reset');
   Route::post('/reset-password/process', [AdminAuthController::class, 'passwordResetProcess'])->name('admin-reset-process');

   Route::group(['middleware' => 'adminauth'], function() {
      Route::get('/', [AdminAuthController::class, 'adminIndex'])->name('adminDashboard');
      Route::get('/user/{id}', [AdminAuthController::class, 'userDetail'])->name('userDetail');
      Route::get('/user/{id}/{step}', [AdminAuthController::class, 'userDetailStep'])->name('userDetailStep');
      Route::post('/user/update', [AdminAuthController::class, 'userDetailPost'])->name('userDetailPost');
      Route::post('/user/appt', [AdminAuthController::class, 'step3date'])->name('apptSet');
      Route::post('/user/final-suggest', [AdminAuthController::class, 'step6Date'])->name('final-suggest');
      Route::post('/user/final', [AdminAuthController::class,'finalInspectDate'])->name('final-inspect');
      Route::post('/user/admin-msg', [AdminAuthController::class, 'admin_msg'])->name('admin-msg');
      Route::post('/user/admin-module-note', [AdminAuthController::class, 'adminModuleNote'])->name('admin-module-note');

      Route::get('/list', [AdminAuthController::class, 'adminList'])->name('admin-list');
      Route::get('/list/{id}', [AdminAuthController::class, 'adminIndividual'])->name('admin-individual');
      Route::post('/list/delete', [AdminAuthController::class, 'adminIndividualDelete'])->name('admin-delete');

      Route::post('/list/process', [AdminAuthController::class, 'adminUpdate'])->name('admin-update');

      Route::get('/create', [AdminAuthController::class, 'adminCreate'])->name('admin-new');
      Route::post('/create/process', [AdminAuthController::class, 'adminCreateProcess'])->name('admin-create');   });
});


Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
