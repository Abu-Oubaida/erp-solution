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
        Schema::create('rename_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();;
            $table->string('status')->nullable();
            $table->string('message')->nullable();
            $table->string('disk_name')->nullable();
            $table->string('old_name')->nullable();
            $table->string('new_name')->nullable();
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
        Schema::dropIfExists('rename_histories');
    }
};
