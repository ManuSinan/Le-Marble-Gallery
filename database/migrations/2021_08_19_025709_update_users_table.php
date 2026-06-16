<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile', 15)->nullable()->change();
            $table->string('email', 255)->nullable()->change();
            $table->string('password', 255)->nullable()->change();
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'mobile_verified')) {
                $table->boolean('mobile_verified')->default(0);
            }

            if (!Schema::hasColumn('users', 'email_verified')) {
                $table->boolean('email_verified')->default(0);
            }

            if (!Schema::hasColumn('users', 'verification_code')) {
                $table->string('verification_code', 255)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
