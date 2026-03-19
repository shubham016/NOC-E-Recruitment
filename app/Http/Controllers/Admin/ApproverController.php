<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Approver;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ApproverController extends Controller
{
    /**
     * Display a listing of Approvers
     */
    public function index(Request $request)
    {
        $query = Approver::with('jobPosting');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Department filter
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $approvers = $query->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total' => Approver::count(),
            'active' => Approver::where('status', 'active')->count(),
            'inactive' => Approver::where('status', 'inactive')->count(),
        ];

        // Get unique departments for filter
        $departments = Approver::distinct()->pluck('department')->filter();

        return view('admin.approvers.index', compact('approvers', 'stats', 'departments'));
    }

    /**
     * Show the form for creating a new Approver
     */
    public function create()
    {
        $jobs = JobPosting::where('status', 'active')->get();
        return view('admin.approvers.create', compact('jobs'));
    }

    /**
     * Store a newly created Approver
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'string', 'max:50', 'unique:approvers'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:approvers'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'department' => ['required', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'job_posting_id' => ['nullable', 'exists:job_postings,id'],
            'password' => ['required', Password::min(8)],
            'photo' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('approvers/photos', 'public');
        }

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        Approver::create($validated);

        return redirect()->route('admin.approvers.index')
            ->with('success', 'Approver created successfully.');
    }

    /**
     * Display the specified Approver
     */
    public function show($id)
    {
        $approver = Approver::with('jobPosting')->findOrFail($id);
        return view('admin.approvers.show', compact('approver'));
    }

    /**
     * Show the form for editing the specified Approver
     */
    public function edit($id)
    {
        $approver = Approver::findOrFail($id);
        $jobs = JobPosting::where('status', 'active')->get();
        return view('admin.approvers.edit', compact('approver', 'jobs'));
    }

    /**
     * Update the specified Approver
     */
    public function update(Request $request, $id)
    {
        $approver = Approver::findOrFail($id);

        $validated = $request->validate([
            'employee_id' => ['required', 'string', 'max:50', 'unique:approvers,employee_id,' . $id],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:approvers,email,' . $id],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'department' => ['required', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'job_posting_id' => ['nullable', 'exists:job_postings,id'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($approver->photo) {
                Storage::disk('public')->delete($approver->photo);
            }
            $validated['photo'] = $request->file('photo')->store('approvers/photos', 'public');
        }

        $approver->update($validated);

        return redirect()->route('admin.approvers.index')
            ->with('success', 'Approver updated successfully.');
    }

    /**
     * Remove the specified Approver
     */
    public function destroy($id)
    {
        $approver = Approver::findOrFail($id);

        // Delete photo if exists
        if ($approver->photo) {
            Storage::disk('public')->delete($approver->photo);
        }

        $approver->delete();

        return redirect()->route('admin.approvers.index')
            ->with('success', 'Approver deleted successfully.');
    }

    /**
     * Reset Approver password
     */
    public function resetPassword(Request $request, $id)
    {
        $validated = $request->validate([
            'password' => ['required', Password::min(8), 'confirmed'],
        ]);

        $approver = Approver::findOrFail($id);
        $approver->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->back()
            ->with('success', 'Password reset successfully.');
    }

    /**
     * Toggle Approver status
     */
    public function toggleStatus($id)
    {
        $approver = Approver::findOrFail($id);
        $approver->update([
            'status' => $approver->status === 'active' ? 'inactive' : 'active',
        ]);

        return redirect()->back()
            ->with('success', 'Approver status updated successfully.');
    }
}
