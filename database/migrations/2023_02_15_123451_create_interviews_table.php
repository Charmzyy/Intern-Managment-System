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
        Schema::create('interviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('venue');
            $table->date('interview_date');
            $table->time('interview_time');
            $table->bigInteger('rating')->nullable();
            $table->text('comments')->nullable();
            $table->boolean('conducted')->nullable();
            $table->uuid('attachee_id');
            $table->uuid('user_id')->where('role',0);
            $table->boolean('admin_reviewed')->nullable();
            $table->foreign('attachee_id')->references('id')->on('attache_details')->onDelete('cascade');
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
        Schema::dropIfExists('interviews');
    }
};
