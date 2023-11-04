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
        Schema::create('role_wise_default_permissions', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->default(1)->comment('1=active');
            $table->unsignedBigInteger('role_id');
            $table->string('role_name');
            $table->unsignedBigInteger('permission_id');
            $table->string('permission_name');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
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
        Schema::dropIfExists('role_wise_default_permissions');
    }
};
