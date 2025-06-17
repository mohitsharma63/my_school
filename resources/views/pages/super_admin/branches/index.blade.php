
@extends('layouts.master')

@section('page_title', 'Manage Branches')

@section('content')
<div class="content">
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><span class="font-weight-semibold">Branch Management</span></h4>
            </div>

            <div class="header-elements">
                <div class="d-flex justify-content-center">
                    <a href="{{ route('super_admin.branches.create') }}" class="btn btn-primary">
                        <i class="icon-plus-circle2 mr-2"></i> Add New Branch
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">All Branches</h5>
                    {!! Qs::getPanelOptions() !!}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Contact</th>
                                    <th>Students</th>
                                    <th>Staff</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($branches as $branch)
                                <tr>
                                    <td>
                                        <strong>{{ $branch->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $branch->slug }}</small>
                                    </td>
                                    <td>{{ $branch->address ?: 'N/A' }}</td>
                                    <td>
                                        @if($branch->phone)
                                            <div>{{ $branch->phone }}</div>
                                        @endif
                                        @if($branch->email)
                                            <div>{{ $branch->email }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $branch->students_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $branch->staff_count }}</span>
                                    </td>
                                    <td>
                                        @if($branch->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{ route('super_admin.branches.show', $branch->id) }}" class="dropdown-item">
                                                        <i class="icon-eye"></i> View Details
                                                    </a>
                                                    <a href="{{ route('super_admin.branches.edit', $branch->id) }}" class="dropdown-item">
                                                        <i class="icon-pencil"></i> Edit
                                                    </a>
                                                    <a href="{{ route('super_admin.branch_management.users', $branch->id) }}" class="dropdown-item">
                                                        <i class="icon-users"></i> Manage Users
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <form action="{{ route('super_admin.branches.destroy', $branch->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this branch?')">
                                                            <i class="icon-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $branches->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
