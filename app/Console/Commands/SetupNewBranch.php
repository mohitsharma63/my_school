<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Branch;
use App\Models\Setting;
use App\Models\Grade;
use App\Models\MyClass;
use App\Models\User;
use App\Models\Role;

class SetupNewBranch extends Command
{
    protected $signature = 'branch:setup {name} {--admin-email=} {--copy-structure=}';
    protected $description = 'Setup a new branch with basic configuration';

    public function handle()
    {
        $branchName = $this->argument('name');
        $adminEmail = $this->option('admin-email');
        $copyFromBranch = $this->option('copy-structure');

        $this->info("Setting up new branch: {$branchName}");

        try {
            // Create the branch
            $branch = $this->createBranch($branchName);

            // Setup basic settings
            $this->setupBasicSettings($branch);

            // Copy structure from existing branch if specified
            if ($copyFromBranch) {
                $this->copyBranchStructure($branch, $copyFromBranch);
            }

            // Create branch admin if email provided
            if ($adminEmail) {
                $this->createBranchAdmin($branch, $adminEmail);
            }

            $this->info("Branch '{$branchName}' setup completed successfully!");
            $this->info("Branch ID: {$branch->id}");

        } catch (\Exception $e) {
            $this->error('Branch setup failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function createBranch($name)
    {
        $this->info('Creating branch record...');

        $branch = Branch::create([
            'name' => $name,
            'address' => $this->ask('Enter branch address', 'TBD'),
            'academic_year' => date('Y') . '-' . (date('Y') + 1),
            'phone' => $this->ask('Enter phone number', '+1234567890'),
            'email' => $this->ask('Enter branch email', 'branch@school.edu'),
            'principal_name' => $this->ask('Enter principal name', 'TBD'),
            'is_active' => true
        ]);

        $this->info("Branch created with ID: {$branch->id}");
        return $branch;
    }

    private function setupBasicSettings($branch)
    {
        $this->info('Setting up basic settings...');

        $defaultSettings = [
            ['type' => 'system_name', 'description' => $branch->name . ' Management System'],
            ['type' => 'system_title', 'description' => $branch->name],
            ['type' => 'address', 'description' => $branch->address],
            ['type' => 'phone', 'description' => $branch->phone],
            ['type' => 'system_email', 'description' => $branch->email],
            ['type' => 'current_session', 'description' => $branch->academic_year],
            ['type' => 'logo', 'description' => ''],
            ['type' => 'primary_color', 'description' => '#007bff'],
            ['type' => 'secondary_color', 'description' => '#6c757d'],
            ['type' => 'theme', 'description' => 'default']
        ];

        foreach ($defaultSettings as $setting) {
            Setting::create(array_merge($setting, ['branch_id' => $branch->id]));
        }

        $this->info('Basic settings created');
    }

    private function copyBranchStructure($newBranch, $sourceBranchId)
    {
        $this->info("Copying structure from branch ID: {$sourceBranchId}");

        $sourceBranch = Branch::findOrFail($sourceBranchId);

        // Copy grades
        $sourceGrades = Grade::where('branch_id', $sourceBranch->id)->get();
        foreach ($sourceGrades as $grade) {
            Grade::create([
                'name' => $grade->name,
                'class_type_id' => $grade->class_type_id,
                'mark_from' => $grade->mark_from,
                'mark_to' => $grade->mark_to,
                'remark' => $grade->remark,
                'branch_id' => $newBranch->id
            ]);
        }

        // Copy classes (without students)
        $sourceClasses = MyClass::where('branch_id', $sourceBranch->id)->get();
        foreach ($sourceClasses as $class) {
            MyClass::create([
                'name' => $class->name,
                'class_type_id' => $class->class_type_id,
                'branch_id' => $newBranch->id
            ]);
        }

        $this->info('Structure copied successfully');
    }

    private function createBranchAdmin($branch, $email)
    {
        $this->info("Creating branch admin: {$email}");

        $password = $this->secret('Enter password for admin user');

        $user = User::create([
            'name' => $this->ask('Enter admin name', 'Branch Administrator'),
            'email' => $email,
            'password' => bcrypt($password),
            'user_type' => 'admin',
            'branch_id' => $branch->id,
            'email_verified_at' => now()
        ]);

        // Assign school admin role
        $schoolAdminRole = Role::where('name', 'school_admin')->first();
        if ($schoolAdminRole) {
            $user->roles()->attach($schoolAdminRole->id);
        }

        $this->info("Branch admin created with ID: {$user->id}");
    }
}
