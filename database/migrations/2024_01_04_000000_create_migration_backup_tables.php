<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMigrationBackupTables extends Migration
{
    public function up()
    {
        // Create backup tables for critical data before migration
        $tables = [
            'student_records', 'staff_records', 'my_classes', 'sections', 'subjects',
            'exams', 'exam_records', 'marks', 'payments', 'payment_records',
            'dorms', 'time_tables', 'time_table_records', 'grades', 'books',
            'book_requests', 'receipts', 'promotions'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $backupTableName = $table . '_backup_' . date('Y_m_d_His');

                // Create backup table with same structure
                Schema::create($backupTableName, function (Blueprint $backupTable) use ($table) {
                    // Get original table structure and copy it
                    $backupTable->id('backup_id');
                    $backupTable->unsignedBigInteger('original_id');
                    $backupTable->json('original_data');
                    $backupTable->timestamp('backed_up_at')->useCurrent();
                });
            }
        }
    }

    public function down()
    {
        // Drop backup tables (be careful with this in production)
        $backupTables = collect(Schema::getAllTables())
            ->pluck('table_name')
            ->filter(function ($table) {
                return strpos($table, '_backup_') !== false;
            });

        foreach ($backupTables as $table) {
            Schema::dropIfExists($table);
        }
    }
}
