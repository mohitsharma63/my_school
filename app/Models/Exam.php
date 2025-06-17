<?php

namespace App\Models;

use Eloquent;

class Exam extends Eloquent
{
    protected $fillable = [
        'branch_id','name', 'term', 'year'];




    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
