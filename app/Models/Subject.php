<?php

namespace App\Models;

use App\User;
use Eloquent;

class Subject extends Eloquent
{
    protected $fillable = [
        'branch_id', 'name', 'my_class_id', 'teacher_id', 'slug'
    ];

    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
