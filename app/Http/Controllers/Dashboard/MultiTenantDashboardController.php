<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\StudentRecord;
use App\Models\StaffRecord;
use App\Models\Exam;
use App\Models\Payment;
use App\Traits\BranchFilterTrait;
use Illuminate\Http\Request;

class MultiTenantDashboardController extends Controller
{
    use BranchFilterTrait;

    public function index()
    {
        $user = auth()->user();
        $currentBranch = $this->getCurrentBranch();

        if ($user->isPlatformSuperAdmin()) {
            return $this->superAdminDashboard();
        } elseif ($user->isSchoolAdmin()) {
            return $this->schoolAdminDashboard($currentBranch);
        } else {
            return $this->staffDashboard($currentBranch);
        }
    }

    private function superAdminDashboard()
    {
        $branches = Branch::where('is_active', true)->get();
        $totalStudents = StudentRecord::count();
        $totalStaff = StaffRecord::count();
        $totalBranches = $branches->count();

        $branchStats = $branches->map(function ($branch) {
            return [
                'branch' => $branch,
                'students' => $branch->students()->count(),
                'staff' => $branch->staff()->count(),
                'classes' => $branch->classes()->count(),
                'revenue' => $branch->payments()->sum('amount')
            ];
        });

        return view('pages.super_admin.dashboard', compact(
            'branches', 'totalStudents', 'totalStaff', 'totalBranches', 'branchStats'
        ));
    }

    private function schoolAdminDashboard($branch)
    {
        $stats = [
            'students' => $branch->students()->count(),
            'staff' => $branch->staff()->count(),
            'classes' => $branch->classes()->count(),
            'subjects' => $branch->subjects()->count(),
            'exams' => $branch->exams()->count(),
            'revenue_this_month' => $branch->payments()
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
            'recent_payments' => $branch->payments()
                ->with(['my_class'])
                ->latest()
                ->take(5)
                ->get(),
            'upcoming_exams' => $branch->exams()
                ->where('created_at', '>', now())
                ->take(3)
                ->get()
        ];

        return view('pages.admin.dashboard', compact('stats', 'branch'));
    }

    private function staffDashboard($branch)
    {
        $user = auth()->user();
        $stats = [
            'my_classes' => $branch->classes()->where('teacher_id', $user->id)->count(),
            'my_students' => StudentRecord::whereHas('my_class', function($q) use ($user) {
                $q->where('teacher_id', $user->id);
            })->count(),
            'pending_marks' => 0, // Implement based on your marks system
            'upcoming_exams' => $branch->exams()
                ->where('created_at', '>', now())
                ->take(3)
                ->get()
        ];

        return view('pages.teacher.dashboard', compact('stats', 'branch'));
    }

    public function getBranchStatistics(Request $request)
    {
        $branchId = $request->input('branch_id', $this->getCurrentBranchId());
        $branch = Branch::findOrFail($branchId);

        if (!auth()->user()->canAccessBranch($branchId)) {
            abort(403);
        }

        $stats = [
            'students' => $branch->students()->count(),
            'staff' => $branch->staff()->count(),
            'classes' => $branch->classes()->count(),
            'subjects' => $branch->subjects()->count(),
            'revenue' => $branch->payments()->sum('amount'),
            'recent_activity' => $this->getRecentActivity($branch)
        ];

        return response()->json($stats);
    }

    private function getRecentActivity($branch)
    {
        $activities = collect();

        // Recent student registrations
        $recentStudents = $branch->students()->latest()->take(3)->get();
        foreach ($recentStudents as $student) {
            $activities->push([
                'type' => 'student_registration',
                'message' => "New student registered: {$student->user->name}",
                'date' => $student->created_at
            ]);
        }

        // Recent payments
        $recentPayments = $branch->payments()->latest()->take(3)->get();
        foreach ($recentPayments as $payment) {
            $activities->push([
                'type' => 'payment',
                'message' => "Payment received: {$payment->title} - {$payment->amount}",
                'date' => $payment->created_at
            ]);
        }

        return $activities->sortByDesc('date')->take(5)->values();
    }

    public function getBranchDetails(Request $request)
    {
        $branchId = $request->input('branch_id', $this->getCurrentBranchId());
        $branch = Branch::with([
            'students', 'staff', 'classes', 'subjects', 'exams',
            'payments', 'dorms', 'timeTables', 'sections', 'grades',
            'settings', 'users'
        ])->findOrFail($branchId);

        if (!auth()->user()->canAccessBranch($branchId)) {
            abort(403);
        }

        $details = [
            'branch_info' => [
                'id' => $branch->id,
                'name' => $branch->name,
                'address' => $branch->address,
                'phone' => $branch->phone,
                'email' => $branch->email,
                'principal_name' => $branch->principal_name,
                'academic_year' => $branch->academic_year,
                'established_date' => $branch->established_date,
                'is_active' => $branch->is_active,
            ],
            'statistics' => [
                'students' => $branch->students()->count(),
                'active_students' => $branch->students()->where('is_graduated', 0)->count(),
                'graduated_students' => $branch->students()->where('is_graduated', 1)->count(),
                'staff' => $branch->staff()->count(),
                'teachers' => $branch->staff()->whereHas('user', function($q) {
                    $q->where('user_type', 'teacher');
                })->count(),
                'classes' => $branch->classes()->count(),
                'sections' => $branch->sections()->count(),
                'subjects' => $branch->subjects()->count(),
                'exams' => $branch->exams()->count(),
                'grades' => $branch->grades()->count(),
                'dorms' => $branch->dorms()->count(),
                'time_tables' => $branch->timeTables()->count(),
                'users' => $branch->users()->count(),
                'settings' => $branch->settings()->count(),
                'total_revenue' => $branch->payments()->sum('amount'),
                'monthly_revenue' => $branch->payments()
                    ->whereMonth('created_at', now()->month)
                    ->sum('amount'),
                'payments_count' => $branch->payments()->count(),
            ],
            'blood_groups' => \App\Models\BloodGroup::all()->count(),
            'nationalities' => \App\Models\Nationality::all()->count(),
            'states' => \App\Models\State::all()->count(),
            'lgas' => \App\Models\Lga::all()->count(),
            'class_types' => \App\Models\ClassType::all()->count(),
            'user_types' => \App\Models\UserType::all()->count(),
            'skills' => \App\Models\Skill::all()->count(),
        ];

        return response()->json($details);
    }
}
