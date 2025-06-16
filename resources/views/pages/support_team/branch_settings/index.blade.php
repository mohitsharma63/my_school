
@extends('layouts.master')

@section('page_title', 'Branch Settings - ' . $branch->name)

@section('content')
<div class="content">
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><span class="font-weight-semibold">Branch Settings</span> - {{ $branch->name }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a href="#general" class="nav-link active" data-toggle="pill">
                                <i class="icon-gear mr-2"></i> General Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#theme" class="nav-link" data-toggle="pill">
                                <i class="icon-palette mr-2"></i> Theme & Branding
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#academic" class="nav-link" data-toggle="pill">
                                <i class="icon-graduation mr-2"></i> Academic Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#notifications" class="nav-link" data-toggle="pill">
                                <i class="icon-notification2 mr-2"></i> Notifications
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="tab-content">
                <!-- General Settings -->
                <div class="tab-pane fade show active" id="general">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">General Information</h6>
                        </div>
                        <form action="{{ route('branch.settings.general') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>School Name <span class="text-danger">*</span></label>
                                            <input type="text" name="school_name" class="form-control" 
                                                   value="{{ $settings->get('school_name')->description ?? $branch->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Academic Year <span class="text-danger">*</span></label>
                                            <input type="text" name="academic_year" class="form-control" 
                                                   value="{{ $settings->get('academic_year')->description ?? $branch->academic_year }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>School Address <span class="text-danger">*</span></label>
                                    <textarea name="school_address" class="form-control" rows="3" required>{{ $settings->get('school_address')->description ?? $branch->address }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            <input type="text" name="school_phone" class="form-control" 
                                                   value="{{ $settings->get('school_phone')->description ?? $branch->phone }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="email" name="school_email" class="form-control" 
                                                   value="{{ $settings->get('school_email')->description ?? $branch->email }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Principal Name</label>
                                    <input type="text" name="principal_name" class="form-control" 
                                           value="{{ $settings->get('principal_name')->description ?? $branch->principal_name }}">
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Save General Settings</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Theme Settings -->
                <div class="tab-pane fade" id="theme">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">Theme & Branding</h6>
                        </div>
                        <form action="{{ route('branch.settings.theme') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Primary Color</label>
                                            <input type="color" name="primary_color" class="form-control" 
                                                   value="{{ $settings->get('primary_color')->description ?? '#007bff' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Secondary Color</label>
                                            <input type="color" name="secondary_color" class="form-control" 
                                                   value="{{ $settings->get('secondary_color')->description ?? '#6c757d' }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Theme Style</label>
                                    <select name="theme_style" class="form-control">
                                        <option value="default" {{ ($settings->get('theme_style')->description ?? 'default') == 'default' ? 'selected' : '' }}>Default</option>
                                        <option value="dark" {{ ($settings->get('theme_style')->description ?? '') == 'dark' ? 'selected' : '' }}>Dark Theme</option>
                                        <option value="blue" {{ ($settings->get('theme_style')->description ?? '') == 'blue' ? 'selected' : '' }}>Blue Theme</option>
                                        <option value="green" {{ ($settings->get('theme_style')->description ?? '') == 'green' ? 'selected' : '' }}>Green Theme</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>School Logo</label>
                                    <input type="file" name="logo" class="form-control-file" accept="image/*">
                                    @if($settings->get('logo'))
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $settings->get('logo')->description) }}" 
                                                 alt="Current Logo" class="img-thumbnail" style="max-height: 100px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Save Theme Settings</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Academic Settings -->
                <div class="tab-pane fade" id="academic">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">Academic Configuration</h6>
                        </div>
                        <form action="{{ route('branch.settings.academic') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Grading System</label>
                                            <select name="grading_system" class="form-control">
                                                <option value="percentage" {{ ($settings->get('grading_system')->description ?? 'percentage') == 'percentage' ? 'selected' : '' }}>Percentage (0-100%)</option>
                                                <option value="letter" {{ ($settings->get('grading_system')->description ?? '') == 'letter' ? 'selected' : '' }}>Letter Grades (A-F)</option>
                                                <option value="points" {{ ($settings->get('grading_system')->description ?? '') == 'points' ? 'selected' : '' }}>Points (0-4.0)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Passing Grade</label>
                                            <input type="number" name="passing_grade" class="form-control" 
                                                   value="{{ $settings->get('passing_grade')->description ?? '60' }}" min="0" max="100">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Term System</label>
                                            <select name="term_system" class="form-control">
                                                <option value="semester" {{ ($settings->get('term_system')->description ?? 'semester') == 'semester' ? 'selected' : '' }}>Semester</option>
                                                <option value="trimester" {{ ($settings->get('term_system')->description ?? '') == 'trimester' ? 'selected' : '' }}>Trimester</option>
                                                <option value="quarter" {{ ($settings->get('term_system')->description ?? '') == 'quarter' ? 'selected' : '' }}>Quarter</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Late Payment Fee ($)</label>
                                            <input type="number" name="late_payment_fee" class="form-control" 
                                                   value="{{ $settings->get('late_payment_fee')->description ?? '0' }}" min="0" step="0.01">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" name="attendance_required" class="form-check-input" 
                                               {{ ($settings->get('attendance_required')->description ?? '0') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label">Require attendance tracking</label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Save Academic Settings</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="tab-pane fade" id="notifications">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">Notification Preferences</h6>
                        </div>
                        <form action="{{ route('branch.settings.notifications') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" name="email_notifications" class="form-check-input" 
                                               {{ ($settings->get('email_notifications')->description ?? '1') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label">Enable email notifications</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" name="sms_notifications" class="form-check-input" 
                                               {{ ($settings->get('sms_notifications')->description ?? '0') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label">Enable SMS notifications</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" name="parent_portal_access" class="form-check-input" 
                                               {{ ($settings->get('parent_portal_access')->description ?? '1') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label">Allow parent portal access</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" name="online_payment" class="form-check-input" 
                                               {{ ($settings->get('online_payment')->description ?? '0') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label">Enable online payments</label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Save Notification Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
