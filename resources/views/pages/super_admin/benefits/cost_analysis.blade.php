
@extends('layouts.master')

@section('page_title', 'Cost Analysis - Multi-Branch vs Traditional')

@section('content')
<div class="content">
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><i class="icon-calculator mr-2"></i> <span class="font-weight-semibold">Cost Analysis</span> - Multi-Branch Benefits</h4>
            </div>
        </div>
    </div>

    <div class="content-wrapper">
        <!-- Cost Comparison Overview -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Cost Comparison: Traditional vs Multi-Branch</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h4>Traditional Model (Separate Systems)</h4>
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Cost per Branch:</span>
                                        <strong>₦{{ number_format($analysis['traditional_model']['cost_per_branch']) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Maintenance per Branch:</span>
                                        <strong>₦{{ number_format($analysis['traditional_model']['maintenance_cost']) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Total Branches:</span>
                                        <strong>{{ $analysis['traditional_model']['total_branches'] }}</strong>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span><strong>Total Cost:</strong></span>
                                        <strong>₦{{ number_format($analysis['traditional_model']['total_cost']) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h4>Multi-Branch Model (Single System)</h4>
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Single Deployment:</span>
                                        <strong>₦{{ number_format($analysis['multi_branch_model']['single_deployment_cost']) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Total Maintenance:</span>
                                        <strong>₦{{ number_format($analysis['multi_branch_model']['maintenance_cost']) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Serves Branches:</span>
                                        <strong>{{ $analysis['multi_branch_model']['total_branches'] }}</strong>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span><strong>Total Cost:</strong></span>
                                        <strong>₦{{ number_format($analysis['multi_branch_model']['total_cost']) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Savings Summary -->
                <div class="card bg-primary text-white mt-4">
                    <div class="card-body text-center">
                        <h3>Total Savings with Multi-Branch Architecture</h3>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h2>₦{{ number_format($analysis['savings']) }}</h2>
                                <span>Absolute Savings</span>
                            </div>
                            <div class="col-md-6">
                                <h2>{{ $analysis['savings_percentage'] }}%</h2>
                                <span>Percentage Savings</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROI Analysis -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Return on Investment (ROI) Analysis</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Metric</th>
                                <th>Traditional Model</th>
                                <th>Multi-Branch Model</th>
                                <th>Improvement</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Initial Investment</strong></td>
                                <td>₦{{ number_format($analysis['traditional_model']['cost_per_branch'] * $analysis['traditional_model']['total_branches']) }}</td>
                                <td>₦{{ number_format($analysis['multi_branch_model']['single_deployment_cost']) }}</td>
                                <td class="text-success">{{ round((($analysis['traditional_model']['cost_per_branch'] * $analysis['traditional_model']['total_branches'] - $analysis['multi_branch_model']['single_deployment_cost']) / ($analysis['traditional_model']['cost_per_branch'] * $analysis['traditional_model']['total_branches'])) * 100, 1) }}% Lower</td>
                            </tr>
                            <tr>
                                <td><strong>Annual Maintenance</strong></td>
                                <td>₦{{ number_format($analysis['traditional_model']['maintenance_cost'] * $analysis['traditional_model']['total_branches']) }}</td>
                                <td>₦{{ number_format($analysis['multi_branch_model']['maintenance_cost']) }}</td>
                                <td class="text-success">{{ round((($analysis['traditional_model']['maintenance_cost'] * $analysis['traditional_model']['total_branches'] - $analysis['multi_branch_model']['maintenance_cost']) / ($analysis['traditional_model']['maintenance_cost'] * $analysis['traditional_model']['total_branches'])) * 100, 1) }}% Lower</td>
                            </tr>
                            <tr>
                                <td><strong>Deployment Time</strong></td>
                                <td>{{ $analysis['traditional_model']['total_branches'] }} weeks</td>
                                <td>1 week</td>
                                <td class="text-success">{{ round((($analysis['traditional_model']['total_branches'] - 1) / $analysis['traditional_model']['total_branches']) * 100, 1) }}% Faster</td>
                            </tr>
                            <tr>
                                <td><strong>Update Rollout</strong></td>
                                <td>{{ $analysis['traditional_model']['total_branches'] }} × 2 hours</td>
                                <td>30 minutes</td>
                                <td class="text-success">{{ round(((($analysis['traditional_model']['total_branches'] * 2) - 0.5) / ($analysis['traditional_model']['total_branches'] * 2)) * 100, 1) }}% Faster</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Break-even Analysis -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Break-even Analysis</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6>Break-even Point Analysis:</h6>
                    <p>With the current cost structure, the multi-branch system pays for itself immediately when serving more than <strong>2 branches</strong>.</p>
                    <p>Current deployment serves <strong>{{ $analysis['traditional_model']['total_branches'] }} branches</strong>, resulting in immediate cost savings.</p>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <h3 class="text-success">Day 1</h3>
                            <span class="text-muted">Break-even Achievement</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h3 class="text-success">{{ round($analysis['savings'] / 365) }}</h3>
                            <span class="text-muted">Daily Savings (₦)</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h3 class="text-success">{{ round($analysis['savings'] / 12) }}</h3>
                            <span class="text-muted">Monthly Savings (₦)</span>
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
                <a href="{{ route('benefits.performance-comparison') }}" class="btn btn-success">
                    <i class="icon-stats-bars mr-2"></i>View Performance Comparison
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
