<?php

namespace App\Models;

use Eloquent;

class MyClass extends Eloquent
{
    protected $fillable = [
        'branch_id','name', 'class_type_id'];

    public function section()
    {
        return $this->hasMany(Section::class);
    }

    public function class_type()
    {
        return $this->belongsTo(ClassType::class);
    }

    public function student_record()
    {
        return $this->hasMany(StudentRecord::class);
    }
}



    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
