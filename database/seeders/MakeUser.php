<?php

namespace Database\Seeders;

use App\Traits\ParentSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MakeUser extends Seeder
{
    use ParentSeeder;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = 'users';
        $table2 = 'role_user';
        $this->truncateLaratrustTables($table);
        DB::table($table)->insert([
           ['id'=>1,'company_id'=>1, 'employee_id'=>00000001,'employee_id_hidden'=>000, 'name'=>'System', 'phone'=>'01778138129', 'email'=>'abuoubaida36@gmail.com', 'dept_id'=>null, 'status'=>1, 'designation_id'=>null, 'branch_id'=>null, 'joining_date'=>null,'birthdate'=>date('d-m-Y',strtotime('01-01-2000')), 'profile_pic'=>null, 'email_verified_at'=>null, 'password'=>bcrypt('system@Admin001'), 'remember_token'=>null, 'created_at'=>date('Y-m-d H:i:s',strtotime(now())), 'updated_at'=>date('Y-m-d H:i:s',strtotime(now())),'blood_id'=>null,'phone_2'=>null,'email_2'=>null,'father_name'=>'System Admin','mother_name'=>'System Admin','home_no'=>null,'village'=>null,'word_no'=>null,'union'=>null,'city'=>null,'sub-district'=>null,'district'=>null,'division'=>null,'capital'=>null,'country'=>null]
        ]);
        $this->truncateLaratrustTables($table2);
        DB::table($table2)->insert([
            ['role_id'=>1, 'user_id'=>1, 'user_type'=>'App\Models\User']
        ]);
    }

}
