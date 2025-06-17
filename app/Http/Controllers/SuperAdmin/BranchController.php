<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::withCount(['students', 'staff', 'users'])->paginate(15);
        return view('pages.super_admin.branches.index', compact('branches'));
    }

    public function show($id)
    {
        $branch = Branch::with(['students', 'staff', 'users'])->findOrFail($id);
        return view('pages.super_admin.branches.show', compact('branch'));
    }

    public function create()
    {
        return view('pages.super_admin.branches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:6',
        ]);

        // Create branch
        $branch = Branch::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'is_active' => true,
        ]);

        // Create branch admin user
        $admin = User::create([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'user_type' => 'admin',
            'branch_id' => $branch->id,
            'email_verified_at' => now()
        ]);

        // Assign admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $admin->roles()->attach($adminRole->id);
        }

        return redirect()->route('super_admin.branches.index')
            ->with('flash_message', 'Branch created successfully with admin user.');
    }

    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        return view('pages.super_admin.branches.edit', compact('branch'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        $branch = Branch::findOrFail($id);
        $branch->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('super_admin.branches.index')
            ->with('flash_message', 'Branch updated successfully.');
    }

    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);

        // Check if branch has users or data
        if ($branch->users()->count() > 0 || $branch->students()->count() > 0) {
            return redirect()->back()
                ->with('flash_danger', 'Cannot delete branch that has users or students.');
        }

        $branch->delete();

        return redirect()->route('super_admin.branches.index')
            ->with('flash_message', 'Branch deleted successfully.');
    }
}
