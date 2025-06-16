<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchIdToSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->after('id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->index(['branch_id', 'type']);
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropIndex(['branch_id', 'type']);
            $table->dropColumn('branch_id');
        });
    }
}
