<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'address',
        'academic_year',
        'phone',
        'email',
        'established_date',
        'principal_name',
        'is_active'
    ];

    protected $casts = [
        'established_date' => 'date',
        'is_active' => 'boolean',
        'custom_settings' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($branch) {
            $branch->slug = Str::slug($branch->name);
        });

        static::updating(function ($branch) {
            if ($branch->isDirty('name')) {
                $branch->slug = Str::slug($branch->name);
            }
        });
    }

    // Relationships
    public function students()
    {
        return $this->hasMany(StudentRecord::class);
    }

    public function staff()
    {
        return $this->hasMany(StaffRecord::class);
    }

    public function classes()
    {
        return $this->hasMany(MyClass::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function dorms()
    {
        return $this->hasMany(Dorm::class);
    }

    public function timeTables()
    {
        return $this->hasMany(TimeTable::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function settings()
    {
        return $this->hasMany(Setting::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getSetting($type, $default = null)
    {
        $setting = $this->settings()->where('type', $type)->first();
        return $setting ? $setting->description : $default;
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/logos/' . $this->logo) : asset('global_assets/images/logo_dark.png');
    }

    public function isActive()
    {
        return $this->is_active;
    }
}
