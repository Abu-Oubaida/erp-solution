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
        Schema::create('pest_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();;
            $table->string('status')->nullable();
            $table->string('message')->nullable();
            $table->string('type')->nullable()->comment('copy/cut');
            $table->string('disk_name')->nullable();
            $table->string('to')->nullable();
            $table->string('from')->nullable();
            $table->string('document_type')->nullable()->comment('file/directory');
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('pest_histories');
    }
};
