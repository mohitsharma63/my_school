
@extends('layouts.master')
@section('page_title', $branch->name . ' - Students')
@section('content')

<div class="content">
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">{{ $branch->name }} - Students</h6>
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
                            <th>Name</th>
                            <th>Admission No</th>
                            <th>Class</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $key => $student)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="font-weight-semibold">{{ $student->user->name }}</div>
                                        <div class="text-muted">{{ $student->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $student->adm_no }}</td>
                            <td>{{ $student->my_class->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $student->is_graduated ? 'warning' : 'success' }}">
                                    {{ $student->is_graduated ? 'Graduated' : 'Active' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-primary">
                                    <i class="icon-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $students->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
