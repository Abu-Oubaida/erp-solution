<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Permission::create(['parent_id'=>null, 'name'=>'accounts_file', 'display_name'=>'Accounts File', 'description'=>'Accounts File Upload']);
        Permission::create(['parent_id'=>1, 'name'=>'voucher_document_upload', 'display_name'=>'Voucher Document Upload', 'description'=>'Accounts Voucher Document Upload']);
        Permission::create(['parent_id'=>1, 'name'=>'voucher_document_edit', 'display_name'=>'Voucher Document Edit', 'description'=>'Accounts Voucher Document Edit']);
        Permission::create(['parent_id'=>1, 'name'=>'voucher_document_delete', 'display_name'=>'Voucher Document Edit', 'description'=>'Accounts Voucher Document Edit']);
        Permission::create(['parent_id'=>1, 'name'=>'voucher_document_list', 'display_name'=>'Voucher Document List', 'description'=>'Accounts Voucher Document List']);
        Permission::create(['parent_id'=>1, 'name'=>'voucher_document_view', 'display_name'=>'Voucher Document View', 'description'=>'Accounts Voucher Document View']);
    }
}
