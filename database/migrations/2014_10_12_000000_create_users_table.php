<?php

// database/migrations/2024_07_15_000000_create_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();        
            $table->string('password');
            $table->string('role')->nullable(); // admin, user, etc.
            $table->rememberToken();
            $table->timestamps();

            


        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}


