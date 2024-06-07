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
        Schema::create('company_infos', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default(1)->comment('1=active, 0=inactive, 2=suspended, 3=deleted');
            $table->string("company_name")->unique();
            $table->string("contract_person_name")->nullable();
            $table->string("company_code")->unique();
            $table->string("phone")->unique()->nullable();
            $table->string("contract_person_phone")->nullable();
            $table->string("email")->unique()->nullable();
            $table->text("location")->nullable();
            $table->text("remarks")->nullable();
            $table->text("logo")->nullable();
            $table->text("logo_sm")->nullable();
            $table->text("logo_icon")->nullable();
            $table->text("cover")->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('company_infos');
    }
};
