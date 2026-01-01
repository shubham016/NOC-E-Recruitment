<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\HRAdministrator;
use App\Models\JobPosting;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class HRAdministratorController extends Controller
{
    /**
     * Display a listing of HR Administrators
     */
    public function index(Request $request)
    {
        $query = HRAdministrator::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $administrators = $query->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total' => HRAdministrator::count(),
            'active' => HRAdministrator::where('status', 'active')->count(),
            'inactive' => HRAdministrator::where('status', 'inactive')->count(),
        ];

        return view('admin.hr-administrators.index', compact('administrators', 'stats'));
    }

    /**
     * Show the form for creating a new HR Administrator
     */
    public function create()
    {
        return view('admin.hr-administrators.create');
    }

    /**
     * Store a newly created HR Administrator
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:hr_administrators'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()],
            'status' => ['required', 'in:active,inactive'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('hr-administrator-photos', 'public');
            $validated['photo'] = $photoPath;
        }

        HRAdministrator::create($validated);

        return redirect()->route('admin.hr-administrators.index')
            ->with('success', 'HR Administrator created successfully.');
    }

    /**
     * Display the specified HR Administrator
     */
    public function show($id)
    {
        $hrAdministrator = HRAdministrator::findOrFail($id);

        // Load job postings statistics (using 'posted_by' foreign key)
        $stats = [
            'total_jobs_posted' => JobPosting::where('posted_by', $hrAdministrator->id)->count(),
            'active_jobs' => JobPosting::where('posted_by', $hrAdministrator->id)->where('status', 'active')->count(),
            'closed_jobs' => JobPosting::where('posted_by', $hrAdministrator->id)->where('status', 'closed')->count(),
            'total_applications' => Application::whereHas('jobPosting', function ($q) use ($hrAdministrator) {
                $q->where('posted_by', $hrAdministrator->id);
            })->count(),
        ];

        $recentJobs = JobPosting::where('posted_by', $hrAdministrator->id)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.hr-administrators.show', compact('hrAdministrator', 'stats', 'recentJobs'));
    }

    /**
     * Show the form for editing the specified HR Administrator
     */
    public function edit($id)
    {
        $hrAdministrator = HRAdministrator::findOrFail($id);

        return view('admin.hr-administrators.edit', compact('hrAdministrator'));
    }

    /**
     * Update the specified HR Administrator
     */
    public function update(Request $request, $id)
    {
        $hrAdministrator = HRAdministrator::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:hr_administrators,email,' . $hrAdministrator->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', Password::min(8)->letters()->numbers()],
            'status' => ['required', 'in:active,inactive'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($hrAdministrator->photo) {
                Storage::disk('public')->delete($hrAdministrator->photo);
            }

            $photoPath = $request->file('photo')->store('hr-administrator-photos', 'public');
            $validated['photo'] = $photoPath;
        }

        $hrAdministrator->update($validated);

        return redirect()->route('admin.hr-administrators.index')
            ->with('success', 'HR Administrator updated successfully.');
    }

    /**
     * Remove the specified HR Administrator
     */
    public function destroy($id)
    {
        $hrAdministrator = HRAdministrator::findOrFail($id);

        // Check if HR admin has job postings (using 'posted_by' foreign key)
        if (JobPosting::where('posted_by', $hrAdministrator->id)->exists()) {
            return back()->with('error', 'Cannot delete administrator with existing job postings. Please reassign or delete their jobs first.');
        }

        // Delete photo if exists
        if ($hrAdministrator->photo) {
            Storage::disk('public')->delete($hrAdministrator->photo);
        }

        $hrAdministrator->delete();

        return redirect()->route('admin.hr-administrators.index')
            ->with('success', 'HR Administrator deleted successfully.');
    }

    /**
     * Toggle the status of HR Administrator
     */
    public function toggleStatus($id)
    {
        $hrAdministrator = HRAdministrator::findOrFail($id);

        $newStatus = $hrAdministrator->status === 'active' ? 'inactive' : 'active';
        $hrAdministrator->update(['status' => $newStatus]);

        $statusText = $newStatus === 'active' ? 'activated' : 'deactivated';
        return back()->with('success', "HR Administrator {$statusText} successfully.");
    }

    /**
     * Reset password for HR Administrator
     */
    public function resetPassword(Request $request, $id)
    {
        $hrAdministrator = HRAdministrator::findOrFail($id);

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $hrAdministrator->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password reset successfully.');
    }
}