<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchesTableSeeder extends Seeder
{
    public function run()
    {
        Branch::create([
            'name' => 'Main Campus',
            'address' => '123 Education Street, City Center',
            'academic_year' => '2024-2025',
            'phone' => '+1234567890',
            'email' => 'main@school.edu',
            'principal_name' => 'Dr. John Smith',
            'is_active' => true
        ]);

        Branch::create([
            'name' => 'Secondary Campus',
            'address' => '456 Learning Avenue, Suburb',
            'academic_year' => '2024-2025',
            'phone' => '+1234567891',
            'email' => 'secondary@school.edu',
            'principal_name' => 'Dr. Jane Doe',
            'is_active' => true
        ]);
    }
}
