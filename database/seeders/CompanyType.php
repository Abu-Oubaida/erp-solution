<?php

namespace Database\Seeders;

use App\Traits\ParentSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyType extends Seeder
{
    use ParentSeeder;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = 'company_types';
        $this->truncateLaratrustTables($table);
        DB::table($table)->insert([
            ['id'=>1,'company_type_title'=>'System','status'=>1,'remarks'=>'System','created_by'=>1,'updated_by'=>1]
        ]);
    }
}
