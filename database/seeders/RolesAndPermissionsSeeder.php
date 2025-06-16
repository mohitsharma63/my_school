<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create Roles
        $roles = [
            [
                'name' => 'platform_super_admin',
                'display_name' => 'Platform Super Admin',
                'description' => 'Manages all branches and creates School Admins',
                'hierarchy_level' => 1
            ],
            [
                'name' => 'school_admin',
                'display_name' => 'School Admin',
                'description' => 'Has full access within their assigned school',
                'hierarchy_level' => 2
            ],
            [
                'name' => 'teacher',
                'display_name' => 'Teacher',
                'description' => 'Can access teaching-related data within their school',
                'hierarchy_level' => 3
            ],
            [
                'name' => 'librarian',
                'display_name' => 'Librarian',
                'description' => 'Can access library-related data within their school',
                'hierarchy_level' => 3
            ],
            [
                'name' => 'accountant',
                'display_name' => 'Accountant',
                'description' => 'Can access financial data within their school',
                'hierarchy_level' => 3
            ],
            [
                'name' => 'student',
                'display_name' => 'Student',
                'description' => 'Can access their own academic data',
                'hierarchy_level' => 4
            ],
            [
                'name' => 'parent',
                'display_name' => 'Parent',
                'description' => 'Can access their children\'s academic data',
                'hierarchy_level' => 4
            ]
        ];

        foreach ($roles as $roleData) {
            Role::create($roleData);
        }

        // Create Permissions
        $permissions = [
            // Branch Management
            ['name' => 'manage_branches', 'display_name' => 'Manage Branches', 'category' => 'branches'],
            ['name' => 'view_branches', 'display_name' => 'View Branches', 'category' => 'branches'],

            // User Management
            ['name' => 'manage_users', 'display_name' => 'Manage Users', 'category' => 'users'],
            ['name' => 'view_users', 'display_name' => 'View Users', 'category' => 'users'],
            ['name' => 'create_school_admins', 'display_name' => 'Create School Admins', 'category' => 'users'],

            // Student Management
            ['name' => 'manage_students', 'display_name' => 'Manage Students', 'category' => 'students'],
            ['name' => 'view_students', 'display_name' => 'View Students', 'category' => 'students'],
            ['name' => 'view_own_profile', 'display_name' => 'View Own Profile', 'category' => 'students'],
            ['name' => 'view_children_profiles', 'display_name' => 'View Children Profiles', 'category' => 'students'],

            // Academic Management
            ['name' => 'manage_classes', 'display_name' => 'Manage Classes', 'category' => 'academic'],
            ['name' => 'view_classes', 'display_name' => 'View Classes', 'category' => 'academic'],
            ['name' => 'manage_subjects', 'display_name' => 'Manage Subjects', 'category' => 'academic'],
            ['name' => 'view_subjects', 'display_name' => 'View Subjects', 'category' => 'academic'],
            ['name' => 'manage_timetables', 'display_name' => 'Manage Timetables', 'category' => 'academic'],
            ['name' => 'view_timetables', 'display_name' => 'View Timetables', 'category' => 'academic'],

            // Examination Management
            ['name' => 'manage_exams', 'display_name' => 'Manage Exams', 'category' => 'exams'],
            ['name' => 'view_exams', 'display_name' => 'View Exams', 'category' => 'exams'],
            ['name' => 'manage_marks', 'display_name' => 'Manage Marks', 'category' => 'exams'],
            ['name' => 'view_marks', 'display_name' => 'View Marks', 'category' => 'exams'],
            ['name' => 'view_own_marks', 'display_name' => 'View Own Marks', 'category' => 'exams'],
            ['name' => 'view_children_marks', 'display_name' => 'View Children Marks', 'category' => 'exams'],

            // Financial Management
            ['name' => 'manage_payments', 'display_name' => 'Manage Payments', 'category' => 'finance'],
            ['name' => 'view_payments', 'display_name' => 'View Payments', 'category' => 'finance'],
            ['name' => 'view_own_payments', 'display_name' => 'View Own Payments', 'category' => 'finance'],
            ['name' => 'view_children_payments', 'display_name' => 'View Children Payments', 'category' => 'finance'],

            // Library Management
            ['name' => 'manage_books', 'display_name' => 'Manage Books', 'category' => 'library'],
            ['name' => 'view_books', 'display_name' => 'View Books', 'category' => 'library'],
            ['name' => 'manage_book_requests', 'display_name' => 'Manage Book Requests', 'category' => 'library'],
            ['name' => 'view_book_requests', 'display_name' => 'View Book Requests', 'category' => 'library'],

            // Dormitory Management
            ['name' => 'manage_dorms', 'display_name' => 'Manage Dormitories', 'category' => 'dorms'],
            ['name' => 'view_dorms', 'display_name' => 'View Dormitories', 'category' => 'dorms'],

            // Settings
            ['name' => 'manage_settings', 'display_name' => 'Manage Settings', 'category' => 'settings'],
            ['name' => 'view_settings', 'display_name' => 'View Settings', 'category' => 'settings'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::create($permissionData);
        }

        // Assign permissions to roles
        $this->assignPermissionsToRoles();
    }

    private function assignPermissionsToRoles()
    {
        // Platform Super Admin - All permissions
        $platformSuperAdmin = Role::where('name', 'platform_super_admin')->first();
        $allPermissions = Permission::all();
        $platformSuperAdmin->permissions()->attach($allPermissions);

        // School Admin - All permissions except branch management and creating school admins
        $schoolAdmin = Role::where('name', 'school_admin')->first();
        $schoolAdminPermissions = Permission::whereNotIn('name', [
            'manage_branches',
            'create_school_admins'
        ])->get();
        $schoolAdmin->permissions()->attach($schoolAdminPermissions);

        // Teacher - Academic and examination related permissions
        $teacher = Role::where('name', 'teacher')->first();
        $teacherPermissions = Permission::whereIn('name', [
            'view_users', 'view_students', 'manage_students',
            'view_classes', 'view_subjects', 'view_timetables', 'manage_timetables',
            'view_exams', 'manage_exams', 'view_marks', 'manage_marks',
            'view_books', 'view_book_requests'
        ])->get();
        $teacher->permissions()->attach($teacherPermissions);

        // Librarian - Library management permissions
        $librarian = Role::where('name', 'librarian')->first();
        $librarianPermissions = Permission::whereIn('name', [
            'view_users', 'view_students',
            'manage_books', 'view_books', 'manage_book_requests', 'view_book_requests'
        ])->get();
        $librarian->permissions()->attach($librarianPermissions);

        // Accountant - Financial management permissions
        $accountant = Role::where('name', 'accountant')->first();
        $accountantPermissions = Permission::whereIn('name', [
            'view_users', 'view_students',
            'manage_payments', 'view_payments'
        ])->get();
        $accountant->permissions()->attach($accountantPermissions);

        // Student - Own data access
        $student = Role::where('name', 'student')->first();
        $studentPermissions = Permission::whereIn('name', [
            'view_own_profile', 'view_own_marks', 'view_own_payments',
            'view_books', 'view_book_requests', 'view_timetables'
        ])->get();
        $student->permissions()->attach($studentPermissions);

        // Parent - Children data access
        $parent = Role::where('name', 'parent')->first();
        $parentPermissions = Permission::whereIn('name', [
            'view_children_profiles', 'view_children_marks', 'view_children_payments',
            'view_books', 'view_timetables'
        ])->get();
        $parent->permissions()->attach($parentPermissions);
    }
}
