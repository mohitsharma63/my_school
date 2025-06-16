<?php

namespace App\Models;

use Eloquent;

class Setting extends Eloquent
{
    protected $fillable = ['branch_id', 'type', 'description'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public static function getBranchSetting($branchId, $type, $default = null)
    {
        $setting = self::where('branch_id', $branchId)->where('type', $type)->first();
        return $setting ? $setting->description : $default;
    }

    public static function setBranchSetting($branchId, $type, $value)
    {
        return self::updateOrCreate(
            ['branch_id' => $branchId, 'type' => $type],
            ['description' => $value]
        );
    }
}
