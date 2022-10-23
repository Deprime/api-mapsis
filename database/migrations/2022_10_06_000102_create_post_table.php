<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('type_id');
            $table->unsignedInteger('author_id');
            $table->unsignedSmallInteger('status_id')->default(1);
            $table->text('title');
            $table->text('description');
            $table->text('address');
            $table->text('suggested_address')->nullable()->comment('Address suggested by Map service geocoder');
            $table->text('coords')->nullable();
            $table->timestamp('published_at')->nullable()->comment('Event publish datetime');
            $table->timestamp('start_at')->nullable()->comment('Event startdatetime');
            $table->timestamp('finish_at')->nullable()->comment('Event finish datetime');
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post');
    }
}
