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
            $table->bigIncrements('id');
            $table->string('name')->index();
            $table->string('email')->unique()->index();
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->boolean('status')->default(false)->nullable();
            $table->dateTimeTz('session_expire_on')->nullable();
            $table->boolean('password_changed')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('photo')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
