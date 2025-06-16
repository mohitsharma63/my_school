<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchIdToTables extends Migration
{
    public function up()
    {
        // Add branch_id to student_records
        Schema::table('student_records', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Add branch_id to staff_records
        Schema::table('staff_records', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Add branch_id to my_classes
        Schema::table('my_classes', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Add branch_id to sections
        Schema::table('sections', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Add branch_id to subjects
        Schema::table('subjects', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Add branch_id to exams
        Schema::table('exams', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Add branch_id to exam_records
        Schema::table('exam_records', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Add branch_id to marks
        Schema::table('marks', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Add branch_id to payments
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Add branch_id to payment_records
        Schema::table('payment_records', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Add branch_id to dorms
        Schema::table('dorms', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Add branch_id to time_tables
        Schema::table('time_tables', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Add branch_id to time_table_records
        Schema::table('time_table_records', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Add branch_id to grades
        Schema::table('grades', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Add branch_id to books
        Schema::table('books', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Add branch_id to book_requests
        Schema::table('book_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Add branch_id to receipts
        Schema::table('receipts', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Add branch_id to promotions
        Schema::table('promotions', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    public function down()
    {
        $tables = [
            'student_records', 'staff_records', 'my_classes', 'sections', 'subjects',
            'exams', 'exam_records', 'marks', 'payments', 'payment_records',
            'dorms', 'time_tables', 'time_table_records', 'grades', 'books',
            'book_requests', 'receipts', 'promotions'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['branch_id']);
                $table->dropColumn('branch_id');
            });
        }
    }
}
