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
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
# 0.0 Clear
Route::get('/clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return "Cleared!";
});
# 1.0 Welcome
Route::get('/', function () {
    return view('welcome');
})->name('root');//1.0 End
# 2.0 Others Hidden
Route::post('upload', [ImageController::class,'upload'])->name('editor-img-upload');
Route::controller(ajaxRequestController::class)->group(function (){
# 2.1 Fiend child of a permission only for super admin access
    Route::post('fiend-permission-child','findPermissionChild')->name('fien.permission.child');
# 2.2 Fiend voucher document for preview this document on pop-up modal
    Route::post('fiend-voucher-document','findVoucherDocument')->name('fien.voucher.document');
# 2.3 Fiend voucher document info for sharing
    Route::post('fiend-voucher-document-info','findVoucherDocumentInfo')->name('fien.voucher.document.info');
});//2.0 End

# 3.0 All with Auth
Route::group(['middleware' => ['auth']],function (){
# 3.1 Common Dashboard
    Route::controller(DashboardController::class)->group(function (){
        Route::match(['post','get'],'dashboard','index')->name('dashboard');
        Route::post('change-password','ChangePassword')->name('change.password');
    });//3.1 End
# 3.2 Send mail for document sharing
    Route::controller(ajaxRequestController::class)->group(function (){
        Route::post('share-voucher-document-email','shareVoucherDocumentEmail')->name('share.voucher.document');
        Route::post('email-link-status-change','emailLinkStatusChange')->name('email.link.status.change');
    });//3.2 End

# 3.2 Super Admin Controller
    Route::group(['middleware'=>['auth','role:superadmin']],function (){
# 3.2.1 Only for programmer
        Route::controller(prorammerController::class)->group(function (){
            Route::match(['post','get'],'permission-input','create')->name('permission.input');
            Route::delete('permission-input-delete','delete')->name('permission.input.delete');
        });//3.2.1 End
# 3.2.2 User Screen Permission Controller
        Route::controller(UserPermissionController::class)->group(function (){
            Route::post('add-user-permission','addPermission')->name('add.user.permission');
            Route::delete('delete-user-permission','removePermission')->name('remove.user.permission');
        });//3.2.2 End
# 3.2.3 User File manager permission
        Route::controller(UserController::class)->group(function (){
            Route::post('user-per-add','UserPerSubmit');
            Route::post('user-per-delete','UserPerDelete');
            Route::middleware(['permission:salary_certificate_input'])->group(function () {
                Route::get('export-user-salary-prototype','exportUserSalaryPrototype')->name('export.user.salary.prototype');
            });
        });//3.2.3 End
    });//3.2 End

# 3.3 User Management Controller
    Route::controller(UserController::class,)->group(function (){
# 3.3.1 Add user
        Route::middleware(['permission:add_user'])->group(function (){
            Route::match(['post','get'],'add-user','create')->name('add.user');
        });//3.3.1 End
# 3.3.2 User List
        Route::middleware(['permission:list_user'])->group(function (){
            Route::get('user-list','show')->name('user.list');
        });//3.3.2 End
# 3.3.3 User single view
        Route::middleware(['permission:view_user'])->group(function (){
            Route::get('user-view/{userID}','SingleView')->name('user.single.view');
        });//3.3.3 End
# 3.3.4 User single edit
        Route::middleware(['permission:edit_user'])->group(function (){
            Route::match(['put','get'],'user-edit/{userID}','UserEdit')->name('user.edit');
            Route::post('user-status-change','userStatusChange')->name('user.status.change');
            Route::post('user-role-change','userRoleChange')->name('user.role.change');
            Route::post('user-password-change','userPasswordChange')->name('user.password.change');
            Route::post('user-dept-change','userDepartmentChange')->name('user.dept.change');
        });//3.3.4 End
# 3.3.5 User delete
        Route::middleware(['permission:delete_user'])->group(function (){
            Route::delete('user-delete','UserDelete')->name('user.delete');
        });//3.3.5 End

    });//3.3 End

# 3.4 Department Controller
    Route::controller(DepartmentController::class)->group(function (){
# 3.4.1 Add Department Controller
        Route::middleware(['permission:add_department'])->group(function (){
            Route::match(['post','get'],'add-department','create')->name('add.department');
        });//3.4.1 End

    });//3.4 End

# 3.5 Mobile SIM Controller
    Route::controller(MobileSIMController::class)->group(function (){
# 3.5.1 Add SIM number
        Route::middleware(['permission:add_sim_number'])->group(function (){
            Route::match(['post','get'],'add-number','create')->name('add.number');
        });//3.5.1 End

    });//3.5 End

# 3.6 File Manager Controller
    Route::middleware(['permission:file_manager'])->group(function (){
        Route::get("filemanager", [FileManagerController::class,'index'])->name('file-manager');
    });//3.6 End

# 3.7 Accounts File Manager Controller
    Route::controller(AccountVoucherController::class)->group(function (){
# 3.7.1 Add voucher Type
        Route::middleware(['permission:add_voucher_type'])->group(function () {
            Route::match(['post','get'],'add-voucher-type','createVoucherType')->name('add.voucher.type');
        });//3.7.1 End
# 3.7.2 Edit voucher Type
        Route::middleware(['permission:edit_voucher_type'])->group(function (){
            Route::match(['put','get'],'edit-voucher-type/{voucherTypeID}','editVoucherType')->name('edit.voucher.type');
        });//3.7.2
# 3.7.3 Delete voucher Type
        Route::middleware(['permission:delete_voucher_type'])->group(function (){
            Route::delete('delete-voucher-type','deleteVoucherType')->name('delete.voucher.type');
        });//3.7.2 End
# 3.7.3 Add voucher document
        Route::middleware(['permission:add_voucher_document'])->group(function () {
            Route::match(['post','get'],'add-voucher','create')->name('add.voucher.info');
        });//3.7.3 End
# 3.7.4 Add bill document
        Route::middleware(['permission:add_bill_document'])->group(function () {
            Route::match(['post','get'],'add-bill','createBill')->name('add.bill.info');
        });//3.7.4
# 3.7.5 Add FR document
        Route::middleware(['permission:add_fr_document'])->group(function () {
            Route::match(['post','get'],'add-fr','createFr')->name('add.fr.info');
        });//3.7.5 End
# 3.7.6 List uploaded voucher document
        Route::middleware(['permission:list_voucher_document'])->group(function () {
            Route::get('voucher-list','voucherList')->name('uploaded.voucher.list');
        });//3.7.6
# 3.7.6 List uploaded voucher document
        Route::middleware(['permission:view_voucher_document'])->group(function () {
            Route::get('voucher-document-view/{vID}','voucherDocumentView')->name('view.voucher.document');
        });//3.7.6
# 3.7.7 Salary certificate input
        Route::middleware(['permission:salary_certificate_input'])->group(function () {
            Route::match(['get','post'],'salary-certificate-input','salaryCertificateInput')->name('input.salary.certificate');
            Route::match(['post'],'salary-certificate-input-excel','salaryCertificateInputExcelStore')->name('input.salary.certificate.excel');
        });//3.7.7
# 3.7.8 Salary certificate List
        Route::middleware(['permission:salary_certificate_list'])->group(function () {
            Route::match(['get'],'salary-certificate-list','salaryCertificateList')->name('salary.certificate.list');
        });//3.7.8

    });//3.7 End

# 3.8 Complain Controller
    Route::controller(ComplainController::class)->group(function (){
# 3.8.1 Add complain
        Route::middleware(['permission:add_complain'])->group(function () {
            Route::match(['post','get'],'add-complain','create')->name('add.complain');
        });//#.8.1 End
# 3.8.2 List of all complain
        Route::middleware(['permission:list_complain_all'])->group(function () {
            Route::match(['post','get'],'complain-list','show')->name('individual.list.complain');
        });//3.8.2
# 3.8.3 Department all complain list user wise
        Route::middleware(['permission:list_department_complain_all'])->group(function () {
            Route::match(['post','get'],'departmental-complain-list','deptList')->name('departmental.list.complain');
        });//3.8.3
# 3.8.4 My complaint list
        Route::middleware(['permission:list_my_complain'])->group(function () {
            Route::match(['post','get'],'my-complain-list','myList')->name('my.list.complain');
        });//3.8.4
# 3.8.5 My complaint trash list
        Route::middleware(['permission:list_my_complain_trash'])->group(function () {
            Route::match(['post','get'],'my-complain-trash-list','myTrashList')->name('my.complain.trash.list');
        });//3.8.5
# 3.8.6 Complain single view
        Route::middleware(['permission:view_complain_single'])->group(function () {
            Route::match(['post','get'],'view/{complainID}','singleView')->name('single.view.complain');
        });//3.8.6
# 3.8.7 Complain single view
        Route::middleware(['permission:edit_complain'])->group(function () {
            Route::match(['post','get'],'edit/{complainID}','editMy')->name('edit.my.complain');
        });//3.8.7
# 3.8.8 Delete complain
        Route::middleware(['permission:delete_complain'])->group(function () {
            Route::match(['post','get'],'delete/{complainID}','destroy')->name('delete.complain');
        });//3.8.8

    });//3.8 End


});//3.0 End
# 4.0 Share Document View
Route::controller(\App\Http\Controllers\ShareDocumentViewController::class)->group(function (){
    Route::get('voucher-document-view','voucherDocumentView')->name('voucher.document.view');
});
require __DIR__.'/auth.php';
