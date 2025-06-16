<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\StudentRecord;
use App\Models\Payment;
use App\Models\Exam;

class BenefitsDataSeeder extends Seeder
{
    public function run()
    {
        // Create sample branches if they don't exist
        $branches = [
            ['name' => 'Lagos Main Campus', 'address' => 'Victoria Island, Lagos', 'academic_year' => '2023/2024'],
            ['name' => 'Abuja Branch', 'address' => 'Garki, Abuja', 'academic_year' => '2023/2024'],
            ['name' => 'Port Harcourt Branch', 'address' => 'GRA, Port Harcourt', 'academic_year' => '2023/2024'],
            ['name' => 'Kano Branch', 'address' => 'Nasarawa GRA, Kano', 'academic_year' => '2023/2024'],
        ];

        foreach ($branches as $branchData) {
            $branch = Branch::firstOrCreate(
                ['name' => $branchData['name']],
                $branchData
            );

            // Create sample students for each branch
            $studentCount = rand(150, 300);
            for ($i = 1; $i <= $studentCount; $i++) {
                StudentRecord::create([
                    'branch_id' => $branch->id,
                    'user_id' => 1, // Default user
                    'my_class_id' => rand(1, 6),
                    'section_id' => rand(1, 3),
                    'adm_no' => $branch->id . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'year_admitted' => '2023',
                    'grad' => 0
                ]);
            }

            // Create sample payments for each branch
            $paymentCount = rand(50, 150);
            for ($i = 1; $i <= $paymentCount; $i++) {
                Payment::create([
                    'branch_id' => $branch->id,
                    'title' => 'School Fees',
                    'amount' => rand(50000, 200000),
                    'my_class_id' => rand(1, 6),
                    'year' => '2023',
                    'ref_no' => 'PAY' . $branch->id . str_pad($i, 4, '0', STR_PAD_LEFT)
                ]);
            }

            // Create sample exams for each branch
            $examCount = rand(10, 25);
            for ($i = 1; $i <= $examCount; $i++) {
                Exam::create([
                    'branch_id' => $branch->id,
                    'name' => 'Term ' . rand(1, 3) . ' Examination',
                    'term' => rand(1, 3),
                    'year' => '2023',
                    'category_id' => rand(1, 3)
                ]);
            }
        }
    }
}
