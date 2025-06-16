
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRoleTable extends Migration
{
    public function up()
    {
        Schema::create('user_role', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'role_id', 'branch_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_role');
    }
}
