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
}
