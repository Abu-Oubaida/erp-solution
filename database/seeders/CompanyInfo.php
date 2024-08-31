<?php

namespace Database\Seeders;

use App\Traits\ParentSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyInfo extends Seeder
{
    use ParentSeeder;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = 'company_infos';
        $this->truncateLaratrustTables($table);
        DB::table($table)->insert([
            ['status'=>1,'company_name'=>'System','company_type_id'=>1,'contract_person_name'=>'System Admin','company_code'=>'System','phone'=>'01778138129','contract_person_phone'=>'01778138129','email'=>'abuoubaida36@gmail.com','location'=>'Earth','remarks'=>null,'logo'=>null,'logo_sm'=>null,'logo_icon'=>null,'cover'=>null,'created_by'=>1,'updated_by'=>1,'created_at'=>date('Y-m-d H:i:s',strtotime(now())),'updated_at'=>date('Y-m-d H:i:s',strtotime(now()))]
        ]);
    }
}
