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
        //
        Schema::table('users', function (Blueprint $table) {
            $table->string('birthdate')->nullable()->after('joining_date');
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
