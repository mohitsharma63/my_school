<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugToBranchesTable extends Migration
{
    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->string('slug')->unique()->after('name');
            $table->json('custom_settings')->nullable()->after('is_active');
        });
    }

    public function down()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn(['slug', 'custom_settings']);
        });
    }
}
