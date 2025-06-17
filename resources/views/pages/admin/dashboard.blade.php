
@extends('layouts.master')
@section('page_title', 'School Admin Dashboard - ' . $branch->name)
@section('content')

<div class="content">
    <!-- Branch Info Header -->
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><i class="icon-office mr-2"></i> {{ $branch->name }} Dashboard</h4>
            </div>
            <div class="header-elements">
                <div class="d-flex justify-content-center">
                    <span class="badge badge-success">{{ $branch->academic_year }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">{{ $stats['students'] }}</h3>
                            <span>Total Students</span>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="icon-users4 icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">{{ $stats['staff'] }}</h3>
                            <span>Total Staff</span>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="icon-user-tie icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">{{ $stats['classes'] }}</h3>
                            <span>Total Classes</span>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="icon-graduation2 icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">${{ number_format($stats['revenue_this_month'], 2) }}</h3>
                            <span>This Month Revenue</span>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="icon-cash3 icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Management Tabs -->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">{{ $branch->name }} - Management Dashboard</h5>
            <div class="header-elements">
                <span class="badge badge-primary">Branch ID: {{ $branch->id }}</span>
            </div>
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item">
                    <a href="#students-tab" class="nav-link active" data-toggle="tab">
                        <i class="icon-users4 mr-2"></i>Students ({{ $stats['students'] }})
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#payments-tab" class="nav-link" data-toggle="tab">
                        <i class="icon-cash3 mr-2"></i>Payments
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#academics-tab" class="nav-link" data-toggle="tab">
                        <i class="icon-graduation2 mr-2"></i>Academics
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#management-tab" class="nav-link" data-toggle="tab">
                        <i class="icon-cog mr-2"></i>Management
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#details-tab" class="nav-link" data-toggle="tab">
                        <i class="icon-info3 mr-2"></i>Branch Details
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Students Tab -->
                <div class="tab-pane fade show active" id="students-tab">
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-left-primary">
                                    <div class="card-body">
                                        <h6 class="card-title">Student Statistics</h6>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Students:</span>
                                            <strong>{{ $stats['students'] }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Active Students:</span>
                                            <strong>{{ $branch->students()->where('is_graduated', 0)->count() }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Graduated Students:</span>
                                            <strong>{{ $branch->students()->where('is_graduated', 1)->count() }}</strong>
                                        </div>
                                        <a href="{{ route('students.index') }}" class="btn btn-primary btn-sm mt-2">
                                            Manage Students
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-left-success">
                                    <div class="card-body">
                                        <h6 class="card-title">Recent Student Activities</h6>
                                        <div class="list-group list-group-flush">
                                            @forelse($branch->students()->latest()->take(5)->get() as $student)
                                            <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-0">
                                                <div>
                                                    <strong>{{ $student->user->name ?? 'N/A' }}</strong>
                                                    <small class="text-muted d-block">{{ $student->my_class->name ?? 'No Class' }}</small>
                                                </div>
                                                <small class="text-muted">{{ $student->created_at->diffForHumans() }}</small>
                                            </div>
                                            @empty
                                            <div class="list-group-item py-2 px-0">
                                                <span class="text-muted">No students found</span>
                                            </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payments Tab -->
                <div class="tab-pane fade" id="payments-tab">
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-left-warning">
                                    <div class="card-body">
                                        <h6 class="card-title">Payment Overview</h6>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>This Month Revenue:</span>
                                            <strong>${{ number_format($stats['revenue_this_month'], 2) }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Revenue:</span>
                                            <strong>${{ number_format($branch->payments()->sum('amount'), 2) }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Payments:</span>
                                            <strong>{{ $branch->payments()->count() }}</strong>
                                        </div>
                                        <a href="{{ route('payments.index') }}" class="btn btn-warning btn-sm mt-2">
                                            Manage Payments
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-left-info">
                                    <div class="card-body">
                                        <h6 class="card-title">Recent Payments</h6>
                                        <div class="list-group list-group-flush">
                                            @forelse($stats['recent_payments'] as $payment)
                                            <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-0">
                                                <div>
                                                    <strong>{{ $payment->title }}</strong>
                                                    <small class="text-muted d-block">{{ $payment->my_class->name ?? 'General' }}</small>
                                                </div>
                                                <span class="badge badge-success">${{ number_format($payment->amount, 2) }}</span>
                                            </div>
                                            @empty
                                            <div class="list-group-item py-2 px-0">
                                                <span class="text-muted">No recent payments</span>
                                            </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academics Tab -->
                <div class="tab-pane fade" id="academics-tab">
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title">Classes & Sections</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Classes:</span>
                                            <strong>{{ $stats['classes'] }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Sections:</span>
                                            <strong>{{ $branch->sections()->count() }}</strong>
                                        </div>
                                        <a href="{{ route('classes.index') }}" class="btn btn-primary btn-sm">
                                            Manage Classes
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title">Subjects & Exams</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subjects:</span>
                                            <strong>{{ $stats['subjects'] }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Exams:</span>
                                            <strong>{{ $stats['exams'] }}</strong>
                                        </div>
                                        <a href="{{ route('subjects.index') }}" class="btn btn-success btn-sm">
                                            Manage Subjects
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title">Upcoming Exams</h6>
                                    </div>
                                    <div class="card-body">
                                        @forelse($stats['upcoming_exams'] as $exam)
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <strong>{{ $exam->name }}</strong>
                                                <small class="text-muted d-block">{{ $exam->created_at->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                        @empty
                                        <span class="text-muted">No upcoming exams</span>
                                        @endforelse
                                        <a href="{{ route('exams.index') }}" class="btn btn-info btn-sm mt-2">
                                            Manage Exams
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management Tab -->
                <div class="tab-pane fade" id="management-tab">
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title">Staff Management</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Staff:</span>
                                            <strong>{{ $stats['staff'] }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Teachers:</span>
                                            <strong>{{ $branch->staff()->whereHas('user', function($q) { $q->where('user_type', 'teacher'); })->count() }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Admin Staff:</span>
                                            <strong>{{ $branch->staff()->whereHas('user', function($q) { $q->where('user_type', 'admin'); })->count() }}</strong>
                                        </div>
                                        <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm mt-2">
                                            Manage Staff
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title">Branch Resources</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Dorms:</span>
                                            <strong>{{ $branch->dorms()->count() }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Books:</span>
                                            <strong>{{ $branch->books()->count() ?? 0 }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Time Tables:</span>
                                            <strong>{{ $branch->timeTables()->count() }}</strong>
                                        </div>
                                        <a href="{{ route('dorms.index') }}" class="btn btn-success btn-sm mt-2">
                                            Manage Resources
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Branch Details Tab -->
                <div class="tab-pane fade" id="details-tab">
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title">Branch Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Branch Name:</span>
                                            <strong>{{ $branch->name }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Academic Year:</span>
                                            <strong>{{ $branch->academic_year }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Principal:</span>
                                            <strong>{{ $branch->principal_name ?? 'Not Set' }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Phone:</span>
                                            <strong>{{ $branch->phone ?? 'Not Set' }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Email:</span>
                                            <strong>{{ $branch->email ?? 'Not Set' }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Status:</span>
                                            <strong class="{{ $branch->is_active ? 'text-success' : 'text-danger' }}">
                                                {{ $branch->is_active ? 'Active' : 'Inactive' }}
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title">Additional Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <strong>Address:</strong>
                                            <p class="text-muted">{{ $branch->address }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Established:</strong>
                                            <p class="text-muted">{{ $branch->established_date ? $branch->established_date->format('M d, Y') : 'Not Set' }}</p>
                                        </div>
                                        <a href="{{ route('branch_settings.index') }}" class="btn btn-warning btn-sm">
                                            Branch Settings
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
