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
}
