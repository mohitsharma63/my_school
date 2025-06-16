<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::orderBy('name')->get();
        return view('pages.support_team.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('pages.support_team.branches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'academic_year' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'principal_name' => 'nullable|string'
        ]);

        Branch::create($request->all());

        return redirect()->route('branches.index')->with('flash_message', 'Branch created successfully');
    }

    public function show(Branch $branch)
    {
        return view('pages.support_team.branches.show', compact('branch'));
    }

    public function edit(Branch $branch)
    {
        return view('pages.support_team.branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'academic_year' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'principal_name' => 'nullable|string'
        ]);

        $branch->update($request->all());

        return redirect()->route('branches.index')->with('flash_message', 'Branch updated successfully');
    }

    public function destroy(Branch $branch)
    {
        if (Branch::count() <= 1) {
            return redirect()->back()->with('pop_error', 'Cannot delete the last branch');
        }

        $branch->delete();
        return redirect()->route('branches.index')->with('flash_message', 'Branch deleted successfully');
    }

    public function switch(Request $request)
    {
        $branchId = $request->branch_id;
        $branch = Branch::findOrFail($branchId);

        session(['current_branch_id' => $branchId]);

        return redirect()->back()->with('flash_message', 'Switched to ' . $branch->name);
    }
}
