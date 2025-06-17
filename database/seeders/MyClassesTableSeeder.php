<?php
namespace Database\Seeders;

use App\Models\ClassType;
use App\Models\Branch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class MyClassesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('my_classes')->delete();
        $ct = ClassType::pluck('id')->all();

        // Get the first branch (default branch)
        $defaultBranch = \App\Models\Branch::first();

        if (!$defaultBranch) {
            echo "Warning: No branches found. Please run BranchesTableSeeder first.\n";
            return;
        }

        $data = [
            ['name' => 'Nursery 1', 'class_type_id' => $ct[2], 'branch_id' => $defaultBranch->id],
            ['name' => 'Nursery 2', 'class_type_id' => $ct[2], 'branch_id' => $defaultBranch->id],
            ['name' => 'Nursery 3', 'class_type_id' => $ct[2], 'branch_id' => $defaultBranch->id],
            ['name' => 'Primary 1', 'class_type_id' => $ct[3], 'branch_id' => $defaultBranch->id],
            ['name' => 'Primary 2', 'class_type_id' => $ct[3], 'branch_id' => $defaultBranch->id],
            ['name' => 'JSS 2', 'class_type_id' => $ct[4], 'branch_id' => $defaultBranch->id],
            ['name' => 'JSS 3', 'class_type_id' => $ct[4], 'branch_id' => $defaultBranch->id],
            ['name' => 'SSS 1', 'class_type_id' => $ct[5], 'branch_id' => $defaultBranch->id],
            ['name' => 'SSS 2', 'class_type_id' => $ct[5], 'branch_id' => $defaultBranch->id],
            ['name' => 'SSS 3', 'class_type_id' => $ct[5], 'branch_id' => $defaultBranch->id],
        ];

        DB::table('my_classes')->insert($data);
    }
}
