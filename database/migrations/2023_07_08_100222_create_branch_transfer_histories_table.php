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
            $table->foreign('transfer_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('new_branch_id');
            $table->foreign('new_branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->unsignedBigInteger('from_branch_id');
            $table->foreign('from_branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->unsignedBigInteger('transfer_by');
            $table->foreign('transfer_by')->references('id')->on('users')->onDelete('cascade');
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
