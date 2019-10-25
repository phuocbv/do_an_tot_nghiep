<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class ModifyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('password')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('avatar')->nullable();
            $table->boolean('is_active')->default(false);
            $table->integer('role')->nullable();
            $table->string('facebook_id')->unique()->nullable();
            $table->string('google_id')->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->string('password')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->dropColumn(['avatar', 'is_active', 'role', 'facebook_id', 'google_id']);
        });
    }
}
