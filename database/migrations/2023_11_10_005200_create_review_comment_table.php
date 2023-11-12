<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('review_comment', function (Blueprint $table) {
          $table->increments('id');
          $table->unsignedInteger('review_id');
          $table->unsignedInteger('author_id');
          $table->string('content', 400);
          $table->timestamps();
          $table->softDeletes();
          $table->foreign('review_id')->references('id')->on('review')->onUpdate('cascade')->onDelete('cascade');
          $table->foreign('author_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_comment');
    }
};
