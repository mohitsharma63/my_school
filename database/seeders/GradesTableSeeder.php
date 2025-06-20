<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('grades')->delete();

        $this->createGrades();
    }

    protected function createGrades()
    {
        // Get the default branch ID, create one if none exists
        $branch = DB::table('branches')->first();

        if (!$branch) {
            // Create a default branch if none exists
            $branchId = DB::table('branches')->insertGetId([
                'name' => 'Main Campus',
                'address' => '123 Education Street',
                'academic_year' => '2024-2025',
                'phone' => '+1234567890',
                'email' => 'main@school.edu',
                'principal_name' => 'Administrator',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $defaultBranchId = $branchId;
        } else {
            $defaultBranchId = $branch->id;
        }

        $d = [
            ['name' => 'A', 'mark_from' => 70, 'mark_to' => 100, 'remark' => 'Excellent', 'branch_id' => $defaultBranchId],
            ['name' => 'B', 'mark_from' => 60, 'mark_to' => 69, 'remark' => 'Very Good', 'branch_id' => $defaultBranchId],
            ['name' => 'C', 'mark_from' => 50, 'mark_to' => 59, 'remark' => 'Good', 'branch_id' => $defaultBranchId],
            ['name' => 'D', 'mark_from' => 45, 'mark_to' => 49, 'remark' => 'Pass', 'branch_id' => $defaultBranchId],
            ['name' => 'E', 'mark_from' => 40, 'mark_to' => 44, 'remark' => 'Poor', 'branch_id' => $defaultBranchId],
            ['name' => 'F', 'mark_from' => 0, 'mark_to' => 39, 'remark' => 'Fail', 'branch_id' => $defaultBranchId],
        ];

        DB::table('grades')->insert($d);
    }
}
