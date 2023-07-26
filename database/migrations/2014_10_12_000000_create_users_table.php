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
            $table->id();
            $table->string('first_name',100);
            $table->string('middle_name',100)->nullable();
            $table->string('last_name',100);
            $table->string('email')->unique();
            $table->integer('id_number')->nullable()->unique();
            $table->string('contacts',50)->nullable()->unique();
            $table->string('mpesa_contact',50)->nullable()->unique();
            $table->smallInteger('role_type')->nullable();
            $table->smallInteger('status')->nullable()->default(1);  // Indicate whether present or absent
            $table->string('path')->nullable(); //to be used for img path
            $table->timestamp('last_login')->nullable();
            $table->smallInteger('login_attempts')->default(0);
            $table->smallInteger('blacklist')->default(0);
            $table->smallInteger('blacklist_attempts')->default(0);
            $table->timestamp('time_blacklisted')->nullable();
            $table->string('otp')->nullable();
            $table->timestamp('otp_expiry')->nullable();
            $table->tinyInteger('is_verified')->nullable();
            $table->string('reset_code')->nullable();
            $table->string('reset_expiry')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->softDeletes();
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