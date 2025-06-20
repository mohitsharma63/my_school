<?php

namespace App\Models;

use Eloquent;

class Payment extends Eloquent
{
    protected $fillable = [
        'branch_id', 'title', 'amount', 'my_class_id', 'description', 'year', 'ref_no'
    ];

    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
