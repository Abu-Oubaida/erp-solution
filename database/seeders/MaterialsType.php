<?php

namespace Database\Seeders;

use App\Traits\ParentSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialsType extends Seeder
{
    use ParentSeeder;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = 'material_types';
        $this->truncateLaratrustTables($table);
        DB::table($table)->insert([
            ['company_id'=>1,'name'=>'Fixed Asset','code'=>220100,'status'=>1,'description'=>'Fixed Asset Materials','created_by'=>1,'updated_by'=>1],
        ]);
    }
}
