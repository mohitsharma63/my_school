<?php

Auth::routes();

//Route::get('/test', 'TestController@index')->name('test');
Route::get('/privacy-policy', 'HomeController@privacy_policy')->name('privacy_policy');
Route::get('/terms-of-use', 'HomeController@terms_of_use')->name('terms_of_use');


Route::group(['middleware' => 'auth'], function () {

    Route::get('/', 'HomeController@dashboard')->name('home');
    Route::get('/home', 'HomeController@dashboard')->name('home');
    Route::get('/dashboard', 'Dashboard\MultiTenantDashboardController@index')->name('dashboard');

    // Branch switching
    Route::post('/branch/switch', 'BranchSwitchController@switch')->name('branch.switch');
    Route::get('/branch/current', 'BranchSwitchController@getCurrentBranch')->name('branch.current');

    // Branch settings
    Route::post('/branch/settings/general', 'SupportTeam\BranchSettingsController@updateGeneral')->name('branch.settings.general');
    Route::post('/branch/settings/theme', 'SupportTeam\BranchSettingsController@updateTheme')->name('branch.settings.theme');
    Route::post('/branch/settings/academic', 'SupportTeam\BranchSettingsController@updateAcademic')->name('branch.settings.academic');
    Route::post('/branch/settings/notifications', 'SupportTeam\BranchSettingsController@updateNotifications')->name('branch.settings.notifications');

    Route::group(['prefix' => 'my_account'], function() {
        Route::get('/', 'MyAccountController@edit_profile')->name('my_account');
        Route::put('/', 'MyAccountController@update_profile')->name('my_account.update');
        Route::put('/change_password', 'MyAccountController@change_pass')->name('my_account.change_pass');
    });

    /*************** Support Team *****************/
    Route::group(['namespace' => 'SupportTeam',], function(){

        /*************** Students *****************/
        Route::group(['prefix' => 'students'], function(){
            Route::get('reset_pass/{st_id}', 'StudentRecordController@reset_pass')->name('st.reset_pass');
            Route::get('graduated', 'StudentRecordController@graduated')->name('students.graduated');
            Route::put('not_graduated/{id}', 'StudentRecordController@not_graduated')->name('st.not_graduated');
            Route::get('list/{class_id}', 'StudentRecordController@listByClass')->name('students.list')->middleware('teamSAT');

            /* Promotions */
            Route::post('promote_selector', 'PromotionController@selector')->name('students.promote_selector');
            Route::get('promotion/manage', 'PromotionController@manage')->name('students.promotion_manage');
            Route::delete('promotion/reset/{pid}', 'PromotionController@reset')->name('students.promotion_reset');
            Route::delete('promotion/reset_all', 'PromotionController@reset_all')->name('students.promotion_reset_all');
            Route::get('promotion/{fc?}/{fs?}/{tc?}/{ts?}', 'PromotionController@promotion')->name('students.promotion');
            Route::post('promote/{fc}/{fs}/{tc}/{ts}', 'PromotionController@promote')->name('students.promote');

        });

        /*************** Users *****************/
        Route::group(['prefix' => 'users'], function(){
            Route::get('reset_pass/{id}', 'UserController@reset_pass')->name('users.reset_pass');
        });

        /*************** TimeTables *****************/
        Route::group(['prefix' => 'timetables'], function(){
            Route::get('/', 'TimeTableController@index')->name('tt.index');

            Route::group(['middleware' => 'teamSA'], function() {
                Route::post('/', 'TimeTableController@store')->name('tt.store');
                Route::put('/{tt}', 'TimeTableController@update')->name('tt.update');
                Route::delete('/{tt}', 'TimeTableController@delete')->name('tt.delete');
            });

            /*************** TimeTable Records *****************/
            Route::group(['prefix' => 'records'], function(){

                Route::group(['middleware' => 'teamSA'], function(){
                    Route::get('manage/{ttr}', 'TimeTableController@manage')->name('ttr.manage');
                    Route::post('/', 'TimeTableController@store_record')->name('ttr.store');
                    Route::get('edit/{ttr}', 'TimeTableController@edit_record')->name('ttr.edit');
                    Route::put('/{ttr}', 'TimeTableController@update_record')->name('ttr.update');
                });

                Route::get('show/{ttr}', 'TimeTableController@show_record')->name('ttr.show');
                Route::get('print/{ttr}', 'TimeTableController@print_record')->name('ttr.print');
                Route::delete('/{ttr}', 'TimeTableController@delete_record')->name('ttr.destroy');

            });

            /*************** Time Slots *****************/
            Route::group(['prefix' => 'time_slots', 'middleware' => 'teamSA'], function(){
                Route::post('/', 'TimeTableController@store_time_slot')->name('ts.store');
                Route::post('/use/{ttr}', 'TimeTableController@use_time_slot')->name('ts.use');
                Route::get('edit/{ts}', 'TimeTableController@edit_time_slot')->name('ts.edit');
                Route::delete('/{ts}', 'TimeTableController@delete_time_slot')->name('ts.destroy');
                Route::put('/{ts}', 'TimeTableController@update_time_slot')->name('ts.update');
            });

        });

        /*************** Payments *****************/
        Route::group(['prefix' => 'payments'], function(){

            Route::get('manage/{class_id?}', 'PaymentController@manage')->name('payments.manage');
            Route::get('invoice/{id}/{year?}', 'PaymentController@invoice')->name('payments.invoice');
            Route::get('receipts/{id}', 'PaymentController@receipts')->name('payments.receipts');

// Super Admin Branch Management Routes
Route::group(['middleware' => ['auth', 'super_admin'], 'prefix' => 'super_admin'], function () {
    Route::get('/branch/{id}/details', [App\Http\Controllers\SuperAdmin\BranchManagementController::class, 'branchDetails'])->name('super_admin.branch_details');
    Route::get('/branch/{id}/students', [App\Http\Controllers\SuperAdmin\BranchManagementController::class, 'branchStudents'])->name('super_admin.branch_students');
    Route::get('/branch/{id}/payments', [App\Http\Controllers\SuperAdmin\BranchManagementController::class, 'branchPayments'])->name('super_admin.branch_payments');
    Route::get('/branch/{id}/academics', [App\Http\Controllers\SuperAdmin\BranchManagementController::class, 'branchAcademics'])->name('super_admin.branch_academics');
    Route::get('/branch/{id}/management', [App\Http\Controllers\SuperAdmin\BranchManagementController::class, 'branchManagement'])->name('super_admin.branch_management');
    Route::get('/branch/{id}/edit', [App\Http\Controllers\SuperAdmin\BranchManagementController::class, 'editBranch'])->name('super_admin.branch_edit');
    Route::get('/branch/{id}/reports', [App\Http\Controllers\SuperAdmin\BranchManagementController::class, 'branchReports'])->name('super_admin.branch_reports');
});

// Dashboard API Routes
Route::group(['middleware' => ['auth'], 'prefix' => 'api'], function () {
    Route::get('/branch-statistics', [App\Http\Controllers\Dashboard\MultiTenantDashboardController::class, 'getBranchStatistics']);
    Route::get('/branch-details', [App\Http\Controllers\Dashboard\MultiTenantDashboardController::class, 'getBranchDetails']);
});

            Route::get('pdf_receipts/{id}', 'PaymentController@pdf_receipts')->name('payments.pdf_receipts');
            Route::post('select_year', 'PaymentController@select_year')->name('payments.select_year');
            Route::post('select_class', 'PaymentController@select_class')->name('payments.select_class');
            Route::delete('reset_record/{id}', 'PaymentController@reset_record')->name('payments.reset_record');
            Route::post('pay_now/{id}', 'PaymentController@pay_now')->name('payments.pay_now');
        });

        /*************** Pins *****************/
        Route::group(['prefix' => 'pins'], function(){
            Route::get('create', 'PinController@create')->name('pins.create');
            Route::get('/', 'PinController@index')->name('pins.index');
            Route::post('/', 'PinController@store')->name('pins.store');
            Route::get('enter/{id}', 'PinController@enter_pin')->name('pins.enter');
            Route::post('verify/{id}', 'PinController@verify')->name('pins.verify');
            Route::delete('/', 'PinController@destroy')->name('pins.destroy');
        });

        /*************** Marks *****************/
        Route::group(['prefix' => 'marks'], function(){

           // FOR teamSA
            Route::group(['middleware' => 'teamSA'], function(){
                Route::get('batch_fix', 'MarkController@batch_fix')->name('marks.batch_fix');
                Route::put('batch_update', 'MarkController@batch_update')->name('marks.batch_update');
                Route::get('tabulation/{exam?}/{class?}/{sec_id?}', 'MarkController@tabulation')->name('marks.tabulation');
                Route::post('tabulation', 'MarkController@tabulation_select')->name('marks.tabulation_select');
                Route::get('tabulation/print/{exam}/{class}/{sec_id}', 'MarkController@print_tabulation')->name('marks.print_tabulation');
            });

            // FOR teamSAT
            Route::group(['middleware' => 'teamSAT'], function(){
                Route::get('/', 'MarkController@index')->name('marks.index');
                Route::get('manage/{exam}/{class}/{section}/{subject}', 'MarkController@manage')->name('marks.manage');
                Route::put('update/{exam}/{class}/{section}/{subject}', 'MarkController@update')->name('marks.update');
                Route::put('comment_update/{exr_id}', 'MarkController@comment_update')->name('marks.comment_update');
                Route::put('skills_update/{skill}/{exr_id}', 'MarkController@skills_update')->name('marks.skills_update');
                Route::post('selector', 'MarkController@selector')->name('marks.selector');
                Route::get('bulk/{class?}/{section?}', 'MarkController@bulk')->name('marks.bulk');
                Route::post('bulk', 'MarkController@bulk_select')->name('marks.bulk_select');
            });

            Route::get('select_year/{id}', 'MarkController@year_selector')->name('marks.year_selector');
            Route::post('select_year/{id}', 'MarkController@year_selected')->name('marks.year_select');
            Route::get('show/{id}/{year}', 'MarkController@show')->name('marks.show');
            Route::get('print/{id}/{exam_id}/{year}', 'MarkController@print_view')->name('marks.print');

        });

        Route::resource('students', 'StudentRecordController');
        Route::resource('users', 'UserController');
        Route::resource('classes', 'MyClassController');
        Route::resource('sections', 'SectionController');
        Route::resource('subjects', 'SubjectController');
        Route::resource('grades', 'GradeController');
        Route::resource('exams', 'ExamController');
        Route::resource('dorms', 'DormController');
        Route::resource('payments', 'PaymentController');

    });

    /************************ AJAX ****************************/
    Route::group(['prefix' => 'ajax'], function() {
        Route::get('get_lga/{state_id}', 'AjaxController@get_lga')->name('get_lga');
        Route::get('get_class_sections/{class_id}', 'AjaxController@get_class_sections')->name('get_class_sections');
        Route::get('get_class_subjects/{class_id}', 'AjaxController@get_class_subjects')->name('get_class_subjects');
    });

});

/************************ SUPER ADMIN ****************************/
Route::group(['namespace' => 'SuperAdmin','middleware' => 'super_admin', 'prefix' => 'super_admin'], function(){

    Route::get('/settings', 'SettingController@index')->name('settings');
    Route::put('/settings', 'SettingController@update')->name('settings.update');

    // Branch Management Routes
    Route::resource('branches', 'BranchManagementController')->except(['create', 'store', 'destroy']);
    Route::get('/branches/{id}/details', 'BranchManagementController@branchDetails')->name('branches.details');

    // Benefits Routes
    Route::get('/benefits/dashboard', 'BenefitsController@dashboard')->name('benefits.dashboard');
    Route::get('/benefits/cost-analysis', 'BenefitsController@costAnalysis')->name('benefits.cost-analysis');
    Route::get('/benefits/performance-comparison', 'BenefitsController@performanceComparison')->name('benefits.performance-comparison');

});

/************************ PARENT ****************************/
Route::group(['namespace' => 'MyParent','middleware' => 'my_parent',], function(){

    Route::get('/my_children', 'MyController@children')->name('my_children');

});
// Super Admin Branch Management Routes
Route::group(['prefix' => 'super_admin', 'middleware' => ['auth', 'role:super_admin']], function() {
    Route::group(['prefix' => 'branch_management', 'as' => 'super_admin.branch_management.'], function() {
        Route::get('/dashboard', 'SuperAdmin\BranchManagementController@dashboard')->name('dashboard');
        Route::get('/reports', 'SuperAdmin\BranchManagementController@crossBranchReport')->name('reports');
        Route::get('/branch/{id}/users', 'SuperAdmin\BranchManagementController@branchUsers')->name('users');
        Route::post('/transfer-user', 'SuperAdmin\BranchManagementController@transferUser')->name('transfer_user');
    });
});

// Branch Settings Routes
Route::group(['prefix' => 'branch/settings', 'middleware' => ['auth', 'branch.access'], 'as' => 'branch.settings.'], function() {
    Route::get('/', 'SupportTeam\BranchSettingsController@index')->name('index');
    Route::post('/branding', 'SupportTeam\BranchSettingsController@updateBranding')->name('branding');
    Route::post('/academic', 'SupportTeam\BranchSettingsController@updateAcademicSettings')->name('academic');
    Route::post('/fees', 'SupportTeam\BranchSettingsController@updateFeeStructure')->name('fees');
    Route::get('/theme', 'SupportTeam\BranchSettingsController@getBranchTheme')->name('theme');
});

// Branch-specific Login Routes
Route::group(['prefix' => 'branch'], function() {
    Route::get('/select', 'Auth\BranchLoginController@showBranchSelection')->name('branch.selection');
    Route::get('/{slug}/login', 'Auth\BranchLoginController@showBranchLogin')->name('branch.login');
    Route::post('/{slug}/login', 'Auth\BranchLoginController@branchLogin')->name('branch.login.submit');
    Route::post('/{slug}/register', 'Auth\BranchLoginController@branchRegister')->name('branch.register');
});
