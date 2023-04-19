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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique()->nullable()->after('id');
            $table->string('name');
            $table->string('phone');
            $table->string('email')->unique();
            $table->integer('status')->default('1')->comment('1=active, 0=inactive, 4=cool');
            $table->bigInteger('dept_id');
            $table->foreign('dept_id')->references('id')->on('departments')->onDelete('cascade');
            $table->string('designation')->nullable();
            $table->string('branch_name')->nullable();
            $table->date('joining_date')->nullable();
            $table->text('profile_pic')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
