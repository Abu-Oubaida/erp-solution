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
        Schema::create('branch_transfer_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transfer_user_id');
            $table->unsignedBigInteger('new_branch_id');
            $table->unsignedBigInteger('from_branch_id')->nullable();
            $table->unsignedBigInteger('transfer_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brance_transfer_histories');
    }
};
