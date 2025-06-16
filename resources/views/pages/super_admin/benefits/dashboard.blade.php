
@extends('layouts.master')

@section('page_title', 'Multi-Branch Benefits Dashboard')

@section('content')
<div class="content">
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Multi-Branch Architecture</span> - Benefits Overview</h4>
            </div>
        </div>
    </div>

    <div class="content-wrapper">
        <!-- Cost Efficiency -->
        <div class="card">
            <div class="card-header header-elements-inline bg-primary text-white">
                <h5 class="card-title">💰 Cost Efficiency</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-success">{{ $benefits['cost_efficiency']['total_branches'] }}</h3>
                            <span class="text-muted">Total Branches</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-success">₦{{ number_format($benefits['cost_efficiency']['estimated_savings']) }}</h3>
                            <span class="text-muted">Estimated Savings</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-success">{{ $benefits['cost_efficiency']['maintenance_cost_reduction'] }}</h3>
                            <span class="text-muted">Maintenance Reduction</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-primary">1</h3>
                            <span class="text-muted">Single Deployment</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Centralized Maintenance -->
        <div class="card">
            <div class="card-header header-elements-inline bg-success text-white">
                <h5 class="card-title">🔧 Centralized Maintenance</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="icon-checkmark-circle text-success" style="font-size: 2rem;"></i>
                            <div class="mt-2">
                                <strong>Single Codebase</strong>
                                <div class="text-muted">Unified development & maintenance</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h3 class="text-success">{{ $benefits['centralized_maintenance']['deployment_time'] }}</h3>
                            <span class="text-muted">Update Deployment Time</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h3 class="text-success">{{ $benefits['centralized_maintenance']['maintenance_efficiency'] }}</h3>
                            <span class="text-muted">Maintenance Efficiency</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Security -->
        <div class="card">
            <div class="card-header header-elements-inline bg-warning text-white">
                <h5 class="card-title">🔒 Data Security & Isolation</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            <strong>Complete Data Isolation:</strong> Each branch's data is completely separated and secure
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
                                <th>Payments</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($benefits['data_security']['branch_data_separation'] as $branch)
                            <tr>
                                <td>{{ $branch['name'] }}</td>
                                <td>{{ $branch['students'] }}</td>
                                <td>{{ $branch['staff'] }}</td>
                                <td>{{ $branch['payments'] }}</td>
                                <td><span class="badge badge-success">Isolated</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Scalability -->
        <div class="card">
            <div class="card-header header-elements-inline bg-info text-white">
                <h5 class="card-title">📈 Scalability</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-info">{{ $benefits['scalability']['current_branches'] }}</h3>
                            <span class="text-muted">Current Branches</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-info">{{ $benefits['scalability']['recent_additions'] }}</h3>
                            <span class="text-muted">Added (6 months)</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-info">{{ $benefits['scalability']['average_setup_time'] }}</h3>
                            <span class="text-muted">Setup Time</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-info">∞</h3>
                            <span class="text-muted">Max Branches</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Standardization -->
        <div class="card">
            <div class="card-header header-elements-inline bg-secondary text-white">
                <h5 class="card-title">🧪 Standardization</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($benefits['standardization']['feature_consistency'] as $feature => $percentage)
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="progress mb-2">
                                <div class="progress-bar bg-success" style="width: {{ $percentage }}%"></div>
                            </div>
                            <strong>{{ $percentage }}%</strong>
                            <div class="text-muted">{{ ucwords(str_replace('_', ' ', $feature)) }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Reporting -->
        <div class="card">
            <div class="card-header header-elements-inline bg-dark text-white">
                <h5 class="card-title">📊 Cross-Branch Reporting</h5>
                <div class="header-elements">
                    <a href="{{ route('benefits.performance-comparison') }}" class="btn btn-light btn-sm">View Detailed Comparison</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Branch</th>
                                <th>Students</th>
                                <th>Revenue</th>
                                <th>Performance Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($benefits['reporting']['cross_branch_comparison'] as $branch)
                            <tr>
                                <td>{{ $branch['name'] }}</td>
                                <td>{{ $branch['total_students'] }}</td>
                                <td>₦{{ number_format($branch['total_revenue']) }}</td>
                                <td>
                                    <span class="badge badge-{{ $branch['performance_score'] >= 85 ? 'success' : ($branch['performance_score'] >= 70 ? 'warning' : 'danger') }}">
                                        {{ $branch['performance_score'] }}%
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card">
            <div class="card-body text-center">
                <div class="row">
                    <div class="col-md-4">
                        <a href="{{ route('benefits.cost-analysis') }}" class="btn btn-primary btn-block">
                            <i class="icon-calculator mr-2"></i>Cost Analysis Report
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('benefits.performance-comparison') }}" class="btn btn-success btn-block">
                            <i class="icon-stats-bars mr-2"></i>Performance Comparison
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('branches.index') }}" class="btn btn-info btn-block">
                            <i class="icon-office mr-2"></i>Manage Branches
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
