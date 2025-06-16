
@extends('layouts.master')

@section('page_title', 'Super Admin Dashboard')

@section('content')
<div class="content">
    <!-- Branch Selector -->
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><span class="font-weight-semibold">Multi-Branch Management Dashboard</span></h4>
            </div>
            
            <div class="header-elements">
                <div class="d-flex justify-content-center">
                    <select id="branch-selector" class="form-control select-branch">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-blue-400 has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0 text-white">{{ $totalBranches }}</h3>
                        <span class="text-uppercase font-size-xs text-white-75">Total Branches</span>
                    </div>
                    <div class="ml-3 align-self-center">
                        <i class="icon-office icon-3x text-white-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-success-400 has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0 text-white">{{ $totalStudents }}</h3>
                        <span class="text-uppercase font-size-xs text-white-75">Total Students</span>
                    </div>
                    <div class="ml-3 align-self-center">
                        <i class="icon-users icon-3x text-white-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-warning-400 has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0 text-white">{{ $totalStaff }}</h3>
                        <span class="text-uppercase font-size-xs text-white-75">Total Staff</span>
                    </div>
                    <div class="ml-3 align-self-center">
                        <i class="icon-user-tie icon-3x text-white-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-indigo-400 has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0 text-white">${{ number_format($branchStats->sum('revenue'), 2) }}</h3>
                        <span class="text-uppercase font-size-xs text-white-75">Total Revenue</span>
                    </div>
                    <div class="ml-3 align-self-center">
                        <i class="icon-cash icon-3x text-white-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Branch Performance Table -->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Branch Performance Overview</h6>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="reload"></a>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Branch</th>
                        <th>Students</th>
                        <th>Staff</th>
                        <th>Classes</th>
                        <th>Revenue</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($branchStats as $stat)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $stat['branch']->logo_url }}" alt="" class="rounded-circle mr-2" width="32" height="32">
                                <div>
                                    <div class="font-weight-semibold">{{ $stat['branch']->name }}</div>
                                    <div class="text-muted font-size-sm">{{ $stat['branch']->address }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge badge-success">{{ $stat['students'] }}</span></td>
                        <td><span class="badge badge-info">{{ $stat['staff'] }}</span></td>
                        <td><span class="badge badge-warning">{{ $stat['classes'] }}</span></td>
                        <td>${{ number_format($stat['revenue'], 2) }}</td>
                        <td>
                            @if($stat['branch']->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="list-icons">
                                <a href="{{ route('branches.manage', $stat['branch']->id) }}" class="list-icons-item text-primary" data-popup="tooltip" title="Manage Branch">
                                    <i class="icon-gear"></i>
                                </a>
                                <a href="{{ route('branches.switch', $stat['branch']->id) }}" class="list-icons-item text-success switch-branch" data-popup="tooltip" title="Switch to Branch">
                                    <i class="icon-switch"></i>
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

<script>
$(document).ready(function() {
    // Branch switching functionality
    $('.switch-branch').on('click', function(e) {
        e.preventDefault();
        var branchId = $(this).attr('href').split('/').pop();
        
        $.post('{{ route("branch.switch") }}', {
            branch_id: branchId,
            _token: '{{ csrf_token() }}'
        }).done(function(response) {
            if (response.success) {
                location.reload();
            }
        });
    });

    // Branch selector change
    $('#branch-selector').on('change', function() {
        var branchId = $(this).val();
        if (branchId) {
            window.location.href = '{{ route("dashboard") }}?branch=' + branchId;
        } else {
            window.location.href = '{{ route("dashboard") }}';
        }
    });
});
</script>
@endsection
