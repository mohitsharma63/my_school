
@extends('layouts.master')
@section('page_title', $branch->name . ' - Academics')
@section('content')

<div class="content">
    <div class="row">
        <!-- Classes -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Classes & Sections</h6>
                </div>
                <div class="card-body">
                    @foreach($classes as $class)
                    <div class="media border-bottom pb-3 mb-3">
                        <div class="media-body">
                            <div class="media-title font-weight-semibold">{{ $class->name }}</div>
                            <span class="text-muted">
                                {{ $class->sections->count() }} sections
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Subjects -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Subjects</h6>
                </div>
                <div class="card-body">
                    @foreach($subjects as $subject)
                    <div class="media border-bottom pb-3 mb-3">
                        <div class="media-body">
                            <div class="media-title font-weight-semibold">{{ $subject->name }}</div>
                            <span class="text-muted">{{ $subject->slug }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Exams -->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Recent Exams</h6>
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
                            <th>Exam Name</th>
                            <th>Category</th>
                            <th>Year</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exams as $exam)
                        <tr>
                            <td>{{ $exam->name }}</td>
                            <td>{{ $exam->category }}</td>
                            <td>{{ $exam->year }}</td>
                            <td>{{ $exam->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
