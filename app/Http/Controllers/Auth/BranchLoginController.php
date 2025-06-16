<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BranchLoginController extends Controller
{
    public function showBranchLogin($branchSlug)
    {
        $branch = Branch::where('slug', $branchSlug)->firstOrFail();

        if (!$branch->is_active) {
            abort(404, 'Branch not found or inactive');
        }

        return view('auth.branch_login', compact('branch'));
    }

    public function branchLogin(Request $request, $branchSlug)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $branch = Branch::where('slug', $branchSlug)->firstOrFail();

        if (!$branch->is_active) {
            return back()->withErrors(['email' => 'This branch is currently inactive.']);
        }

        $user = User::where('email', $request->email)
            ->where('branch_id', $branch->id)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials for this branch.']);
        }

        Auth::login($user);
        session(['current_branch_id' => $branch->id]);

        return redirect()->intended('/dashboard');
    }

    public function branchRegister(Request $request, $branchSlug)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => 'required|in:student,parent'
        ]);

        $branch = Branch::where('slug', $branchSlug)->firstOrFail();

        if (!$branch->is_active) {
            return back()->withErrors(['email' => 'This branch is currently inactive.']);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'branch_id' => $branch->id,
        ]);

        Auth::login($user);
        session(['current_branch_id' => $branch->id]);

        return redirect()->intended('/dashboard');
    }

    public function showBranchSelection()
    {
        $branches = Branch::where('is_active', true)->get();
        return view('auth.branch_selection', compact('branches'));
    }
}
