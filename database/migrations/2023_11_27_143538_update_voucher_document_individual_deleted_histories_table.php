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
        Schema::table('voucher_document_individual_deleted_histories', function (Blueprint $table) {
            $table->string('deleted_by')->nullable()->after('filepath');
            $table->string('restored_by')->nullable()->after('filepath');
            $table->string('restored_status')->default(0)->after('filepath');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
