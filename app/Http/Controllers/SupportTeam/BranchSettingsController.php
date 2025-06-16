<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Setting;
use App\Traits\BranchFilterTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BranchSettingsController extends Controller
{
    use BranchFilterTrait;

    public function index()
    {
        $branch = Branch::findOrFail($this->getCurrentBranchId());
        $settings = Setting::where('branch_id', $branch->id)->get()->keyBy('type');

        return view('pages.support_team.branch_settings.index', compact('branch', 'settings'));
    }

    public function updateBranding(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|max:2048',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'theme' => 'nullable|in:default,dark,modern'
        ]);

        $branchId = $this->getCurrentBranchId();
        $branch = Branch::findOrFail($branchId);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($branch->logo && Storage::exists('public/logos/' . $branch->logo)) {
                Storage::delete('public/logos/' . $branch->logo);
            }

            $logoPath = $request->file('logo')->store('public/logos');
            $branch->update(['logo' => basename($logoPath)]);
        }

        // Update branding settings
        $this->updateSetting($branchId, 'primary_color', $request->primary_color);
        $this->updateSetting($branchId, 'secondary_color', $request->secondary_color);
        $this->updateSetting($branchId, 'theme', $request->theme ?? 'default');

        return redirect()->back()->with('flash_message', 'Branding updated successfully');
    }

    public function updateAcademicSettings(Request $request)
    {
        $request->validate([
            'academic_year' => 'required|string',
            'grading_system' => 'required|in:percentage,letter,gpa',
            'pass_mark' => 'required|numeric|min:0|max:100',
            'term_system' => 'required|in:2,3,4'
        ]);

        $branchId = $this->getCurrentBranchId();

        Branch::findOrFail($branchId)->update([
            'academic_year' => $request->academic_year
        ]);

        $this->updateSetting($branchId, 'grading_system', $request->grading_system);
        $this->updateSetting($branchId, 'pass_mark', $request->pass_mark);
        $this->updateSetting($branchId, 'term_system', $request->term_system);

        return redirect()->back()->with('flash_message', 'Academic settings updated successfully');
    }

    public function updateFeeStructure(Request $request)
    {
        $request->validate([
            'fee_structure' => 'required|array',
            'late_fee_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_enabled' => 'boolean'
        ]);

        $branchId = $this->getCurrentBranchId();

        $this->updateSetting($branchId, 'fee_structure', json_encode($request->fee_structure));
        $this->updateSetting($branchId, 'late_fee_percentage', $request->late_fee_percentage ?? 0);
        $this->updateSetting($branchId, 'discount_enabled', $request->discount_enabled ? '1' : '0');

        return redirect()->back()->with('flash_message', 'Fee structure updated successfully');
    }

    private function updateSetting($branchId, $type, $value)
    {
        Setting::updateOrCreate(
            ['branch_id' => $branchId, 'type' => $type],
            ['description' => $value]
        );
    }

    public function getBranchTheme()
    {
        $branchId = $this->getCurrentBranchId();
        $settings = Setting::where('branch_id', $branchId)
            ->whereIn('type', ['primary_color', 'secondary_color', 'theme'])
            ->get()
            ->keyBy('type');

        return response()->json([
            'primary_color' => $settings->get('primary_color')->description ?? '#007bff',
            'secondary_color' => $settings->get('secondary_color')->description ?? '#6c757d',
            'theme' => $settings->get('theme')->description ?? 'default'
        ]);
    }
}