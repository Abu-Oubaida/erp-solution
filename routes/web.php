<?php

use App\Http\Controllers\AccountVoucherController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\BloodGroupController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BranchTypeController;
use App\Http\Controllers\ComplainController;
use App\Http\Controllers\ControlPanelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataArchiveDashboardController;
use App\Http\Controllers\DocumentRequisitionInfoController;
use App\Http\Controllers\editor\ImageController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\FixedAssetController;
use App\Http\Controllers\FixedAssetDistribution;
use App\Http\Controllers\FixedAssetTransferController;
use App\Http\Controllers\OpReferenceTypeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesInterfaceController;
use App\Http\Controllers\ShareDocumentViewController;
use App\Http\Controllers\superadmin\ajaxRequestController;
use App\Http\Controllers\superadmin\CompanySetupController;
use App\Http\Controllers\superadmin\DepartmentController;
use App\Http\Controllers\superadmin\MobileSIMController;
use App\Http\Controllers\superadmin\prorammerController;
use App\Http\Controllers\superadmin\RoleController;
use App\Http\Controllers\superadmin\UserController;
use App\Http\Controllers\superadmin\UserPermissionController;
use App\Http\Controllers\superadmin\DesignationController;
use App\Http\Controllers\systemsuperadmin\DataArchivePackageController;
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
    Route::post('company-change-module-permission','companyChangeModulePermission')->name('company.change.permission');
# 2.2 Fiend voucher document for preview this document on pop-up modal
    Route::post('fiend-archive-document','findArchiveDocument')->name('fien.archive.document');
# 2.3 Fiend voucher document info for sharing
//    Route::middleware(['permission:share_voucher_document_individual'])->group(function (){
    Route::middleware(['permission:share_archive_data_individual'])->group(function (){
        Route::post('fiend-archive-document-info','fiendArchiveDocumentInfo')->name('fiend.archive.document.info');
        Route::post('archive-share-type','archiveShareType')->name('archive.share.type');
    });
    Route::middleware(['permission:share_archive_data'])->group(function (){
        Route::post('share-archive-fiend','shareArchiveFiend')->name('share.archive.document.fiend');
    });
    Route::middleware(['permission:share_archive_data_multiple'])->group(function (){
        Route::post('share-archive-fiend-multiple','shareArchiveFiendMultiple')->name('share.archive.document.fiend.multiple');
    });
    # 2.4 For Auth user company check
    Route::post('company-check-set','companyCheckSet');
});//2.0 End

# 3.0 All with Auth
Route::group(['middleware' => ['auth']],function (){
# 3.1 Common Dashboard
    Route::controller(DashboardController::class)->group(function (){
        Route::match(['post','get'],'dashboard','index')->name('dashboard');
        Route::post('change-password','ChangePassword')->name('change.password');
        Route::middleware(['permission:app_setting'])->group(function (){
            Route::match(['post','get'],'app-setting','appSetting')->name('app.setting');
        });
    });//3.1 End
# 3.2 Send mail for document sharing
    Route::controller(ajaxRequestController::class)->group(function (){
//        Route::middleware(['permission:share_voucher_document_individual'])->group(function (){
        Route::middleware(['permission:share_archive_data_individual'])->group(function (){
            Route::post('share-archive-document-email','shareArchiveDocumentEmail')->name('share.voucher.document');
            Route::post('email-link-status-change','emailLinkStatusChange')->name('email.link.status.change');
        });
//        Route::middleware(['permission:share_voucher_document'])->group(function (){
        Route::middleware(['permission:share_archive_data'])->group(function (){
            Route::post('share-archive-email','shareArchiveEmail');
            Route::post('archive-email-link-status-change','archiveEmailLinkStatusChange');
        });
        Route::middleware(['permission:share_archive_data_multiple'])->group(function (){
            Route::post('share-archive-email-multiple','shareArchiveEmailMultiple');
            Route::post('archive-email-link-status-change','archiveEmailLinkStatusChange');
        });
        Route::post('search-company-branch-users','searchCompanyBranchUsers');
        Route::post('company-wise-projects','companyWiseProjects');
        Route::post('user-wise-company-project-permissions','userWiseCompanyProjectPermissions');
        Route::post('company-wise-departments','companyWiseDepartments');
    });//3.2 End
    // Route::delete('/delete-multiple-user-permission',[UserPermissionController::class,'removeMultiplePermission'])->name('delete-multiple-user-permission');
# 3.2 System Admin Controller
    Route::group(['prefix'=>'system-operation'],function (){
        # 3.2.1 Only for system super admin
        Route::group(['middleware'=>['auth','role:systemsuperadmin']],function () {
            # 3.2.1.1 Only for programmer
            Route::controller(prorammerController::class)->group(function (){
                Route::get('permission-input','create')->name('permission.input');
                Route::match(['get','put'],'permission-edit/{permissionID}','edit')->name('permission.edit');
                Route::post('permission-update','update')->name('permission.update');
                Route::get('permission-export-prototype','exportPrototype')->name('permission.export.prototype');
                Route::post('add-permission-excel','permissionStoreBulk');
                Route::post('permission-store','store')->name('permission.input.store');
                Route::delete('permission-input-delete','delete')->name('permission.input.delete');
            });//3.2.1.1 End
            # 3.2.1.2 Company Setup
            Route::controller(CompanySetupController::class)->group(function (){
                Route::match(['get','post'],'company-setup','index')->name('company.setup');
                Route::match(['get','post'],'company-add','companyAdd')->name('add.company');
                Route::match(['get'],'company-list','companyList')->name('company.list');
                Route::match(['put','get'],'company-edit/{companyID}','companyEdit')->name('edit.company');
                Route::delete('company-delete','companyDelete')->name('delete.company');

                Route::match(['get','post'],'company-type-add','companyTypeAdd')->name('add.company.type');
                Route::match(['get'],'company-type-list','companyTypeList')->name('company.type.list');
                Route::match(['put','get'],'company-type-edit/{companyTypeID}','companyTypeEdit')->name('edit.company.type');
                Route::delete('company-type-delete','companyTypeDelete')->name('delete.company.type');

                Route::middleware(['permission:company_module_permission'])->group(function (){
                    Route::match(['get','post'],'company-module-permission/{companyID}','companyModulePermission')->name('company.module.permission');
                    Route::post('parent-wise-module-permission','parentModulePermission')->name('parent.module.permission');
                });
                Route::middleware(['permission:company_module_permission_delete'])->group(function (){
                    Route::delete('company-module-permission-delete-all','companyModulePermissionDeleteAll')->name('company.module.permission.delete.all');
                    Route::delete('company-module-permission-delete','companyModulePermissionDelete')->name('company.module.permission.delete');
                });

                Route::post('company-wise-users-company-permission','companyWiseUsersCompanyPermission');
            });
            # 3.2.1.3 Operation Reference Type
            Route::controller(OpReferenceTypeController::class)->group(function (){
                Route::match(['get','post'],'op-reference-type','index')->name('op.reference.type');
                Route::match(['get','put'],'op-reference-type-edit/{typeID}','edit')->name('op.reference.type.edit');
                Route::match(['delete'],'op-reference-type-delete','destroy')->name('op.reference.type.delete');
            });
            # 3.2.1.4 User Blood group
            Route::controller(BloodGroupController::class)->group(function (){
                Route::middleware(['permission:list_blood_group'])->group(function (){
                    Route::match(['get'],'blood-group-list','index')->name('blood.group.list');
                });
                Route::middleware(['permission:add_blood_group'])->group(function (){
                    Route::match(['post','get'],'add-blood-group','create')->name('add.blood.group');
                });
                Route::middleware(['permission:delete_blood_group'])->group(function (){
                    Route::match(['delete'],'delete-blood-group','destroy')->name('delete.blood.group');
                });
            });// 3.2.1.4 End

            Route::controller(DataArchivePackageController::class)->group(function (){
                Route::post('archive-package-add','store')->name('add.archive.package');
                Route::post('archive-package-edit','edit')->name('edit.archive.package');
                Route::post('archive-package-update','update')->name('update.archive.package');
                Route::post('archive-package-delete','destroy')->name('delete.archive.package');
            });
        });//3.2.1 End
        Route::controller(CompanySetupController::class)->group(function (){
            Route::middleware(['permission:add_user_company_permission'])->group(function (){
                Route::match(['post','get'],'user-company-permission/{companyID}','userCompanyPermission')->name('user.company.permission');
            });
            Route::middleware(['permission:delete_user_company_permission'])->group(function (){
                Route::delete('user-company-permission-delete','userCompanyPermissionDelete')->name('delete.user.company.permission');
            });

            Route::middleware(['permission:company_directory_permission'])->group(function (){
                Route::post('company-wise-directory-permission','companyWiseDirectoryPermission')->name('company.directory.permission');
            });
        });
        # 3.2.2 User Screen Permission Controller
        // Route::delete('/delete-multiple-user-permission',[UserPermissionController::class,'removeMultiplePermission'])->name('delete-multiple-user-permission');
        Route::controller(UserPermissionController::class)->group(function (){
            Route::middleware(['permission:add_user_screen_permission'])->group(function (){
                Route::post('add-user-permission','addPermission')->name('add.user.permission');
            });
             Route::middleware(['permission:delete_user_screen_permission'])->group(function (){
                 Route::delete('delete-user-permission','removePermission')->name('remove.user.permission');
                 Route::delete('delete-multiple-user-permission','removeMultiplePermission')->name('delete-multiple-user-permission');

            });
        });//3.2.2 End
        # 3.2.3 User File manager permission
        Route::controller(UserController::class)->group(function (){
            Route::middleware(['permission:add_user_file_manager_permission'])->group(function (){
                Route::post('user-per-add','UserPerSubmit');
            });
            Route::middleware(['permission:delete_user_file_manager_permission'])->group(function (){
                Route::post('user-per-delete','UserPerDelete');
            });
            Route::middleware(['permission:delete_user_file_manager_permission'])->group(function (){
                Route::post('user-per-multiple-delete','UserPerMultipleDelete');
            });
        });//3.2.3 End


    });//3.2 End

# 3.3 User Management Controller
    Route::controller(UserController::class,)->group(function (){
# 3.3.1 Add user
        Route::middleware(['permission:user_screen_permission'])->group(function (){
            Route::match(['get'],'user-screen-permission/{userID}','getUserScreenPermission')->name('user.screen.permission');
            Route::match(['get'],'fetch-user-permissions-after-delete','fetchUserPermissionsAfterDelete')->name('fetch.user.permissions.after.delete');
        });
        Route::middleware(['permission:file_manager_permission'])->group(function (){
            Route::match(['get'],'file-manager-permission/{userID}','getFileManagerPermission')->name('file.manager.permission');
        });
        Route::middleware(['permission:add_user'])->group(function (){
            Route::match(['post','get'],'add-user','create')->name('add.user');
            Route::match(['post'],'add-user-excel','excelStore');
            Route::post('get-employee-id','getEmployeeId');
            Route::post('change-user-company','changeUserCompany');
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
            Route::put('user-dept-change','userDepartmentChange')->name('user.dept.change');
            Route::put('user-designation-change','userDesignationChange')->name('user.designation.change');
            Route::put('user-branch-change','userBranchChange')->name('user.branch.change');
        });//3.3.4 End
# 3.3.5 User delete
        Route::middleware(['permission:delete_user'])->group(function (){
            Route::delete('user-delete','UserDelete')->name('user.delete');
        });//3.3.5 End

        Route::middleware(['permission:salary_certificate_input'])->group(function () {
            Route::get('export-employee-prototype','exportEmployeeDataPrototype')->name('export.employee.data.prototype');
        });

    });//3.3 End

# 3.4 Department Controller
    Route::controller(DepartmentController::class)->group(function (){
# 3.4.1 Add Department Controller
        Route::middleware(['permission:add_department'])->group(function (){
            Route::match(['post','get'],'add-department','create')->name('add.department');
        });//3.4.1 End
        Route::middleware(['permission:edit_department'])->group(function (){
            Route::match(['get','put'],'department-edit/{departmentID}','edit')->name('edit.department');
        });
        Route::middleware(['permission:delete_department'])->group(function (){
            Route::match(['delete'],'department-delete','destroy')->name('delete.department');
        });
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
        Route::get("file_manager", [FileManagerController::class,'index'])->name('file-manager');
    });//3.6 End
# 3.7 Data Archive Controller
    Route::controller(DataArchiveDashboardController::class)->group(function(){
        Route::middleware(['permission:archive_dashboard'])->group(function (){
            Route::match(['get'],'data-archive-dashboard','index')->name('data.archive.dashboard.interface');
            Route::post('company-wise-archive-dashboard','companyWiseArchiveDashboard');
            Route::post('company-wise-archive-dashboard-date-wise','companyWiseArchiveDashboardDateWise');
        });
    });
    Route::controller(ArchiveController::class)->group(function (){
        Route::post('search-previous-document-ref','searchPreviousDocumentRef')->name('search.previous-document-ref');//for ajax
        Route::post('search-previous-document','searchPreviousDocument')->name('search.previous-document');//for ajax
# 3.7.1 Add voucher Type
//        Route::middleware(['permission:add_voucher_type'])->group(function () {
        Route::middleware(['permission:add_archive_data_type'])->group(function () {
            Route::match(['post','get'],'add-archive-data-type','createArchiveType')->name('add.archive.type');
        });//3.7.1 End
        Route::middleware(['permission:archive_data_type_list'])->group(function () {
            Route::match(['get'],'archive-data-type-list','listArchiveDataType')->name('archive.data.type.list');
        });//3.7.1 End
# 3.7.2 Edit voucher Type

        Route::middleware(['permission:add_archive_data_type_user_permission'])->group(function () {
            Route::post('archive-data-type-user-permission-add','archiveDataTypeUserPermissionAdd')->name('archive.data.type.user.permission.add');
        });
        Route::middleware(['permission:delete_archive_data_type_user_permission'])->group(function () {
            Route::match(['post'],'delete-data-type-permission-from-user','deleteTypePermissionFromUser')->name('delete.data.type.permission.from.user');
        });
//        Route::middleware(['permission:edit_voucher_type'])->group(function (){
        Route::middleware(['permission:edit_archive_data_type'])->group(function (){
            Route::match(['put','get','post'],'edit-archive-data-type/{archiveTypeID}','editArchiveType')->name('edit.archive.type');
        });//3.7.2
# 3.7.3 Delete voucher Type
//        Route::middleware(['permission:delete_voucher_type'])->group(function (){
        Route::middleware(['permission:archive_data_type_delete'])->group(function (){
            Route::delete('delete-archive-type','deleteArchiveType')->name('delete.archive.type');
        });//3.7.2 End
# 3.7.3 Add voucher document
//        Route::middleware(['permission:add_voucher_document'])->group(function () {
        Route::middleware(['permission:archive_document_upload'])->group(function () {
            Route::match(['post','get'],'archive-data-upload','create')->name('add.archive.info');
        });
//        Route::middleware(['permission:voucher_document_edit'])->group(function () {
        Route::middleware(['permission:archive_document_edit'])->group(function () {
            Route::match(['put','get'],'edit-archive-data/{archiveDocumentID}','archiveDocumentEdit')->name('edit.archive.info');
            Route::put('linked-uploaded-document','linkedUploadedDocument')->name('linked.uploaded.document');
        });
//        Route::middleware(['permission:voucher_document_delete'])->group(function () {
        Route::middleware(['permission:archive_data_delete','permission:multiple_archive_data_delete'])->group(function () {
            Route::delete('delete-archive-data','delete')->name('delete.archive.info');
        });
//3.7.3 End
//        Route::middleware(['permission:list_voucher_document'])->group(function () {
        Route::middleware(['permission:archive_data_list'])->group(function () {
            Route::get('archive-data-list','archiveList')->name('uploaded.archive.list');
            Route::post('archive-data-list-permission-users','archiveDataListPermissionWithUsers')->name('archive-data-list.permission.users');
        });//3.7.6

        Route::middleware(['permission:archive_data_list_quick'])->group(function () {
            Route::match(['get','post'],'archive-data-list-quick','archiveListQuick')->name('uploaded.archive.list.quick');
            Route::post('archive-data-type-wise-data-show','typeWiseArchiveDataShow');
        });//3.7.6


# 3.7.5 List uploaded voucher document
//        Route::middleware(['permission:view_voucher_document'])->group(function () {
        Route::middleware(['permission:archive_document_view'])->group(function () {
            Route::get('archive-document-view/{vID}','archiveDocumentView')->name('view.archive.document');
        });//3.7.6
//        Route::middleware(['permission:archive_document_upload_individual'])->group(function (){
        Route::middleware(['permission:add_archive_document_individual'])->group(function (){
            Route::post('add-archive-document-individual','createArchiveDocumentIndividual');
            Route::post('store-archive-document-individual','storeArchiveDocumentIndividual')->name('store.archive.document.individual');
        });
//        Route::middleware(['permission:delete_voucher_document_individual'])->group(function (){
        Route::middleware(['permission:delete_archive_document_individual'])->group(function (){
            Route::delete('delete-archive-document-individual','deleteArchiveDocumentIndividual')->name('delete.archive.document.individual');
        });
//        Route::middleware(['permission:multiple_voucher_operation'])->group(function (){
        Route::middleware(['permission:multiple_archive_operation'])->group(function (){
            Route::post('archive-multiple-submit','archiveMultipleSubmit')->name('archive.multiple.submit');
//            Route::delete('delete-voucher','deleteVoucherMultiple')->name('delete.voucher.multiple');
        });
        Route::middleware(['permission:archive_setting'])->group(function (){
            Route::get('setting','setting')->name('data.archive.setting');
        });
        Route::post('search-company-department-users','searchCompanyDepartmentUsers')->name('search.company-department-users');

        Route::post('company-wise-projects-and-data-type-archive','companyWiseProjectsAndDataType');
    });
//    Account Controller
    Route::controller(AccountVoucherController::class)->group(function (){
# 3.7.6 Salary certificate input
        Route::middleware(['permission:salary_certificate_input'])->group(function () {
            Route::match(['get','post'],'salary-certificate-input','salaryCertificateInput')->name('input.salary.certificate');
            Route::match(['post'],'salary-certificate-input-excel','salaryCertificateInputExcelStore')->name('input.salary.certificate.excel');
        });//3.7.7
# 3.7.7 Salary certificate List
        Route::middleware(['permission:salary_certificate_list'])->group(function () {
            Route::match(['get'],'salary-certificate-list','salaryCertificateList')->name('salary.certificate.list');
        });//3.7.8

        Route::middleware(['permission:salary_certificate_input'])->group(function () {
            Route::get('export-user-salary-prototype','exportUserSalaryPrototype')->name('export.user.salary.prototype');
        });
        Route::middleware(['permission:salary_certificate_view'])->group(function () {
            Route::match(['get'],'salary-certificate-view/{salaryInfoID}','salaryCertificateView')->name('salary.certificate.view');
            Route::post('transaction-submit','transactionSubmit')->name('transaction.submit');
            Route::get('salary-certificate-print/{salaryInfoID}','salaryCertificatePrint')->name('salary.certificate.download');
            Route::get('salary-certificate-preview/{salaryInfoID}','previewPdf')->name('salary.certificate.preview');
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
# 3.9 User Designation
    Route::controller(DesignationController::class)->group(function (){
        Route::middleware(['permission:add_designation'])->group(function (){
            Route::match(['post','get'],'add-designation','create')->name('add.designation');
        });
        Route::middleware(['permission:list_designation'])->group(function (){
            Route::match(['get'],'designation-list','show')->name('designation.list');
        });
        Route::middleware(['permission:edit_designation'])->group(function (){
            Route::match(['get','put'],'edit-designation/{designationID}','edit')->name('edit.designation');
        });
        Route::middleware(['permission:delete_designation'])->group(function (){
            Route::match(['delete'],'designation-delete','destroy')->name('delete.designation');
        });
    });//3.8 End
# 3.10 User Designation
    Route::controller(BranchTypeController::class)->group(function (){
        Route::middleware(['permission:list_branch_type'])->group(function (){
            Route::match(['get'],'branch-type-list','index')->name('branch.type.list');
        });
        Route::middleware(['permission:add_branch_type'])->group(function (){
            Route::match(['post','get'],'add-branch-type','create')->name('add.branch.type');
        });
        Route::middleware(['permission:edit_branch_type'])->group(function (){
            Route::match(['put','get'],'edit-branch-type/{branchTypeID}','edit')->name('edit.branch.type');
        });
        Route::middleware(['permission:delete_branch_type'])->group(function (){
            Route::match(['delete'],'delete-branch-type}','destroy')->name('delete.branch.type');
        });
    });
    Route::controller(BranchController::class)->group(function (){
        Route::middleware(['permission:list_branch'])->group(function (){
            Route::match(['get'],'branch-list','index')->name('branch.list');
        });
        Route::middleware(['permission:add_branch'])->group(function (){
            Route::match(['post','get'],'add-branch','create')->name('add.branch');
            Route::post('change-branch-company','changeCompany');
        });
        Route::middleware(['permission:edit_branch'])->group(function (){
            Route::match(['put','get'],'edit-branch/{branchID}','edit')->name('edit.branch');
        });
        Route::middleware(['permission:delete_branch'])->group(function (){
            Route::match(['delete'],'delete-branch','destroy')->name('delete.branch');
        });

    });// 3.10 End
    # 3.12 Sales Interface
    Route::controller(SalesInterfaceController::class)->group(function (){
        Route::middleware(['permission:sales_dashboard_interface'])->group(function (){
            Route::match(['get','post'],'sales-dashboard','index')->name('sales.dashboard.interface');
        });
        Route::middleware(['permission:add_sales_lead'])->group(function (){
            Route::match(['get','post'],'add-lead','addLead')->name('add.sales.lead');
        });
        Route::middleware(['permission:sales_lead_list'])->group(function (){
            Route::match(['get','post'],'list-lead','leadList')->name('sales.lead.list');
        });
    });//3.12 End
    # 3.13 Control Panel
    Route::controller(ControlPanelController::class)->group(function (){
        Route::middleware(['permission:control_panel'])->prefix('control-panel')->group(function (){
            Route::match(['get','post'],'index','index')->name('control.panel');
            Route::middleware(['permission:add_user_project_permission'])->group(function (){
                Route::match(['get'],'user-project-permission','userProjectPermission')->name('user.project.permission');
                Route::match(['post'],'user-project-permission-search','userProjectPermissionSearch');
                Route::match(['post'],'user-project-permission-add','userProjectPermissionAdd');
                Route::match(['post'],'user-project-permission-copy','userProjectPermissionCopy');
                Route::match(['post'],'user-project-permission-add-all','userProjectPermissionAddAll');
                Route::middleware(['permission:user_project_permission_delete'])->group(function (){
                    Route::match(['delete','post'],'user-project-permission-delete','userProjectPermissionDelete');
                    Route::match(['delete','post'],'user-project-permission-delete-all','userProjectPermissionDeleteAll');
                });
            });
            Route::post('company-wise-user','companyWiseUser');
        });

    });//3.13 End
    # 3.14 Asset Management
    Route::middleware('permission:fixed_asset_interface')->group(function (){
        # 3.14.1 Fixed Asset
        Route::controller(FixedAssetController::class)->group(function (){
            Route::middleware(['permission:fixed_asset'])->prefix('fixed-asset')->group(function (){
                Route::match(['get','post'],'index','index')->name('fixed.asset');
                Route::post('company-wise-fixed-asset','companyWiseFixedAsset');
                Route::middleware(['permission:add_fixed_asset'])->group(function (){
                    Route::match(['post','get'],'add-fixed-asset','create')->name('fixed.asset.add');
                });
                Route::middleware(['permission:add_fixed_asset_specification'])->group(function (){
                    Route::match(['post','get'],'add-specification','createSpecification')->name('fixed.asset.specification');
                    Route::post('store-specification','specificationStore');
                });
                Route::middleware(['permission:edit_fixed_asset_specification'])->group(function (){
                    Route::match(['put','get'],'edit-specification/{fasid}','editSpecification')->name('edit.fixed.asset.specification');
                });
                Route::middleware(['permission:fixed_asset_list'])->group(function (){
                    Route::get('show-fixed-asset-list','show')->name('fixed.asset.show');
                });
                Route::middleware(['permission:fixed_asset_edit'])->group(function (){
                    Route::match(['put','get'],'edit/{fixedAssetID}','edit')->name('fixed.asset.edit');
                });
                Route::middleware(['permission:fixed_asset_delete'])->group(function (){
                    Route::match(['delete'],'delete','destroy')->name('fixed.asset.delete');
                });
                Route::middleware(['permission:delete_fixed_asset_specification'])->group(function (){
                    Route::match(['delete'],'delete-specification','destroySpecification')->name('fixed.asset.specification.delete');
                });

            });

            Route::middleware(['permission:fixed_asset_report'])->prefix('fixed-asset-report')->group(function (){
                Route::middleware(['permission:fixed_asset_stock_report'])->group(function (){
                    Route::match(['get','post'],'stock-report','stockReport')->name('fixed.asset.stock.report');
                    Route::match(['get','post'],'stock-report-search','stockReportSearch');
                });

            });
            Route::match(['get','post'],'projects-wise-fixed-assets','projectsWiseFixedAssets');
        });
        # 3.14.2 Fixed Asset Distribution
        Route::middleware(['permission:fixed_asset_distribution'])->prefix('fixed-asset-distribution')->group(function (){
            Route::controller(FixedAssetDistribution::class)->group(function (){
                Route::match(['get','post'],'index','index')->name('fixed.asset.distribution');
                Route::middleware(['permission:fixed_asset_with_reference_input'])->group(function (){
                    Route::match(['get','post'],'with-reference-input','openingInput')->name('fixed.asset.distribution.opening.input');
                    Route::post('get-fixed-asset-spec','fixedAssetSpecification');
                    Route::post('add-fixed-asset-opening','addFixedAssetOpening');
                    Route::post('get-fixed-asset-opening','getFixedAssetOpening');
                    Route::post('edit-fixed-asset-opening-spec','editFixedAssetOpeningSpec');
                    Route::post('update-fixed-asset-opening-spec','updateFixedAssetOpeningSpec');
                    Route::delete('delete-fixed-asset-opening','deleteFixedAssetOpening');
                    Route::delete('delete-fixed-asset-opening-spec','deleteFixedAssetOpeningSpec');
                    Route::put('final-update-fixed-asset-opening-spec','finalUpdateFixedAssetOpeningSpec')->name('fixed.asset.distribution.update');
                    Route::get('fixed-asset-opening-print/{assetID}','printFixedAssetWithReference')->name('fixed.asset.with.reference.print');
                });
                Route::post('edit-fixed-asset-opening','editFixedAssetOpeningList');
                Route::middleware(['permission:edit_fixed_asset_distribution_with_reference'])->group(function (){
                    Route::match(['get','put'],'edit-fixed-asset-opening-balance/{faobid}','editFixedAssetOpening')->name('edit.fixed.asset.distribution.with.reference.balance');
                    Route::delete('delete-fixed-asset-with-ref-document','destroyWithRefDocument')->name('fixed.asset.with.ref.document.delete');
                });
                Route::middleware(['permission:fixed_asset_opening_list'])->group(function (){
                    Route::match(['get'],'opening-list','openingList')->name('fixed.asset.distribution.opening.list');
                });
                Route::middleware(['permission:fixed_asset_opening_report'])->group(function (){
                    Route::get('opening-report','openingReportView')->name('fixed.asset.distribution.opening.report.view');
                });
                Route::middleware(['permission:fixed_asset_mrf'])->group(function (){});
                Route::middleware(['permission:fixed_asset_gp'])->group(function (){});
                Route::middleware(['permission:fixed_asset_issue'])->group(function (){});
                Route::middleware(['permission:fixed_asset_damage'])->group(function (){});
                Route::middleware(['permission:fixed_asset_issue_return'])->group(function (){});
                Route::post('company-projects','companyProjects');
            });

            Route::middleware(['permission:fixed_asset_transfer'])->group(function (){
                Route::controller(FixedAssetTransferController::class)->group(function (){
                    Route::middleware(['permission:fixed_asset_transfer_entry, fixed_asset_transfer_list'])->group(function (){
                        Route::match(['get'],'gp-index','index')->name('fixed.asset.transfer');
                    });
                    Route::middleware(['permission:fixed_asset_transfer_entry'])->group(function (){
                        Route::post('gp-create','create')->name('fixed.asset.transfer.create');
                        Route::post('material-specification-search','materialWiseSpecification');
                        Route::post('material-specification-wise-stock-rate-search','materialSpecificationWiseStockRate');
                        Route::post('final-update-fixed-asset-transfer','store')->name('fixed.asset.transfer.final.update');
                    });
                    Route::middleware(['permission:fixed_asset_transfer_entry,edit_fixed_asset_transfer'])->group(function (){
                        Route::post('add-to-list-fixed-asset-gp','addToListFixedAssetGp');
                        Route::delete('delete-fixed-asset-transfer-spec','deleteFixedAssetTransferSpec');
                        Route::post('edit-fixed-asset-transfer-spec','editFixedAssetTransferSpec');
                        Route::put('update-fixed-asset-transfer-spec','updateFixedAssetTransferSpec');
                    });
                    Route::middleware(['permission:edit_fixed_asset_transfer'])->group(function (){
                        Route::match(['get','put'],'edit-fixed-asset-transfer/{ftid}','edit')->name('edit.fixed.asset.transfer');
                        Route::delete('delete-fixed-asset-transfer-document','destroyDocument');
                    });
                    Route::middleware(['permission:fixed_asset_transfer_entry, delete_fixed_asset_transfer'])->group(function (){
                        Route::delete('delete-fixed-asset-transfer','deleteFixedAssetRunningTransfer')->name('fixed.asset.transfer.delete');
                    });
                    Route::match(['get','post'],'fixed-asset-transfer-print/{assetID}','fixedAssetTransferPrint')->name('fixed.asset.transfer.print');
                });
            });
        });
    });

    # 3.15 Role Management
    Route::controller(RoleController::class)->group(function (){
        Route::middleware(['permission:role_list'])->group(function (){
            Route::match(['post','get'],'role-list','index')->name('role.list');
        });
        Route::middleware(['permission:add_role'])->group(function (){
            Route::match(['post','get'],'role-add','create')->name('add.role');
        });
        Route::middleware(['permission:edit_role'])->group(function (){
            Route::match(['put','get'],'role-edit/{roleID}','edit')->name('edit.role');
        });
        Route::middleware(['permission:delete_role'])->group(function (){
            Route::match(['delete'],'delete-role','destroy')->name('delete.role');
        });
    });

    #3.16 Requisition Management
    Route::middleware(['permission:requisition'])->prefix('requisition')->group(function (){
        Route::controller(DocumentRequisitionInfoController::class)->middleware(['permission:document_requisition'])->group(function (){
            Route::post('company-wise-user','companyWiseUser');
            Route::post('req-document-receiver','reqDocumentReceiver');//ajax request
            Route::post('requested-document','requestedDocument');//ajax request
            Route::middleware(['permission:add_document_requisition'])->group(function (){
                Route::match(['post','get'],'document-requisition-add','createDocumentRequisition')->name('document.requisition.add');
                Route::match(['get'],'document-requisition-view-self/{requisitionDocumentId}','viewDocumentRequisitionSelf')->name('document.requisition.view.self');
                Route::match(['get','put'],'document-requisition-edit-self/{requisitionDocumentId}','editDocumentRequisitionSelf')->name('document.requisition.edit.self');
                Route::match(['delete'],'document-requisition-delete-self/{requisitionDocumentId}','deleteDocumentRequisitionSelf')->name('document.requisition.delete.self');
            });
            Route::middleware(['permission:list_document_requisition'])->group(function (){
                Route::match(['post','get'],'document-requisition-list','indexDocument')->name('document.requisition.list');
            });
            Route::middleware(['permission:document_requisition_list'])->prefix('document-report')->group(function (){
                Route::middleware(['permission:document_requisition_received_list'])->group(function (){
                    Route::match(['get'],'received-list','documentRequisitionReceivedList')->name("document.requisition.received.list");
                });
                Route::middleware(['permission:document_requisition_sent_list'])->group(function (){
                    Route::match(['get'],'sent-list','documentRequisitionSentList')->name("document.requisition.sent.list");
                });
            });
        });
    });
});//3.0 End
# 4.0 Share Document View
Route::controller(ShareDocumentViewController::class)->group(function (){
    Route::get('archive-document-view','archiveDocumentView')->name('archive.document.view');
    Route::get('/secure-document/{id}', 'viewDocument')->name('document.view');
    Route::get('archive-view','archiveView')->name('archive.view');
    Route::get('/view-pdf/{id}', 'streamSecurePdf')->name('pdf.secure.view');
});
require __DIR__.'/auth.php';
