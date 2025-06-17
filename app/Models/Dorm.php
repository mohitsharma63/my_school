<?php

namespace App\Models;

use Eloquent;

class Dorm extends Eloquent
{
    protected $fillable = [
        'branch_id', 'name', 'description'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
