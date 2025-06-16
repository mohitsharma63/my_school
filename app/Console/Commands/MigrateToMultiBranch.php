<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Branch;
use App\Models\StudentRecord;
use App\Models\MyClass;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Payment;
use App\Models\Dorm;

class MigrateToMultiBranch extends Command
{
    protected $signature = 'migrate:multi-branch {--backup} {--assign-default}';
    protected $description = 'Migrate existing single-branch data to multi-branch architecture';

    public function handle()
    {
        $this->info('Starting Multi-Branch Migration...');

        try {
            // Step 1: Create backup if requested
            if ($this->option('backup')) {
                $this->createDataBackup();
            }

            // Step 2: Ensure default branch exists
            $defaultBranch = $this->ensureDefaultBranch();

            // Step 3: Assign branch_id to existing data
            if ($this->option('assign-default')) {
                $this->assignDefaultBranchToExistingData($defaultBranch->id);
            }

            // Step 4: Validate migration
            $this->validateMigration();

            $this->info('Multi-Branch Migration completed successfully!');

        } catch (\Exception $e) {
            $this->error('Migration failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function createDataBackup()
    {
        $this->info('Creating data backup...');

        $tables = [
            'student_records', 'staff_records', 'my_classes', 'sections', 'subjects',
            'exams', 'exam_records', 'marks', 'payments', 'payment_records',
            'dorms', 'time_tables', 'time_table_records', 'grades', 'books',
            'book_requests', 'receipts', 'promotions'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $backupTableName = $table . '_backup_' . date('Y_m_d_His');

                // Get all data from original table
                $data = DB::table($table)->get();

                if ($data->count() > 0) {
                    foreach ($data as $record) {
                        DB::table($backupTableName)->insert([
                            'original_id' => $record->id,
                            'original_data' => json_encode($record),
                            'backed_up_at' => now()
                        ]);
                    }

                    $this->info("Backed up {$data->count()} records from {$table}");
                }
            }
        }

        $this->info('Data backup completed.');
    }

    private function ensureDefaultBranch()
    {
        $this->info('Ensuring default branch exists...');

        $defaultBranch = Branch::where('name', 'Default Branch')->first();

        if (!$defaultBranch) {
            $defaultBranch = Branch::create([
                'name' => 'Default Branch',
                'address' => 'Main Campus Address',
                'academic_year' => date('Y') . '-' . (date('Y') + 1),
                'phone' => '+1234567890',
                'email' => 'admin@school.edu',
                'principal_name' => 'School Administrator',
                'is_active' => true
            ]);

            $this->info('Default branch created with ID: ' . $defaultBranch->id);
        } else {
            $this->info('Default branch already exists with ID: ' . $defaultBranch->id);
        }

        return $defaultBranch;
    }

    private function assignDefaultBranchToExistingData($branchId)
    {
        $this->info('Assigning default branch to existing data...');

        $tables = [
            'student_records', 'staff_records', 'my_classes', 'sections', 'subjects',
            'exams', 'exam_records', 'marks', 'payments', 'payment_records',
            'dorms', 'time_tables', 'time_table_records', 'grades', 'books',
            'book_requests', 'receipts', 'promotions'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'branch_id')) {
                // Update records that don't have branch_id set
                $updated = DB::table($table)
                    ->whereNull('branch_id')
                    ->update(['branch_id' => $branchId]);

                if ($updated > 0) {
                    $this->info("Updated {$updated} records in {$table}");
                }
            }
        }

        // Update users table
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'branch_id')) {
            $updated = DB::table('users')
                ->whereNull('branch_id')
                ->update(['branch_id' => $branchId]);

            if ($updated > 0) {
                $this->info("Updated {$updated} user records");
            }
        }

        $this->info('Branch assignment completed.');
    }

    private function validateMigration()
    {
        $this->info('Validating migration...');

        $tables = [
            'student_records', 'staff_records', 'my_classes', 'sections', 'subjects',
            'exams', 'exam_records', 'marks', 'payments', 'payment_records',
            'dorms', 'time_tables', 'time_table_records', 'grades', 'books',
            'book_requests', 'receipts', 'promotions'
        ];

        $issues = [];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'branch_id')) {
                $nullCount = DB::table($table)->whereNull('branch_id')->count();

                if ($nullCount > 0) {
                    $issues[] = "{$table} has {$nullCount} records with null branch_id";
                }
            }
        }

        if (empty($issues)) {
            $this->info('Migration validation passed!');
        } else {
            $this->warn('Migration validation found issues:');
            foreach ($issues as $issue) {
                $this->warn('- ' . $issue);
            }
        }
    }
}
