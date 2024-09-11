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
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->integer('status')->default('1')->comment('1=active, 0=inactive, 4=cool');
            $table->bigInteger('dept_id')->nullable();
            $table->bigInteger('designation_id')->nullable();
            $table->bigInteger('branch_id')->nullable();
            $table->string('joining_date')->nullable();
            $table->string('birthdate')->nullable();
            $table->text('profile_pic')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->unsignedBigInteger('blood_id')->nullable();
            $table->string('phone_2')->nullable();
            $table->string('email_2')->nullable();
            $table->text('father_name')->nullable();
            $table->text('mother_name')->nullable();
            $table->text('home_no')->nullable();
            $table->text('village')->nullable();
            $table->text('word_no')->nullable();
            $table->text('union')->nullable();
            $table->text('city')->nullable();
            $table->text('sub-district')->nullable();
            $table->text('district')->nullable();
            $table->text('division')->nullable();
            $table->text('capital')->nullable();
            $table->text('country')->nullable();
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
