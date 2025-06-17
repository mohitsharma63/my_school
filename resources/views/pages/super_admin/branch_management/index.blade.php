
@extends('layouts.master')
@section('page_title', 'Branch Management')
@section('content')

<div class="content">
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">All Branches</h6>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="reload"></a>
                    <a class="list-icons-item" data-action="remove"></a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Branch Name</th>
                            <th>Students</th>
                            <th>Staff</th>
                            <th>Revenue</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($branches as $key => $branch)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <span class="badge badge-{{ $branch->is_active ? 'success' : 'danger' }}">
                                            {{ $branch->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="font-weight-semibold">{{ $branch->name }}</div>
                                        <div class="text-muted">{{ $branch->address }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-primary">{{ $branch->students()->count() }}</span>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $branch->staff()->count() }}</span>
                            </td>
                            <td>
                                <span class="font-weight-semibold text-success">
                                    ₦{{ number_format($branch->payments()->sum('amount')) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $branch->is_active ? 'success' : 'danger' }}">
                                    {{ $branch->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $branch->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('branches.show', $branch->id) }}" class="dropdown-item">
                                                <i class="icon-eye"></i> View Details
                                            </a>
                                            <a href="{{ route('super_admin.branch_students', $branch->id) }}" class="dropdown-item">
                                                <i class="icon-users"></i> Students
                                            </a>
                                            <a href="{{ route('super_admin.branch_payments', $branch->id) }}" class="dropdown-item">
                                                <i class="icon-cash"></i> Payments
                                            </a>
                                            <a href="{{ route('super_admin.branch_academics', $branch->id) }}" class="dropdown-item">
                                                <i class="icon-books"></i> Academics
                                            </a>
                                            <a href="{{ route('super_admin.branch_management', $branch->id) }}" class="dropdown-item">
                                                <i class="icon-gear"></i> Management
                                            </a>
                                            <a href="{{ route('branches.edit', $branch->id) }}" class="dropdown-item">
                                                <i class="icon-pencil"></i> Edit
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mt-4">
        <div class="col-lg-3 col-sm-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">{{ $branches->count() }}</h3>
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
                            <h3 class="mb-0">{{ $branches->where('is_active', true)->count() }}</h3>
                            <span>Active Branches</span>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="icon-checkmark icon-3x opacity-75"></i>
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
                            <h3 class="mb-0">{{ $branches->sum(function($branch) { return $branch->students()->count(); }) }}</h3>
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
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">₦{{ number_format($branches->sum(function($branch) { return $branch->payments()->sum('amount'); })) }}</h3>
                            <span>Total Revenue</span>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="icon-cash icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
