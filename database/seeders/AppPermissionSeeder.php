<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Traits\ParentSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class AppPermissionSeeder extends Seeder
{
    use ParentSeeder;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = 'permissions';
        $this->truncateLaratrustTables($table);
        $permissions = [
            ['id' => '1','parent_id' => NULL,'name' => 'none','is_parent' => '1','display_name' => 'None','description' => 'None','created_at' => NULL,'updated_at' => NULL],
            ['id' => '2','parent_id' => NULL,'name' => 'accounts_file','is_parent' => '1','display_name' => 'Accounts File','description' => 'Accounts File Upload','created_at' => '2023-10-04 09:41:29','updated_at' => '2023-10-04 09:41:29'],
            ['id' => '3','parent_id' => '2','name' => 'voucher_document_upload','is_parent' => NULL,'display_name' => 'Voucher Document Upload','description' => 'Accounts Voucher Document Upload','created_at' => '2023-10-04 09:41:29','updated_at' => '2023-10-04 09:41:29'],
            ['id' => '4','parent_id' => '2','name' => 'voucher_document_edit','is_parent' => NULL,'display_name' => 'Voucher Document Edit','description' => 'Accounts Voucher Document Edit','created_at' => '2023-10-04 09:41:29','updated_at' => '2023-10-04 09:41:29'],
            ['id' => '5','parent_id' => '2','name' => 'voucher_document_delete','is_parent' => NULL,'display_name' => 'Voucher Document Delete','description' => 'Accounts Voucher Document Edit','created_at' => '2023-10-04 09:41:29','updated_at' => '2023-10-04 09:41:29'],
            ['id' => '6','parent_id' => '2','name' => 'voucher_document_list','is_parent' => NULL,'display_name' => 'Voucher Document List','description' => 'Accounts Voucher Document List','created_at' => '2023-10-04 09:41:29','updated_at' => '2023-10-04 09:41:29'],
            ['id' => '7','parent_id' => '2','name' => 'voucher_document_view','is_parent' => NULL,'display_name' => 'Voucher Document View','description' => 'Accounts Voucher Document View','created_at' => '2023-10-04 09:41:29','updated_at' => '2023-10-04 09:41:29'],
            ['id' => '8','parent_id' => '2','name' => 'voucher_type_add','is_parent' => NULL,'display_name' => 'Voucher Type Add','description' => 'Voucher Type Add','created_at' => '2023-10-07 07:29:21','updated_at' => '2023-10-07 07:29:21'],
            ['id' => '11','parent_id' => '2','name' => 'voucher_type_list','is_parent' => NULL,'display_name' => 'Voucher Type List','description' => 'Voucher Type List','created_at' => '2023-10-07 07:29:21','updated_at' => '2023-10-07 07:29:21'],
            ['id' => '12','parent_id' => '2','name' => 'voucher_type_view','is_parent' => NULL,'display_name' => 'Voucher Type View','description' => 'Voucher Type View','created_at' => '2023-10-07 07:29:21','updated_at' => '2023-10-07 07:29:21'],
            ['id' => '21','parent_id' => NULL,'name' => 'user_management','is_parent' => '1','display_name' => 'User Management','description' => 'User Management','created_at' => '2023-10-30 10:10:36','updated_at' => '2023-10-30 10:10:36'],
            ['id' => '22','parent_id' => '21','name' => 'add_user','is_parent' => NULL,'display_name' => 'Add New User','description' => 'Add New User','created_at' => '2023-10-30 10:11:49','updated_at' => '2023-10-30 10:11:49'],
            ['id' => '23','parent_id' => '21','name' => 'list_user','is_parent' => NULL,'display_name' => 'User List','description' => 'User List','created_at' => '2023-10-30 10:12:55','updated_at' => '2023-10-30 10:12:55'],
            ['id' => '24','parent_id' => '21','name' => 'view_user','is_parent' => NULL,'display_name' => 'User Profile View','description' => 'User Profile Single View','created_at' => '2023-10-30 10:13:55','updated_at' => '2023-10-30 10:13:55'],
            ['id' => '25','parent_id' => '21','name' => 'edit_user','is_parent' => NULL,'display_name' => 'Edit User Profile','description' => 'Edit User Profile','created_at' => '2023-10-30 10:14:32','updated_at' => '2023-10-30 10:14:32'],
            ['id' => '26','parent_id' => '21','name' => 'delete_user','is_parent' => NULL,'display_name' => 'Delete User Profile','description' => 'Delete User Profile','created_at' => '2023-10-30 10:15:06','updated_at' => '2023-10-30 10:15:06'],
            ['id' => '27','parent_id' => '21','name' => 'department','is_parent' => '1','display_name' => 'Department','description' => 'Department','created_at' => '2023-10-30 10:16:44','updated_at' => '2023-10-30 10:16:44'],
            ['id' => '28','parent_id' => '27','name' => 'add_department','is_parent' => NULL,'display_name' => 'Add Department ( Add + List + Edit )','description' => 'Add Department ( Add + List + Edit )','created_at' => '2023-10-30 10:19:43','updated_at' => '2023-10-30 10:19:43'],
            ['id' => '29','parent_id' => '2','name' => 'salary_certificate_input','is_parent' => NULL,'display_name' => 'Add Salary Certificate','description' => 'Salary Certificate Input','created_at' => '2023-11-15 05:47:56','updated_at' => '2023-11-15 05:47:56'],
            ['id' => '30','parent_id' => '21','name' => 'mobile_sim','is_parent' => '1','display_name' => 'Mobile SIM','description' => 'Mobile SIM','created_at' => '2023-11-15 05:51:18','updated_at' => '2023-11-15 05:51:18'],
            ['id' => '31','parent_id' => '30','name' => 'add_sim_number','is_parent' => NULL,'display_name' => 'Add SIM','description' => 'Add Mobile SIM Number','created_at' => '2023-11-15 05:52:43','updated_at' => '2023-11-15 05:52:43'],
            ['id' => '32','parent_id' => NULL,'name' => 'file_manager','is_parent' => '1','display_name' => 'File Manager','description' => 'File Manager','created_at' => '2023-11-15 05:54:00','updated_at' => '2023-11-15 05:54:00'],
            ['id' => '33','parent_id' => '2','name' => 'add_voucher_type','is_parent' => NULL,'display_name' => 'Add voucher Type','description' => 'Add voucher Type','created_at' => '2023-11-15 05:57:28','updated_at' => '2023-11-15 05:57:28'],
            ['id' => '34','parent_id' => '2','name' => 'edit_voucher_type','is_parent' => NULL,'display_name' => 'Edit Voucher Type','description' => 'Edit Voucher Type','created_at' => '2023-11-15 05:58:25','updated_at' => '2023-11-15 05:58:25'],
            ['id' => '35','parent_id' => '2','name' => 'delete_voucher_type','is_parent' => NULL,'display_name' => 'Delete Voucher Type','description' => 'Delete Voucher Type','created_at' => '2023-11-19 04:00:01','updated_at' => '2023-11-19 04:00:01'],
            ['id' => '36','parent_id' => '2','name' => 'add_voucher_document','is_parent' => NULL,'display_name' => 'Add Voucher','description' => 'Add Voucher Document','created_at' => '2023-11-19 04:01:58','updated_at' => '2023-11-19 04:01:58'],
            ['id' => '37','parent_id' => '2','name' => 'add_bill_document','is_parent' => NULL,'display_name' => 'Add Bill','description' => 'Add Bill Document','created_at' => '2023-11-19 04:02:25','updated_at' => '2023-11-19 04:02:25'],
            ['id' => '38','parent_id' => '2','name' => 'add_fr_document','is_parent' => NULL,'display_name' => 'Add FR','description' => 'Add FR Document','created_at' => '2023-11-19 04:03:34','updated_at' => '2023-11-19 04:03:34'],
            ['id' => '39','parent_id' => '2','name' => 'list_voucher_document','is_parent' => NULL,'display_name' => 'Voucher List','description' => 'Voucher Document List','created_at' => '2023-11-19 04:04:10','updated_at' => '2023-11-19 04:04:10'],
            ['id' => '40','parent_id' => '2','name' => 'view_voucher_document','is_parent' => NULL,'display_name' => 'Voucher View','description' => 'Voucher View Document','created_at' => '2023-11-19 04:10:56','updated_at' => '2023-11-19 04:10:56'],
            ['id' => '41','parent_id' => '2','name' => 'salary_certificate_list','is_parent' => NULL,'display_name' => 'Salary Certificate List','description' => 'Salary Certificate List','created_at' => '2023-11-19 04:12:00','updated_at' => '2023-11-19 04:12:00'],
            ['id' => '42','parent_id' => '2','name' => 'salary_certificate_view','is_parent' => NULL,'display_name' => 'Salary Certificate View','description' => 'Salary Certificate View','created_at' => '2023-11-19 04:13:18','updated_at' => '2023-11-19 04:13:18'],
            ['id' => '43','parent_id' => NULL,'name' => 'complains','is_parent' => '1','display_name' => 'Complains','description' => 'Complains','created_at' => '2023-11-19 04:14:20','updated_at' => '2023-11-19 04:14:20'],
            ['id' => '44','parent_id' => '43','name' => 'add_complain','is_parent' => NULL,'display_name' => 'Add Complain','description' => 'Add Complain','created_at' => '2023-11-19 04:14:53','updated_at' => '2023-11-19 04:14:53'],
            ['id' => '45','parent_id' => '43','name' => 'list_complain_all','is_parent' => NULL,'display_name' => 'Complain List','description' => 'Complain List','created_at' => '2023-11-19 04:15:36','updated_at' => '2023-11-19 04:15:36'],
            ['id' => '46','parent_id' => '43','name' => 'list_department_complain_all','is_parent' => NULL,'display_name' => 'Department complain List','description' => 'Department complain List','created_at' => '2023-11-19 04:16:06','updated_at' => '2023-11-19 04:16:06'],
            ['id' => '47','parent_id' => '43','name' => 'list_my_complain','is_parent' => NULL,'display_name' => 'My Complain List','description' => 'My Complain List','created_at' => '2023-11-19 04:16:29','updated_at' => '2023-11-19 04:16:29'],
            ['id' => '48','parent_id' => '43','name' => 'list_my_complain_trash','is_parent' => NULL,'display_name' => 'My Complain Trash List','description' => 'My Complain Trash List','created_at' => '2023-11-19 04:19:39','updated_at' => '2023-11-19 04:19:39'],
            ['id' => '49','parent_id' => '43','name' => 'view_complain_single','is_parent' => NULL,'display_name' => 'View Complain','description' => 'View Complain','created_at' => '2023-11-19 04:20:08','updated_at' => '2023-11-19 04:20:08'],
            ['id' => '50','parent_id' => '43','name' => 'edit_complain','is_parent' => NULL,'display_name' => 'Edit Complain','description' => 'Edit Complain','created_at' => '2023-11-19 04:20:31','updated_at' => '2023-11-19 04:20:31'],
            ['id' => '51','parent_id' => '43','name' => 'delete_complain','is_parent' => NULL,'display_name' => 'Delete Complain','description' => 'Delete Complain','created_at' => '2023-11-19 04:20:58','updated_at' => '2023-11-19 04:20:58'],
            ['id' => '52','parent_id' => '21','name' => 'designation','is_parent' => '1','display_name' => 'Designation','description' => 'Designation','created_at' => '2023-11-19 04:21:45','updated_at' => '2023-11-19 04:21:45'],
            ['id' => '53','parent_id' => '52','name' => 'add_designation','is_parent' => NULL,'display_name' => 'Add Designation','description' => 'Add Designation','created_at' => '2023-11-19 04:22:15','updated_at' => '2023-11-19 04:22:15'],
            ['id' => '54','parent_id' => '21','name' => 'branch','is_parent' => '1','display_name' => 'Branch','description' => 'Branch','created_at' => '2023-11-19 04:22:56','updated_at' => '2023-11-19 04:22:56'],
            ['id' => '55','parent_id' => '54','name' => 'list_branch_type','is_parent' => NULL,'display_name' => 'Branch Type List','description' => 'Branch Type List','created_at' => '2023-11-19 04:23:42','updated_at' => '2023-11-19 04:23:42'],
            ['id' => '56','parent_id' => '54','name' => 'add_branch_type','is_parent' => NULL,'display_name' => 'Add Branch Type','description' => 'Add Branch Type','created_at' => '2023-11-19 04:26:05','updated_at' => '2023-11-19 04:26:05'],
            ['id' => '57','parent_id' => '54','name' => 'edit_branch_type','is_parent' => NULL,'display_name' => 'Edit Branch Type','description' => 'Edit Branch Type','created_at' => '2023-11-19 04:26:26','updated_at' => '2023-11-19 04:26:26'],
            ['id' => '58','parent_id' => '54','name' => 'delete_branch_type','is_parent' => NULL,'display_name' => 'Delete Branch Type','description' => 'Delete Branch Type','created_at' => '2023-11-19 04:26:57','updated_at' => '2023-11-19 04:26:57'],
            ['id' => '59','parent_id' => '54','name' => 'list_branch','is_parent' => NULL,'display_name' => 'Branch List','description' => 'Branch List','created_at' => '2023-11-19 04:27:23','updated_at' => '2023-11-19 04:27:23'],
            ['id' => '60','parent_id' => '54','name' => 'add_branch','is_parent' => NULL,'display_name' => 'Add Branch','description' => 'Add Branch','created_at' => '2023-11-19 04:27:50','updated_at' => '2023-11-19 04:27:50'],
            ['id' => '61','parent_id' => '54','name' => 'edit_branch','is_parent' => NULL,'display_name' => 'Edit Branch','description' => 'Edit Branch','created_at' => '2023-11-19 04:28:20','updated_at' => '2023-11-19 04:28:20'],
            ['id' => '62','parent_id' => '21','name' => 'blood_group','is_parent' => '1','display_name' => 'Blood Group','description' => 'Blood Group','created_at' => '2023-11-19 04:28:59','updated_at' => '2023-11-19 04:28:59'],
            ['id' => '63','parent_id' => '62','name' => 'list_blood_group','is_parent' => NULL,'display_name' => 'Blood Group List','description' => 'Blood Group List','created_at' => '2023-11-19 04:29:22','updated_at' => '2023-11-19 04:29:22'],
            ['id' => '64','parent_id' => '62','name' => 'add_blood_group','is_parent' => NULL,'display_name' => 'Add Blood Group','description' => 'Add Blood Group','created_at' => '2023-11-19 04:29:43','updated_at' => '2023-11-19 04:29:43'],
            ['id' => '65','parent_id' => '62','name' => 'delete_blood_group','is_parent' => NULL,'display_name' => 'Delete Blood Group','description' => 'Delete Blood Group','created_at' => '2023-11-19 04:33:26','updated_at' => '2023-11-19 04:33:26'],
            ['id' => '66','parent_id' => '2','name' => 'add_voucher_document_individual','is_parent' => NULL,'display_name' => 'Add Voucher Document Individual','description' => 'Add Voucher Document Individual','created_at' => '2023-11-27 10:46:39','updated_at' => '2023-11-27 10:46:39'],
            ['id' => '67','parent_id' => '2','name' => 'delete_voucher_document_individual','is_parent' => NULL,'display_name' => 'Delete Voucher Document Individual','description' => 'Delete Voucher Document Individual','created_at' => '2023-11-27 10:47:32','updated_at' => '2023-11-27 10:47:32'],
            ['id' => '68','parent_id' => '2','name' => 'share_voucher_document_individual','is_parent' => NULL,'display_name' => 'Share Voucher Document Individual','description' => 'Share Voucher Document Individual','created_at' => '2023-11-27 13:37:49','updated_at' => '2023-11-27 13:37:49'],
            ['id' => '69','parent_id' => '2','name' => 'multiple_voucher_operation','is_parent' => NULL,'display_name' => 'Multiple Voucher Operation','description' => 'Selected data delete, download zip, download list etc.','created_at' => '2023-11-28 17:54:56','updated_at' => '2023-11-28 17:54:56'],
            ['id' => '72','parent_id' => NULL,'name' => 'control_panel','is_parent' => '1','display_name' => 'Control Panel','description' => 'Control Panel','created_at' => '2024-06-07 18:48:01','updated_at' => '2024-06-07 18:48:01'],
            ['id' => '73','parent_id' => NULL,'name' => 'sales_interface','is_parent' => '1','display_name' => 'Sales Interface','description' => 'Sales Interface','created_at' => '2024-06-07 18:49:44','updated_at' => '2024-06-07 18:49:44'],
            ['id' => '74','parent_id' => '73','name' => 'sales_dashboard_interface','is_parent' => NULL,'display_name' => 'Sales Interface Dashboard','description' => 'Sales Interface Dashboard','created_at' => '2024-06-07 18:50:50','updated_at' => '2024-06-07 18:50:50'],
            ['id' => '75','parent_id' => '73','name' => 'add_sales_lead','is_parent' => NULL,'display_name' => 'Add Sales Lead','description' => 'Add Sales Lead','created_at' => '2024-06-07 18:51:50','updated_at' => '2024-06-07 18:51:50'],
            ['id' => '76','parent_id' => '73','name' => 'sales_lead_list','is_parent' => NULL,'display_name' => 'Sales Lead List','description' => 'Sales Lead List','created_at' => '2024-06-07 18:52:49','updated_at' => '2024-06-07 18:52:49'],
            ['id' => '77','parent_id' => '72','name' => 'create_sales_team','is_parent' => NULL,'display_name' => 'Create Sales Team','description' => 'Create Sales Team','created_at' => '2024-06-07 20:44:12','updated_at' => '2024-06-07 20:44:12'],
            ['id' => '78','parent_id' => '72','name' => 'assign_sales_marketing_users','is_parent' => NULL,'display_name' => 'Assign Sales Marketing Users','description' => 'Assign Sales Marketing Users','created_at' => '2024-06-07 20:45:22','updated_at' => '2024-06-07 20:45:22'],
            ['id' => '79','parent_id' => '72','name' => 'add_user_project_permission','is_parent' => NULL,'display_name' => 'Add User Project Permission','description' => 'Add User Project Permission','created_at' => '2024-08-13 10:55:34','updated_at' => '2024-08-13 10:55:34'],
            ['id' => '80','parent_id' => NULL,'name' => 'fixed_asset_interface','is_parent' => '1','display_name' => 'Asset Management','description' => 'Asset Management','created_at' => '2024-08-13 11:01:23','updated_at' => '2024-08-13 11:01:23'],
            ['id' => '81','parent_id' => '80','name' => 'fixed_asset','is_parent' => NULL,'display_name' => 'Fixed Asset','description' => 'Fixed Asset','created_at' => '2024-08-13 11:10:42','updated_at' => '2024-08-13 11:10:42'],
            ['id' => '82','parent_id' => '80','name' => 'add_fixed_asset','is_parent' => NULL,'display_name' => 'Add Fixed Asset','description' => 'Add Fixed Asset','created_at' => '2024-08-13 14:27:20','updated_at' => '2024-08-13 14:27:20'],
            ['id' => '83','parent_id' => '80','name' => 'add_fixed_asset_specification','is_parent' => NULL,'display_name' => 'Add Fixed Asset Specification','description' => 'Add Fixed Asset Specification','created_at' => '2024-08-13 14:28:52','updated_at' => '2024-08-13 14:28:52'],
            ['id' => '84','parent_id' => '80','name' => 'edit_fixed_asset_specification','is_parent' => NULL,'display_name' => 'Edit Fixed Asset Specification','description' => 'Edit Fixed Asset Specification','created_at' => '2024-08-13 14:49:07','updated_at' => '2024-08-13 14:49:07'],
            ['id' => '85','parent_id' => '80','name' => 'fixed_asset_list','is_parent' => NULL,'display_name' => 'Fixed Asset List','description' => 'Fixed Asset List','created_at' => '2024-08-13 14:49:46','updated_at' => '2024-08-13 14:49:46'],
            ['id' => '86','parent_id' => '80','name' => 'fixed_asset_edit','is_parent' => NULL,'display_name' => 'Fixed Asset Edit','description' => 'Fixed Asset Edit','created_at' => '2024-08-13 14:51:02','updated_at' => '2024-08-13 14:51:02'],
            ['id' => '87','parent_id' => '80','name' => 'fixed_asset_delete','is_parent' => NULL,'display_name' => 'Fixed Asset Delete','description' => 'Fixed Asset Delete','created_at' => '2024-08-13 14:52:00','updated_at' => '2024-08-13 14:52:00'],
            ['id' => '88','parent_id' => '80','name' => 'fixed_asset_distribution','is_parent' => NULL,'display_name' => 'Fixed Asset Distribution','description' => 'Fixed Asset Distribution','created_at' => '2024-08-13 14:55:00','updated_at' => '2024-08-13 14:55:00'],
            ['id' => '89','parent_id' => '80','name' => 'fixed_asset_with_reference_input','is_parent' => NULL,'display_name' => 'Fixed Asset With Reference Input','description' => 'Fixed Asset With Reference Input','created_at' => '2024-08-13 15:00:27','updated_at' => '2024-08-13 15:00:27'],
            ['id' => '90','parent_id' => '80','name' => 'fixed_asset_opening_list','is_parent' => NULL,'display_name' => 'Fixed Asset Opening List','description' => 'Fixed Asset Opening List','created_at' => '2024-08-13 15:02:17','updated_at' => '2024-08-13 15:02:17'],
            ['id' => '91','parent_id' => '80','name' => 'fixed_asset_opening_report','is_parent' => NULL,'display_name' => 'Fixed Asset Opening Report','description' => 'Fixed Asset Opening Report','created_at' => '2024-08-13 15:03:15','updated_at' => '2024-08-13 15:03:15'],
            ['id' => '92','parent_id' => '80','name' => 'fixed_asset_mrf','is_parent' => NULL,'display_name' => 'Fixed Asset MRF','description' => 'Fixed Asset MRF','created_at' => '2024-08-13 15:11:33','updated_at' => '2024-08-13 15:11:33'],
            ['id' => '93','parent_id' => '80','name' => 'fixed_asset_gp','is_parent' => NULL,'display_name' => 'Fixed Asset GP','description' => 'Fixed Asset GP','created_at' => '2024-08-13 15:13:56','updated_at' => '2024-08-13 15:13:56'],
            ['id' => '94','parent_id' => '80','name' => 'fixed_asset_issue','is_parent' => NULL,'display_name' => 'Fixed Asset Issue','description' => 'Fixed Asset Issue','created_at' => '2024-08-13 15:15:38','updated_at' => '2024-08-13 15:15:38'],
            ['id' => '95','parent_id' => '80','name' => 'fixed_asset_damage','is_parent' => NULL,'display_name' => 'Fixed Asset Damage','description' => 'Fixed Asset Damage','created_at' => '2024-08-13 15:17:11','updated_at' => '2024-08-13 15:17:11'],
            ['id' => '97','parent_id' => '80','name' => 'fixed_asset_issue_return','is_parent' => NULL,'display_name' => 'Fixed Asset Issue Return','description' => 'Fixed Asset Issue Return','created_at' => '2024-08-13 15:21:08','updated_at' => '2024-08-13 15:21:08'],
            ['id' => '99','parent_id' => '80','name' => 'delete_fixed_asset_opening_balance','is_parent' => NULL,'display_name' => 'Delete Fixed Asset Opening Balance','description' => 'Delete Fixed Asset Opening Balance','created_at' => '2024-08-19 09:30:51','updated_at' => '2024-08-19 09:30:51'],
            ['id' => '100','parent_id' => '80','name' => 'edit_fixed_asset_distribution_with_reference','is_parent' => NULL,'display_name' => 'Edit Fixed Asset Distribution With Reference Balance','description' => 'Edit Fixed Asset Distribution With Reference Balance','created_at' => '2024-08-24 14:42:27','updated_at' => '2024-08-24 14:42:27'],
            ['id' => '101','parent_id' => '54','name' => 'delete_branch','is_parent' => NULL,'display_name' => 'Delete Branch','description' => 'Delete Branch','created_at' => '2023-11-19 04:26:57','updated_at' => '2023-11-19 04:26:57'],
        ];
        DB::table($table)->insert($permissions);
    }
}



