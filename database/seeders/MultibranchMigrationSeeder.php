<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\User;
use App\Models\Role;
use App\Models\StudentRecord;
use App\Models\MyClass;
use App\Models\Subject;

class MultibranchMigrationSeeder extends Seeder
{
    public function run()
    {
        // Create additional test branches
        $branches = [
            [
                'name' => 'North Campus',
                'address' => '789 North Street, North District',
                'academic_year' => '2024-2025',
                'phone' => '+1234567892',
                'email' => 'north@school.edu',
                'principal_name' => 'Dr. Alice Johnson',
                'is_active' => true
            ],
            [
                'name' => 'South Campus',
                'address' => '321 South Avenue, South District',
                'academic_year' => '2024-2025',
                'phone' => '+1234567893',
                'email' => 'south@school.edu',
                'principal_name' => 'Dr. Bob Wilson',
                'is_active' => true
            ]
        ];

        foreach ($branches as $branchData) {
            $branch = Branch::create($branchData);

            // Create branch admin
            $admin = User::create([
                'name' => 'Admin ' . $branch->name,
                'email' => 'admin.' . strtolower(str_replace(' ', '', $branch->name)) . '@school.edu',
                'password' => bcrypt('password'),
                'user_type' => 'admin',
                'branch_id' => $branch->id,
                'email_verified_at' => now()
            ]);

            // Assign school admin role
            $schoolAdminRole = Role::where('name', 'school_admin')->first();
            if ($schoolAdminRole) {
                $admin->roles()->attach($schoolAdminRole->id);
            }

            // Create sample classes for each branch
            $classes = [
                ['name' => 'Class 1A', 'class_type_id' => 1],
                ['name' => 'Class 1B', 'class_type_id' => 1],
                ['name' => 'Class 2A', 'class_type_id' => 2]
            ];

            foreach ($classes as $classData) {
                MyClass::create(array_merge($classData, ['branch_id' => $branch->id]));
            }

            // Create sample subjects
            $subjects = [
                ['name' => 'Mathematics', 'slug' => 'mathematics'],
                ['name' => 'English', 'slug' => 'english'],
                ['name' => 'Science', 'slug' => 'science']
            ];

            foreach ($subjects as $subjectData) {
                Subject::create(array_merge($subjectData, [
                    'branch_id' => $branch->id,
                    'my_class_id' => MyClass::where('branch_id', $branch->id)->first()->id
                ]));
            }

            $this->command->info("Created test data for branch: {$branch->name}");
        }
    }
}
