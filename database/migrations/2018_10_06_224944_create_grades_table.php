<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradesTable extends Migration
{
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            // Make sure this matches the branches.id type exactly
            $table->unsignedBigInteger('branch_id');

            // Add any other columns you need

            $table->timestamps();

            // Add the foreign key constraint
            $table->foreign('branch_id')
                  ->references('id')
                  ->on('branches')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('grades');
    }
}
