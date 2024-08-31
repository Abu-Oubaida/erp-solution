<?php

namespace Database\Seeders;

use App\Traits\ParentSeeder;
use Doctrine\DBAL\Schema\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BloodGroups extends Seeder
{
    use ParentSeeder;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = 'blood_groups';
        $this->truncateLaratrustTables($table);
        $blood_groups =
            [
                ['blood_type' => 'A+','created_by' => '1','updated_by' => NULL,'created_at' => date(now())],
                ['blood_type' => 'B+','created_by' => '1','updated_by' => NULL,'created_at' => date(now())],
                ['blood_type' => 'AB+','created_by' => '1','updated_by' => NULL,'created_at' => date(now())],
                ['blood_type' => 'O+','created_by' => '1','updated_by' => NULL,'created_at' => date(now())],
                ['blood_type' => 'A-','created_by' => '1','updated_by' => NULL,'created_at' => date(now())],
                ['blood_type' => 'B-','created_by' => '1','updated_by' => NULL,'created_at' => date(now())],
                ['blood_type' => 'AB-','created_by' => '1','updated_by' => NULL,'created_at' => date(now())],
                ['blood_type' => 'O-','created_by' => '1','updated_by' => NULL,'created_at' => date(now())],
            ];
        DB::table($table)->insert($blood_groups);
    }
}
