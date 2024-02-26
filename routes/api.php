<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttacheController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\InternController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\SupervisorMiddleware;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/attachedetails',[AttacheController::class,'store']); 

//Applying for Internship


Route::controller(AuthController::class)->group(function(){
//Authcontroller routes 
Route::post('login','login');
Route::post('register','register');
Route::post('forgot/password','forgot');
Route::post('reset/password','reset');
Route::get('reset-password-link/{token}', 'resetPasswordLink')->name('reset');


Route::get('remember/{id}/me','remember');

});



 //profil change

Route::middleware(['auth:sanctum'])->group(function(){


Route::post('create/profile',[ProfileController::class,'profile']);//creating my profile
Route::put('update/my/profile',[ProfileController::class,'updateprofile']);//updating my profile
Route::get('my/profile',[ProfileController::class,'myprofile']);//get your profile

Route::post('/logout', [AuthController::class, 'logout']); //logout
Route::post('task/comment',[CommentController::class,'store'])->middleware('auth:sanctum'); //comment
});


Route::get('roles',[AdminController::class,'getroles']);



Route::middleware('auth:sanctum','is_admin')->group(function(){
//admin routes
    Route::get('/applications',[AdminController::class,'applications']);
Route::get('interviews/conducted',[AdminController::class,'interview_conducted']);
Route::get('unassigned/interns',[AdminController::class,'unassigned']);
Route::delete('delete/application/{id}',[AdminController::class,'delete_application']);
Route::post('create/supervisor',[AdminController::class,'createSupervisor']);
Route::delete('supervisor/{id}/destroy',[AdminController::class,'destroysupervisor']);
Route::get('/interns',[AdminController::class,'interns']);
Route::get('/supervisors',[AdminController::class,'index']);
Route::get('/applicants',[AdminController::class,'attache']);
Route::post('/interview/invite',[AdminController::class,'store']);
Route::get('scheduled/interviews',[AdminController::class,'scheduled_interviews']);
Route::get('/interviews',[AdminController::class,'getinterviews']);
Route::post('attachees/{id}/accept',[AdminController::class,'isaccepted']);
Route::post('attachees/{id}/reject',[AdminController::class,'isrejected']);
Route::get('accepted/applicants',[AdminController::class,'getaccepted']);
Route::post('attachee/{id}/accept',[AdminController::class,'accept']);
Route::post('attachee/{id}/reject',[AdminController::class,'reject']);
Route::get('all/interns',[AdminController::class,'getnew']);
Route::post('/assign',[AdminController::class,'assign']);
Route::get('/assignments',[AdminController::class,'getassignments']);
Route::post('create/role',[AdminController::class,'create']);
Route::delete('role/{id}/destroy',[AdminController::class,'destroy']);
Route::put('role/{id}/update',[AdminController::class,'updaterole']);
Route::get('rejected/applicants',[AdminController::class,'rejected_applicants']);
Route::get('rejected/interviewees',[AdminController::class,'rejected_interviewees']);
Route::post('interview/{id}/cancel',[AdminController::class,'cancelInterview']);
Route::delete('interview/{id}/destroy',[AdminController::class,'destroy_interview']);
Route::put('interview/{id}/update',[AdminController::class,'update_interview']);

});




Route::middleware('auth:sanctum','SupervisorMiddleware')->group(function(){
    //supervisor routes
    Route::get('my/interns',[SupervisorController::class,'index']);
Route::post('create/tasks',[SupervisorController::class,'create']);
Route::get('assigned/tasks',[SupervisorController::class,'tasks']);
Route::get('user/{id}/task',[SupervisorController::class,'show']);
Route::post('user/task/{id}/rate',[SupervisorController::class,'rate_task']);
Route::put('task/{id}/edit',[SupervisorController::class,'update']);
Route::delete('task/{id}/destroy',[SupervisorController::class,'delete']);
Route::get('interview/panels',[SupervisorController::class,'panel']);

Route::post('interview/{id}/rate',[SupervisorController::class,'rate']);

});





Route::middleware('auth:sanctum','InternMiddleware')->group(function(){
  //intern routes  
Route::get('my/tasks',[InternController::class,'mytasks']);
Route::get('my/ongoing/tasks',[InternController::class,'accepted_tasks']);
Route::get('my/task/{id}',[InternController::class,'task']);
Route::post('task/{id}/accept',[InternController::class,'accept']);
Route::post('task/{id}/reject',[InternController::class,'reject']);
Route::post('task/{id}/complete',[InternController::class,'complete']);

});
