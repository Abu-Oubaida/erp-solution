<?php

use App\Http\Controllers\ComplainController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\editor\ImageController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\superadmin\DepartmentController;
use App\Http\Controllers\superadmin\MobileSIMController;
use App\Http\Controllers\superadmin\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name('root');
Route::post('upload', [ImageController::class,'upload'])->name('editor-img-upload');

Route::group(['middleware' => ['auth']],function (){
    Route::controller(DashboardController::class)->group(function (){
        Route::match(['post','get'],'dashboard','index')->name('dashboard');
        Route::post('change-password','ChangePassword')->name('change.password');
    });
    //Super Admin Start
    Route::group(['middleware'=>['auth','role:superadmin']],function (){
        Route::controller(UserController::class,)->group(function (){
            Route::match(['post','get'],'add-user','create')->name('add.user');
            Route::get('user-list','show')->name('user.list');
            Route::get('user-view/{userID}','SingleView')->name('user.single.view');
            Route::match(['put','get'],'user-edit/{userID}','UserEdit')->name('user.edit');
            Route::delete('user-delete','UserDelete')->name('user.delete');
            Route::post('user-per-add','UserPerSubmit');
            Route::post('user-per-delete','UserPerDelete');
            Route::post('user-status-change','userStatusChange')->name('user.status.change');
            Route::post('user-role-change','userRoleChange')->name('user.role.change');
            Route::post('user-password-change','userPasswordChange')->name('user.password.change');
            Route::post('user-dept-change','userDepartmentChange')->name('user.dept.change');
        });
        //Department
        Route::controller(DepartmentController::class,)->group(function (){
            Route::match(['post','get'],'add-department','create')->name('add.department');
        });
        //Mobile SIM Controller
        Route::controller(MobileSIMController::class)->group(function (){
            Route::match(['post','get'],'add-number','create')->name('add.number');
        });

    });//Super Admin End

    Route::get("filemanager", [FileManagerController::class,'index'])->name('file-manager');
    Route::controller(ComplainController::class)->group(function (){
        Route::match(['post','get'],'add-complain','create')->name('add.complain');
        Route::match(['post','get'],'complain-list','show')->name('individual.list.complain');
        Route::match(['post','get'],'departmental-complain-list','deptList')->name('departmental.list.complain');
        Route::match(['post','get'],'my-complain-list','myList')->name('my.list.complain');
        Route::match(['post','get'],'my-complain-trash-list','myTrashList')->name('my.complain.trash.list');
        Route::match(['post','get'],'view/{complainID}','singleView')->name('single.view.complain');
        Route::match(['post','get'],'edit/{complainID}','editMy')->name('edit.my.complain');
        Route::match(['post','get'],'delete/{complainID}','destroy')->name('delete.complain');
    });


});


require __DIR__.'/auth.php';
