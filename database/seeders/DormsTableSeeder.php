<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dorm;
use App\Models\Branch;

class DormsTableSeeder extends Seeder
{
    public function run()
    {
        // Get the first branch or create one if none exists
        $branch = Branch::first();

        if (!$branch) {
            $branch = Branch::create([
                'name' => 'Main Branch',
                'slug' => 'main-branch'
            ]);
        }

        $dorms = [
            'Faith Hostel',
            'Peace Hostel',
            'Grace Hostel',
            'Success Hostel',
            'Trust Hostel'
        ];

        foreach ($dorms as $dormName) {
            Dorm::create([
                'name' => $dormName,
                'branch_id' => $branch->id
            ]);
        }
    }
}
