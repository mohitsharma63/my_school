
@extends('layouts.master')
@section('page_title', 'Edit Branch - ' . $branch->name)
@section('content')

<div class="content">
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Branch - {{ $branch->name }}</h6>
            <div class="header-elements">
                <a href="{{ route('branches.index') }}" class="btn btn-primary">
                    <i class="icon-arrow-left8 mr-2"></i> Back to Branches
                </a>
            </div>
        </div>

        <form action="{{ route('branches.update', $branch->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Branch Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $branch->name) }}" required class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email', $branch->email) }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $branch->phone) }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="is_active" class="form-control">
                                <option value="1" {{ $branch->is_active ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$branch->is_active ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" rows="3" class="form-control">{{ old('address', $branch->address) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">Update Branch <i class="icon-paperplane ml-2"></i></button>
            </div>
        </form>
    </div>
</div>

@endsection
