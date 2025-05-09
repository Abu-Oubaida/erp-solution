<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fixed_asset_opening_balances', function (Blueprint $table) {
            $table->unsignedBigInteger('ref_type_id')->nullable()->after('references');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fixed_asset_opening_balances', function (Blueprint $table) {
        });
    }
};
