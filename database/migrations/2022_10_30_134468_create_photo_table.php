<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('photo', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedInteger('post_id');
        $table->unsignedInteger('author_id');
        $table->boolean('is_poster')->default(false);
        $table->text('name');
        $table->string('extension', 5);
        $table->text('url');
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
        Schema::dropIfExists('photo');
    }
}
