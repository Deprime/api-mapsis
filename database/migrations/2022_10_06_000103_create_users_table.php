<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->nullable();
            $table->string('email', 64)->nullable()->unique('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('prefix', 6)->nullable()->comment('phone prefix');
            $table->string('phone', 20)->nullable()->unique('phone');
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('reset_token', 80)->nullable();
            $table->enum('role', ['dementor', 'moderator', 'customer', 'performer'])->nullable()->comment('If role null user must finalize his registration');
            $table->double('balance', 12, 2)->default(0);
            $table->string('nickname')->nullable();
            $table->string('first_name', 20)->nullable();
            $table->string('last_name', 20)->nullable();
            $table->string('patronymic', 30)->nullable();
            $table->date('birthdate')->nullable();
            $table->enum('sex', ['male', 'female']);
            $table->text('avatar_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('referal_parent_id')->nullable();
            $table->timestamp('referal_connected_at')->nullable();
            $table->string('tg_user_id')->unique('tg_user_id')->nullable();
            $table->string('tg_username')->unique('tg_username')->nullable();
            $table->string('google_user_id')->unique('google_user_id')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('users');
    }
}
