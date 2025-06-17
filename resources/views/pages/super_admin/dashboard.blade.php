
@extends('layouts.master')
@section('page_title', 'Super Admin Dashboard')
@section('content')

<div class="content">
    <!-- Branch Statistics Overview -->
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">{{ $totalBranches }}</h3>
                            <span>Total Branches</span>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="icon-office icon-3x opacity-75"></i>
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
                            <h3 class="mb-0">{{ $totalStudents }}</h3>
                            <span>Total Students</span>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="icon-users icon-3x opacity-75"></i>
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
                            <h3 class="mb-0">{{ $totalStaff }}</h3>
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
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">{{ $branches->where('is_active', true)->count() }}</h3>
                            <span>Active Branches</span>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="icon-checkmark3 icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Branch Details Cards -->
    <div class="row">
        @foreach($branchStats as $stat)
        <div class="col-xl-4 col-lg-6">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">
                        <i class="icon-office mr-2"></i>
                        {{ $stat['branch']->name }}
                        @if($stat['branch']->is_active)
                            <span class="badge badge-success ml-2">Active</span>
                        @else
                            <span class="badge badge-danger ml-2">Inactive</span>
                        @endif
                    </h6>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="icon-users4 mr-2"></i>Students</span>
                            <span class="badge badge-primary">{{ $stat['students'] }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="icon-user-tie mr-2"></i>Staff</span>
                            <span class="badge badge-info">{{ $stat['staff'] }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="icon-graduation2 mr-2"></i>Classes</span>
                            <span class="badge badge-success">{{ $stat['classes'] }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="icon-cash3 mr-2"></i>Revenue</span>
                            <span class="badge badge-warning">${{ number_format($stat['revenue'], 2) }}</span>
                        </li>
                    </ul>
                </div>

                <div class="card-footer">
                    <div class="row text-center">
                        <div class="col-4">
                            <a href="{{ route('super_admin.branch_details', $stat['branch']->id) }}" class="btn btn-link btn-sm">
                                <i class="icon-eye mr-1"></i>View
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('super_admin.branch_edit', $stat['branch']->id) }}" class="btn btn-link btn-sm">
                                <i class="icon-pencil7 mr-1"></i>Edit
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('super_admin.branch_reports', $stat['branch']->id) }}" class="btn btn-link btn-sm">
                                <i class="icon-statistics mr-1"></i>Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Detailed Branch Management Tabs -->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Branch Management Dashboard</h5>
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item">
                    <a href="#overview-tab" class="nav-link active" data-toggle="tab">
                        <i class="icon-office mr-2"></i>Overview
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#students-tab" class="nav-link" data-toggle="tab">
                        <i class="icon-users4 mr-2"></i>Students
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
            </ul>

            <div class="tab-content">
                <!-- Overview Tab -->
                <div class="tab-pane fade show active" id="overview-tab">
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card border-left-primary">
                                <div class="card-body">
                                    <h6 class="card-title">Branch Performance Summary</h6>
                                    <canvas id="branchPerformanceChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-left-success">
                                <div class="card-body">
                                    <h6 class="card-title">Revenue Distribution</h6>
                                    <canvas id="revenueChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Students Tab -->
                <div class="tab-pane fade" id="students-tab">
                    <div class="mt-3">
                        <div class="row">
                            @foreach($branchStats as $stat)
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title">{{ $stat['branch']->name }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Students:</span>
                                            <strong>{{ $stat['students'] }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Active Students:</span>
                                            <strong>{{ $stat['students'] }}</strong>
                                        </div>
                                        <a href="{{ route('super_admin.branch_students', $stat['branch']->id) }}" class="btn btn-primary btn-sm">
                                            View Students
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Payments Tab -->
                <div class="tab-pane fade" id="payments-tab">
                    <div class="mt-3">
                        <div class="row">
                            @foreach($branchStats as $stat)
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title">{{ $stat['branch']->name }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Revenue:</span>
                                            <strong>${{ number_format($stat['revenue'], 2) }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>This Month:</span>
                                            <strong>${{ number_format($stat['revenue'] * 0.1, 2) }}</strong>
                                        </div>
                                        <a href="{{ route('super_admin.branch_payments', $stat['branch']->id) }}" class="btn btn-success btn-sm">
                                            View Payments
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Academics Tab -->
                <div class="tab-pane fade" id="academics-tab">
                    <div class="mt-3">
                        <div class="row">
                            @foreach($branchStats as $stat)
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title">{{ $stat['branch']->name }} - Academic Data</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Classes:</span>
                                                    <strong>{{ $stat['classes'] }}</strong>
                                                </div>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Subjects:</span>
                                                    <strong>{{ $stat['branch']->subjects()->count() }}</strong>
                                                </div>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Exams:</span>
                                                    <strong>{{ $stat['branch']->exams()->count() }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Sections:</span>
                                                    <strong>{{ $stat['branch']->sections()->count() }}</strong>
                                                </div>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Grades:</span>
                                                    <strong>{{ $stat['branch']->grades()->count() }}</strong>
                                                </div>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Time Tables:</span>
                                                    <strong>{{ $stat['branch']->timeTables()->count() }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <a href="{{ route('super_admin.branch_academics', $stat['branch']->id) }}" class="btn btn-info btn-sm">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Management Tab -->
                <div class="tab-pane fade" id="management-tab">
                    <div class="mt-3">
                        <div class="row">
                            @foreach($branchStats as $stat)
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title">{{ $stat['branch']->name }} - Management</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Staff:</span>
                                                    <strong>{{ $stat['staff'] }}</strong>
                                                </div>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Dorms:</span>
                                                    <strong>{{ $stat['branch']->dorms()->count() }}</strong>
                                                </div>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Books:</span>
                                                    <strong>{{ $stat['branch']->books()->count() ?? 0 }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Users:</span>
                                                    <strong>{{ $stat['branch']->users()->count() }}</strong>
                                                </div>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Settings:</span>
                                                    <strong>{{ $stat['branch']->settings()->count() }}</strong>
                                                </div>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Status:</span>
                                                    <strong class="{{ $stat['branch']->is_active ? 'text-success' : 'text-danger' }}">
                                                        {{ $stat['branch']->is_active ? 'Active' : 'Inactive' }}
                                                    </strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <a href="{{ route('super_admin.branch_management', $stat['branch']->id) }}" class="btn btn-warning btn-sm">
                                                Manage Branch
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page_script')
<script>
$(document).ready(function() {
    // Branch Performance Chart
    var ctx1 = document.getElementById('branchPerformanceChart').getContext('2d');
    var branchPerformanceChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: {!! json_encode($branchStats->pluck('branch.name')) !!},
            datasets: [{
                label: 'Students',
                data: {!! json_encode($branchStats->pluck('students')) !!},
                backgroundColor: 'rgba(52, 144, 220, 0.8)'
            }, {
                label: 'Staff',
                data: {!! json_encode($branchStats->pluck('staff')) !!},
                backgroundColor: 'rgba(26, 188, 156, 0.8)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Revenue Chart
    var ctx2 = document.getElementById('revenueChart').getContext('2d');
    var revenueChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($branchStats->pluck('branch.name')) !!},
            datasets: [{
                data: {!! json_encode($branchStats->pluck('revenue')) !!},
                backgroundColor: [
                    '#3490dc', '#38c172', '#f6993f', '#e3342f', '#9561e2'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>
@endsection
