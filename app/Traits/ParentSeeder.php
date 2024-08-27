<?php
namespace App\Traits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait ParentSeeder
{
    public function truncateLaratrustTables($table)
    {
        Schema::disableForeignKeyConstraints();

        DB::table($table)->truncate();
    }
}
