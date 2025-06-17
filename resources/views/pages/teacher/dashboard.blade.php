
@extends('layouts.master')
@section('page_title', 'Teacher Dashboard')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">Welcome {{ Auth::user()->name }}</h5>
                        {!! Qs::getPanelOptions() !!}
                    </div>

                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="card bg-primary">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div>
                                                <h3 class="text-white mb-0">{{ $stats['my_classes'] }}</h3>
                                                <span class="text-white-75">My Classes</span>
                                            </div>
                                            <div class="ml-auto">
                                                <i class="icon-users text-white-75 icon-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card bg-success">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div>
                                                <h3 class="text-white mb-0">{{ $stats['my_students'] }}</h3>
                                                <span class="text-white-75">My Students</span>
                                            </div>
                                            <div class="ml-auto">
                                                <i class="icon-graduation text-white-75 icon-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card bg-warning">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div>
                                                <h3 class="text-white mb-0">{{ $stats['pending_marks'] }}</h3>
                                                <span class="text-white-75">Pending Marks</span>
                                            </div>
                                            <div class="ml-auto">
                                                <i class="icon-clipboard text-white-75 icon-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card bg-info">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div>
                                                <h3 class="text-white mb-0">{{ $stats['upcoming_exams']->count() }}</h3>
                                                <span class="text-white-75">Upcoming Exams</span>
                                            </div>
                                            <div class="ml-auto">
                                                <i class="icon-book text-white-75 icon-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($stats['upcoming_exams']->count() > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Upcoming Exams</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Exam Name</th>
                                        <th>Date</th>
                                        <th>Class</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['upcoming_exams'] as $exam)
                                    <tr>
                                        <td>{{ $exam->name }}</td>
                                        <td>{{ date('M d, Y', strtotime($exam->created_at)) }}</td>
                                        <td>{{ $exam->my_class->name ?? 'N/A' }}</td>
                                        <td><span class="badge badge-primary">Upcoming</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection
