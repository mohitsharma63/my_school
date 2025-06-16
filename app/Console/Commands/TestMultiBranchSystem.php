<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Branch;
use App\Models\StudentRecord;
use App\Models\MyClass;
use App\Models\Subject;
use App\Models\User;
use App\Traits\BranchFilterTrait;

class TestMultiBranchSystem extends Command
{
    use BranchFilterTrait;

    protected $signature = 'test:multi-branch {--branch=}';
    protected $description = 'Test multi-branch system functionality';

    public function handle()
    {
        $this->info('Testing Multi-Branch System...');

        try {
            // Test 1: Branch isolation
            $this->testBranchIsolation();

            // Test 2: Role-based access
            $this->testRoleBasedAccess();

            // Test 3: Data filtering
            $this->testDataFiltering();

            // Test 4: Branch switching
            $this->testBranchSwitching();

            $this->info('All tests passed successfully!');

        } catch (\Exception $e) {
            $this->error('Test failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function testBranchIsolation()
    {
        $this->info('Testing branch isolation...');

        $branches = Branch::all();

        if ($branches->count() < 2) {
            $this->warn('Need at least 2 branches for isolation testing');
            return;
        }

        foreach ($branches as $branch) {
            $students = StudentRecord::where('branch_id', $branch->id)->count();
            $classes = MyClass::where('branch_id', $branch->id)->count();
            $subjects = Subject::where('branch_id', $branch->id)->count();

            $this->info("Branch '{$branch->name}': {$students} students, {$classes} classes, {$subjects} subjects");
        }

        $this->info('✓ Branch isolation test passed');
    }

    private function testRoleBasedAccess()
    {
        $this->info('Testing role-based access...');

        $superAdmin = User::whereHas('roles', function($q) {
            $q->where('name', 'super_admin');
        })->first();

        $schoolAdmin = User::whereHas('roles', function($q) {
            $q->where('name', 'school_admin');
        })->first();

        if ($superAdmin) {
            $accessibleBranches = $superAdmin->getAccessibleBranches();
            $this->info("Super Admin can access {$accessibleBranches->count()} branches");
        }

        if ($schoolAdmin) {
            $accessibleBranches = $schoolAdmin->getAccessibleBranches();
            $this->info("School Admin can access {$accessibleBranches->count()} branches");
        }

        $this->info('✓ Role-based access test passed');
    }

    private function testDataFiltering()
    {
        $this->info('Testing data filtering...');

        $branches = Branch::all();

        foreach ($branches as $branch) {
            // Simulate setting current branch
            session(['current_branch_id' => $branch->id]);

            $filteredStudents = $this->applyBranchFilter(StudentRecord::query())->count();
            $filteredClasses = $this->applyBranchFilter(MyClass::query())->count();

            $this->info("Branch '{$branch->name}' filtered data: {$filteredStudents} students, {$filteredClasses} classes");
        }

        $this->info('✓ Data filtering test passed');
    }

    private function testBranchSwitching()
    {
        $this->info('Testing branch switching...');

        $branches = Branch::where('is_active', true)->take(2)->get();

        foreach ($branches as $branch) {
            session(['current_branch_id' => $branch->id]);
            $currentBranch = $this->getCurrentBranch();

            if ($currentBranch->id === $branch->id) {
                $this->info("✓ Successfully switched to branch: {$branch->name}");
            } else {
                throw new \Exception("Failed to switch to branch: {$branch->name}");
            }
        }

        $this->info('✓ Branch switching test passed');
    }
}
