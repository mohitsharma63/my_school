
@extends('layouts.master')
@section('page_title', $branch->name . ' - Payments')
@section('content')

<div class="content">
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">₦{{ number_format($totalRevenue) }}</h3>
                            <span>Total Revenue</span>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="icon-cash icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">₦{{ number_format($monthlyRevenue) }}</h3>
                            <span>This Month</span>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="icon-calendar icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">{{ $branch->name }} - Payment Records</h6>
            <div class="header-elements">
                <a href="{{ route('branches.index') }}" class="btn btn-primary">
                    <i class="icon-arrow-left8 mr-2"></i> Back to Branches
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Payment Title</th>
                            <th>Class</th>
                            <th>Amount</th>
                            <th>Year</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $key => $payment)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $payment->title }}</td>
                            <td>{{ $payment->my_class->name ?? 'All Classes' }}</td>
                            <td>
                                <span class="font-weight-semibold text-success">
                                    ₦{{ number_format($payment->amount) }}
                                </span>
                            </td>
                            <td>{{ $payment->year }}</td>
                            <td>{{ $payment->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
