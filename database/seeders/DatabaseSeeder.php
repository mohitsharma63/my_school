<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            BloodGroupsTableSeeder::class,
            NationalitiesTableSeeder::class,
            StatesTableSeeder::class,
            LgasTableSeeder::class,
            UserTypesTableSeeder::class,
            ClassTypesTableSeeder::class,
            BranchesTableSeeder::class,
            GradesTableSeeder::class,
            RolesAndPermissionsSeeder::class,
            SettingsTableSeeder::class,
        ]);
        $this->call(DormsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(SubjectsTableSeeder::class);
        $this->call(SectionsTableSeeder::class);
        $this->call(StudentRecordsTableSeeder::class);
        $this->call(SkillsTableSeeder::class);
        $this->call(MyClassesTableSeeder::class);
    }
}
