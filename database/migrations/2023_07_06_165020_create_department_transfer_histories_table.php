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
        Schema::create('department_transfer_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transfer_user_id');
            $table->foreign('transfer_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('new_dept_id');
            $table->foreign('new_dept_id')->references('id')->on('departments')->onDelete('cascade');
            $table->unsignedBigInteger('from_dept_id');
            $table->foreign('from_dept_id')->references('id')->on('departments')->onDelete('cascade');
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
        Schema::dropIfExists('department_transfer_histories');
    }
};
