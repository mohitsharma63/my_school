<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\StudentRecord;
use App\Models\StaffRecord;
use App\Models\Payment;
use App\Models\Exam;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchManagementController extends Controller
{
    public function index()
    {
        $branches = Branch::with(['students', 'staff'])->get();

        $branchStats = $branches->map(function($branch) {
            return [
                'id' => $branch->id,
                'name' => $branch->name,
                'students_count' => $branch->students()->count(),
                'staff_count' => $branch->staff()->count(),
                'revenue' => $branch->payments()->sum('amount'),
                'is_active' => $branch->is_active,
                'created_at' => $branch->created_at
            ];
        });

        return view('pages.super_admin.branch_management.index', compact('branches', 'branchStats'));
    }

    public function show($id)
    {
        return $this->branchDetails($id);
    }

    public function edit($id)
    {
        return $this->editBranch($id);
    }

    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'is_active' => 'boolean'
        ]);

        $branch->update($request->all());

        return redirect()->route('branches.index')->with('flash_message', 'Branch updated successfully');
    }

    public function dashboard()
    {
        $branches = Branch::with(['students', 'staff'])->get();

        $stats = [
            'total_branches' => $branches->count(),
            'total_students' => StudentRecord::count(),
            'total_staff' => StaffRecord::count(),
            'total_revenue' => Payment::sum('amount'),
            'active_branches' => Branch::where('is_active', true)->count()
        ];

        $branchStats = $branches->map(function($branch) {
            return [
                'id' => $branch->id,
                'name' => $branch->name,
                'students_count' => $branch->students()->count(),
                'staff_count' => $branch->staff()->count(),
                'revenue' => $branch->payments()->sum('amount'),
                'is_active' => $branch->is_active
            ];
        });

        return view('pages.super_admin.branch_management.dashboard', compact('stats', 'branchStats'));
    }

    public function crossBranchReport(Request $request)
    {
        $branches = Branch::all();
        $reportType = $request->get('type', 'students');

        $data = [];

        switch($reportType) {
            case 'students':
                $data = $this->getStudentComparison($branches);
                break;
            case 'revenue':
                $data = $this->getRevenueComparison($branches);
                break;
            case 'performance':
                $data = $this->getPerformanceComparison($branches);
                break;
        }

        return view('pages.super_admin.branch_management.reports', compact('data', 'branches', 'reportType'));
    }

    private function getStudentComparison($branches)
    {
        return $branches->map(function($branch) {
            return [
                'branch_name' => $branch->name,
                'total_students' => $branch->students()->count(),
                'active_students' => $branch->students()->where('is_graduated', 0)->count(),
                'graduated_students' => $branch->students()->where('is_graduated', 1)->count(),
            ];
        });
    }

    private function getRevenueComparison($branches)
    {
        return $branches->map(function($branch) {
            $currentYear = date('Y');
            return [
                'branch_name' => $branch->name,
                'total_revenue' => $branch->payments()->sum('amount'),
                'current_year_revenue' => $branch->payments()->where('year', $currentYear)->sum('amount'),
                'pending_payments' => $branch->payments()->where('paid', 0)->sum('amount'),
            ];
        });
    }

    private function getPerformanceComparison($branches)
    {
        return $branches->map(function($branch) {
            $avgGrade = DB::table('marks')
                ->join('student_records', 'marks.student_id', '=', 'student_records.id')
                ->where('student_records.branch_id', $branch->id)
                ->avg('marks.tex1');

            return [
                'branch_name' => $branch->name,
                'average_grade' => round($avgGrade, 2),
                'total_exams' => $branch->exams()->count(),
                'students_count' => $branch->students()->count(),
            ];
        });
    }

    public function branchUsers($branchId)
    {
        $branch = Branch::findOrFail($branchId);
        $users = User::where('branch_id', $branchId)->with('roles')->paginate(20);

        return view('pages.super_admin.branch_management.users', compact('branch', 'users'));
    }

    public function transferUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'new_branch_id' => 'required|exists:branches,id'
        ]);

        $user = User::findOrFail($request->user_id);
        $oldBranchId = $user->branch_id;

        $user->update(['branch_id' => $request->new_branch_id]);

        return redirect()->back()->with('flash_message', 'User transferred successfully');
    }

    public function branchDetails($branchId)
    {
        $branch = Branch::with([
            'students', 'staff', 'classes', 'subjects', 'exams',
            'payments', 'dorms', 'timeTables', 'sections', 'grades'
        ])->findOrFail($branchId);

        $stats = [
            'students' => $branch->students()->count(),
            'staff' => $branch->staff()->count(),
            'classes' => $branch->classes()->count(),
            'subjects' => $branch->subjects()->count(),
            'revenue' => $branch->payments()->sum('amount'),
            'exams' => $branch->exams()->count(),
        ];

        return view('pages.super_admin.branch_management.details', compact('branch', 'stats'));
    }

    public function branchStudents($branchId)
    {
        $branch = Branch::findOrFail($branchId);
        $students = $branch->students()->with(['user', 'my_class'])->paginate(20);

        return view('pages.super_admin.branch_management.students', compact('branch', 'students'));
    }

    public function branchPayments($branchId)
    {
        $branch = Branch::findOrFail($branchId);
        $payments = $branch->payments()->with(['my_class'])->latest()->paginate(20);
        $totalRevenue = $branch->payments()->sum('amount');
        $monthlyRevenue = $branch->payments()->whereMonth('created_at', now()->month)->sum('amount');

        return view('pages.super_admin.branch_management.payments', compact('branch', 'payments', 'totalRevenue', 'monthlyRevenue'));
    }

    public function branchAcademics($branchId)
    {
        $branch = Branch::findOrFail($branchId);
        $classes = $branch->classes()->with(['sections'])->get();
        $subjects = $branch->subjects()->get();
        $exams = $branch->exams()->latest()->take(10)->get();

        return view('pages.super_admin.branch_management.academics', compact('branch', 'classes', 'subjects', 'exams'));
    }

    public function branchManagement($branchId)
    {
        $branch = Branch::findOrFail($branchId);
        $users = $branch->users()->with(['roles'])->paginate(20);
        $settings = $branch->settings()->get();

        return view('pages.super_admin.branch_management.management', compact('branch', 'users', 'settings'));
    }

    public function editBranch($branchId)
    {
        $branch = Branch::findOrFail($branchId);
        return view('pages.super_admin.branch_management.edit', compact('branch'));
    }

    public function branchReports($branchId)
    {
        $branch = Branch::findOrFail($branchId);

        $reportData = [
            'student_stats' => [
                'total' => $branch->students()->count(),
                'active' => $branch->students()->where('is_graduated', 0)->count(),
                'graduated' => $branch->students()->where('is_graduated', 1)->count(),
            ],
            'financial_stats' => [
                'total_revenue' => $branch->payments()->sum('amount'),
                'current_year' => $branch->payments()->where('year', date('Y'))->sum('amount'),
                'pending_payments' => $branch->payments()->where('paid', 0)->sum('amount'),
            ],
            'academic_stats' => [
                'classes' => $branch->classes()->count(),
                'subjects' => $branch->subjects()->count(),
                'exams' => $branch->exams()->count(),
                'average_grade' => $this->calculateAverageGrade($branch),
            ]
        ];

        return view('pages.super_admin.branch_management.reports', compact('branch', 'reportData'));
    }

    private function calculateAverageGrade($branch)
    {
        $avgGrade = DB::table('marks')
            ->join('student_records', 'marks.student_id', '=', 'student_records.id')
            ->where('student_records.branch_id', $branch->id)
            ->avg('marks.tex1');

        return round($avgGrade, 2) ?: 0;
    }
}
