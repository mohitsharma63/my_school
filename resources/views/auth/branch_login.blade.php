
@extends('layouts.login_master')

@section('content')
<div class="page-content">
    <div class="content-wrapper">
        <div class="content-inner">
            <div class="content d-flex justify-content-center align-items-center">
                <div class="login-form">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="text-center mb-3">
                                @if($branch->logo)
                                    <img src="{{ $branch->logo_url }}" alt="{{ $branch->name }}" class="mb-3" style="max-height: 80px;">
                                @endif
                                <h5 class="mb-0">{{ $branch->name }}</h5>
                                <span class="d-block text-muted">Sign in to your account</span>
                            </div>

                            <form method="POST" action="{{ route('branch.login.submit', $branch->slug) }}">
                                @csrf

                                <div class="form-group form-group-feedback form-group-feedback-left">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email') }}" placeholder="Email" required>
                                    <div class="form-control-feedback">
                                        <i class="icon-user text-muted"></i>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group form-group-feedback form-group-feedback-left">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           name="password" placeholder="Password" required>
                                    <div class="form-control-feedback">
                                        <i class="icon-lock2 text-muted"></i>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Sign in <i class="icon-circle-right2 ml-2"></i>
                                    </button>
                                </div>

                                <div class="text-center">
                                    <a href="{{ route('branch.selection') }}">Choose Different Branch</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
