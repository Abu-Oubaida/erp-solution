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
        Schema::create('complains', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('title')->nullable();
            $table->integer('priority')->default('1')->comment("1=normal, 2=urgent, 3=very-urgent, 4=lazy");
            $table->text('details')->nullable();
            $table->unsignedBigInteger('to_dept');
            $table->foreign('to_dept')->references('id')->on('departments')->onDelete('cascade');
            $table->integer('status')->default(1)->comment("1=active,2=processing,3=solved,4=pending,5=reject,6=delete");
            $table->integer('progress')->nullable();
            $table->unsignedBigInteger('forward_to')->nullable();
            $table->foreign('forward_to')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('forward_by')->nullable();
            $table->foreign('forward_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->text('document_img')->nullable();
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
        Schema::dropIfExists('complains');
    }
};
