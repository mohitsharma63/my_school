
@extends('layouts.master')

@section('page_title', 'Branch Management Dashboard')

@section('content')
<div class="content">
    <div class="row">
        <!-- Overall Stats -->
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="icon-office icon-2x mr-3"></i>
                        <div>
                            <h3 class="mb-0">{{ $stats['total_branches'] }}</h3>
                            <span>Total Branches</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="icon-users icon-2x mr-3"></i>
                        <div>
                            <h3 class="mb-0">{{ $stats['total_students'] }}</h3>
                            <span>Total Students</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="icon-user-tie icon-2x mr-3"></i>
                        <div>
                            <h3 class="mb-0">{{ $stats['total_staff'] }}</h3>
                            <span>Total Staff</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="icon-cash icon-2x mr-3"></i>
                        <div>
                            <h3 class="mb-0">${{ number_format($stats['total_revenue'], 2) }}</h3>
                            <span>Total Revenue</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Branch Comparison Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Branch Comparison</h5>
            <div class="header-elements">
                <a href="{{ route('super_admin.branch_management.reports') }}" class="btn btn-primary btn-sm">
                    <i class="icon-stats-dots mr-2"></i>Detailed Reports
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Branch</th>
                            <th>Students</th>
                            <th>Staff</th>
                            <th>Revenue</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($branchStats as $branch)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <i class="icon-office icon-2x text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $branch['name'] }}</h6>
                                        <small class="text-muted">ID: {{ $branch['id'] }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-success">{{ $branch['students_count'] }}</span>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $branch['staff_count'] }}</span>
                            </td>
                            <td>
                                <strong>${{ number_format($branch['revenue'], 2) }}</strong>
                            </td>
                            <td>
                                @if($branch['is_active'])
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="list-icons">
                                    <a href="{{ route('super_admin.branch_management.users', $branch['id']) }}" 
                                       class="list-icons-item" title="Manage Users">
                                        <i class="icon-users"></i>
                                    </a>
                                    <a href="{{ route('branches.edit', $branch['id']) }}" 
                                       class="list-icons-item" title="Edit Branch">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
