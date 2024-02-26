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
        Schema::create('attache_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('fullname');
            $table->string('email');
            $table->integer('phone');
            $table->string('academic');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->bigInteger('duration'); 
           $table->string('cv');
           $table->boolean('is_accepted')->nullable();
           $table->boolean('chosen')->nullable();
           $table->text('reason')->nullable();
           $table->uuid('user_id')->nullable();
         
           $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
          
           
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
        Schema::dropIfExists('attache_details');
    }
};
