
@extends('layouts.master')

@section('page_title', 'Branch Performance Comparison')

@section('content')
<div class="content">
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><i class="icon-stats-bars mr-2"></i> <span class="font-weight-semibold">Performance Comparison</span> - Cross-Branch Analytics</h4>
            </div>
        </div>
    </div>

    <div class="content-wrapper">
        <!-- Performance Overview -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Branch Performance Overview</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped datatable-basic">
                        <thead>
                            <tr>
                                <th>Branch Name</th>
                                <th>Total Students</th>
                                <th>Total Revenue</th>
                                <th>Total Exams</th>
                                <th>Revenue per Student</th>
                                <th>Performance Index</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($branches as $branch)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <span class="text-white font-weight-bold">{{ substr($branch['name'], 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <strong>{{ $branch['name'] }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-primary">{{ $branch['students'] }}</span>
                                </td>
                                <td>
                                    <strong>₦{{ number_format($branch['revenue']) }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $branch['exams'] }}</span>
                                </td>
                                <td>
                                    ₦{{ number_format($branch['revenue_per_student']) }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress mr-2" style="width: 60px;">
                                            <div class="progress-bar bg-{{ $branch['performance_index'] >= 80 ? 'success' : ($branch['performance_index'] >= 60 ? 'warning' : 'danger') }}" 
                                                 style="width: {{ min($branch['performance_index'], 100) }}%"></div>
                                        </div>
                                        <span class="font-weight-bold">{{ $branch['performance_index'] }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($branch['performance_index'] >= 80)
                                        <span class="badge badge-success">Excellent</span>
                                    @elseif($branch['performance_index'] >= 60)
                                        <span class="badge badge-warning">Good</span>
                                    @else
                                        <span class="badge badge-danger">Needs Improvement</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Performance Analytics -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Student Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="studentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Revenue Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Top Performing Branches</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @php $topBranches = collect($branches)->sortByDesc('performance_index')->take(3); @endphp
                    @foreach($topBranches as $index => $branch)
                    <div class="col-md-4">
                        <div class="card bg-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'dark') }} text-white">
                            <div class="card-body text-center">
                                <h1>{{ $index + 1 }}</h1>
                                <h5>{{ $branch['name'] }}</h5>
                                <div class="mt-2">
                                    <div>Performance Index: <strong>{{ $branch['performance_index'] }}</strong></div>
                                    <div>Students: <strong>{{ $branch['students'] }}</strong></div>
                                    <div>Revenue: <strong>₦{{ number_format($branch['revenue']) }}</strong></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Comparative Metrics -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Comparative Metrics</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-primary">{{ collect($branches)->sum('students') }}</h3>
                            <span class="text-muted">Total Students</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-success">₦{{ number_format(collect($branches)->sum('revenue')) }}</h3>
                            <span class="text-muted">Total Revenue</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-info">{{ round(collect($branches)->avg('performance_index'), 1) }}</h3>
                            <span class="text-muted">Average Performance</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-warning">{{ collect($branches)->sum('exams') }}</h3>
                            <span class="text-muted">Total Exams</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card">
            <div class="card-body text-center">
                <a href="{{ route('benefits.dashboard') }}" class="btn btn-primary mr-2">
                    <i class="icon-arrow-left52 mr-2"></i>Back to Benefits Dashboard
                </a>
                <a href="{{ route('benefits.cost-analysis') }}" class="btn btn-info">
                    <i class="icon-calculator mr-2"></i>View Cost Analysis
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('global_assets/js/plugins/visualization/d3/d3.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/visualization/c3/c3.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Chart data
    const branches = @json($branches);
    
    // Student Distribution Chart
    const studentChart = c3.generate({
        bindto: '#studentChart',
        data: {
            columns: branches.map(branch => [branch.name, branch.students]),
            type: 'pie'
        },
        color: {
            pattern: ['#2196F3', '#4CAF50', '#FF9800', '#9C27B0', '#F44336', '#00BCD4']
        }
    });
    
    // Revenue Distribution Chart
    const revenueChart = c3.generate({
        bindto: '#revenueChart',
        data: {
            columns: branches.map(branch => [branch.name, branch.revenue]),
            type: 'donut'
        },
        color: {
            pattern: ['#4CAF50', '#2196F3', '#FF9800', '#9C27B0', '#F44336', '#00BCD4']
        }
    });
});
</script>
@endpush
@endsection
