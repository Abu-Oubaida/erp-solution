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
        Schema::table('voucher_share_email_lists', function (Blueprint $table) {
            $table->string('share_id')->nullable()->after('share_link_id');
            $table->unsignedBigInteger('share_link_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('voucher_share_email_lists', function (Blueprint $table) {
            $table->string('share_id')->nullable()->after('share_link_id');
            $table->string('share_link_id')->nullable()->change();
        });
    }
};
