<?php

use App\Http\Controllers\AccountVoucherController;
use App\Http\Controllers\ComplainController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\editor\ImageController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\superadmin\ajaxRequestController;
use App\Http\Controllers\superadmin\DepartmentController;
use App\Http\Controllers\superadmin\MobileSIMController;
use App\Http\Controllers\superadmin\prorammerController;
use App\Http\Controllers\superadmin\UserController;
use App\Http\Controllers\superadmin\UserPermissionController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name('root');
Route::post('upload', [ImageController::class,'upload'])->name('editor-img-upload');
Route::controller(ajaxRequestController::class)->group(function (){
    Route::post('fiend-permission-child','fienPermissionChild')->name('fien.permission.child');
});
Route::group(['middleware' => ['auth']],function (){
    Route::controller(DashboardController::class)->group(function (){
        Route::match(['post','get'],'dashboard','index')->name('dashboard');
        Route::post('change-password','ChangePassword')->name('change.password');
    });
    //Super Admin Start
    Route::group(['middleware'=>['auth','role:superadmin']],function (){
        Route::controller(prorammerController::class)->group(function (){
            Route::match(['post','get'],'permission-input','create')->name('permission.input');
            Route::delete('permission-input-delete','delete')->name('permission.input.delete');
        });
        //User Permission Controller
        Route::controller(UserPermissionController::class)->group(function (){
            Route::post('add-user-permission','addPermission')->name('add.user.permission');
            Route::delete('delete-user-permission','removePermission')->name('remove.user.permission');
        });

    });//Super Admin End

    Route::controller(UserController::class,)->group(function (){
        Route::middleware(['permission:add_user'])->group(function (){
            Route::match(['post','get'],'add-user','create')->name('add.user');
        });
        Route::middleware(['permission:list_user'])->group(function (){
            Route::get('user-list','show')->name('user.list');
        });
        Route::middleware(['permission:view_user'])->group(function (){
            Route::get('user-view/{userID}','SingleView')->name('user.single.view');
        });
        Route::middleware(['permission:edit_user'])->group(function (){
            Route::match(['put','get'],'user-edit/{userID}','UserEdit')->name('user.edit');
            Route::post('user-status-change','userStatusChange')->name('user.status.change');
            Route::post('user-role-change','userRoleChange')->name('user.role.change');
            Route::post('user-password-change','userPasswordChange')->name('user.password.change');
            Route::post('user-dept-change','userDepartmentChange')->name('user.dept.change');
        });
        Route::middleware(['permission:delete_user'])->group(function (){
            Route::delete('user-delete','UserDelete')->name('user.delete');
        });
//        Route::post('user-per-add','UserPerSubmit');
//        Route::post('user-per-delete','UserPerDelete');
    });
    //Department
    Route::controller(DepartmentController::class)->group(function (){
        Route::middleware(['permission:add_department'])->group(function (){
            Route::match(['post','get'],'add-department','create')->name('add.department');
        });
    });
    //Mobile SIM Controller
    Route::controller(MobileSIMController::class)->group(function (){
        Route::match(['post','get'],'add-number','create')->name('add.number');
    });

    Route::middleware(['permission:file_manager'])->group(function (){
        Route::get("filemanager", [FileManagerController::class,'index'])->name('file-manager');
    });

    Route::controller(AccountVoucherController::class)->group(function (){
        Route::middleware(['permission:add_voucher_type'])->group(function () {
            Route::match(['post','get'],'add-voucher-type','createVoucherType')->name('add.voucher.type');
        });
        Route::middleware(['permission:edit_voucher_type'])->group(function (){
            Route::match(['put','get'],'edit-voucher-type/{voucherTypeID}','editVoucherType')->name('edit.voucher.type');
        });
        Route::middleware(['permission:delete_voucher_type'])->group(function (){
            Route::delete('delete-voucher-type','deleteVoucherType')->name('delete.voucher.type');
        });

        Route::middleware(['permission:add_voucher_document'])->group(function () {
            // Your routes that require 'add_module' permission
            Route::match(['post','get'],'add-voucher','create')->name('add.voucher.info');
        });

        Route::match(['post','get'],'add-bill','createBill')->name('add.bill.info');
        Route::match(['post','get'],'add-fr','createFr')->name('add.fr.info');
        Route::get('voucher-list','VoucherList')->name('uploaded.voucher.list');
    });
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
