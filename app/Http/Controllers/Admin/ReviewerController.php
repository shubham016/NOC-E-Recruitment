<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reviewer;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ReviewerController extends Controller
{
    /**
     * Display a listing of Reviewers
     */
    public function index(Request $request)
    {
        $query = Reviewer::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
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

        $reviewers = $query->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total' => Reviewer::count(),
            'active' => Reviewer::where('status', 'active')->count(),
            'inactive' => Reviewer::where('status', 'inactive')->count(),
            'total_reviews' => Application::whereNotNull('reviewer_id')->count(),
        ];

        // Get unique departments for filter
        $departments = Reviewer::distinct()->pluck('department')->filter();

        return view('admin.reviewers.index', compact('reviewers', 'stats', 'departments'));
    }

    /**
     * Show the form for creating a new Reviewer
     */
    public function create()
    {
        return view('admin.reviewers.create');
    }

    /**
     * Store a newly created Reviewer
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:reviewers'],
            'phone' => ['nullable', 'string', 'max:20'],
            'department' => ['required', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'status' => ['required', 'in:active,inactive'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('reviewer-photos', 'public');
            $validated['photo'] = $photoPath;
        }

        Reviewer::create($validated);

        return redirect()->route('admin.reviewers.index')
            ->with('success', 'Reviewer created successfully.');
    }

    /**
     * Display the specified Reviewer
     */
    public function show($id)
    {
        $reviewer = Reviewer::findOrFail($id);

        // Get review statistics
        $stats = [
            'total_assigned' => Application::where('reviewer_id', $reviewer->id)->count(),
            'pending_review' => Application::where('reviewer_id', $reviewer->id)
                ->where('status', 'under_review')->count(),
            'reviewed' => Application::where('reviewer_id', $reviewer->id)
                ->whereIn('status', ['reviewed', 'shortlisted', 'rejected'])->count(),
            'shortlisted' => Application::where('reviewer_id', $reviewer->id)
                ->where('status', 'shortlisted')->count(),
        ];

        // Get recent applications assigned to this reviewer
        $recentApplications = Application::where('reviewer_id', $reviewer->id)
            ->with(['jobPosting', 'candidate'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.reviewers.show', compact('reviewer', 'stats', 'recentApplications'));
    }

    /**
     * Show the form for editing the specified Reviewer
     */
    public function edit($id)
    {
        $reviewer = Reviewer::findOrFail($id);
        return view('admin.reviewers.edit', compact('reviewer'));
    }

    /**
     * Update the specified Reviewer
     */
    public function update(Request $request, $id)
    {
        $reviewer = Reviewer::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:reviewers,email,' . $reviewer->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'department' => ['required', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
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
            if ($reviewer->photo) {
                Storage::disk('public')->delete($reviewer->photo);
            }

            $photoPath = $request->file('photo')->store('reviewer-photos', 'public');
            $validated['photo'] = $photoPath;
        }

        $reviewer->update($validated);

        return redirect()->route('admin.reviewers.index')
            ->with('success', 'Reviewer updated successfully.');
    }

    /**
     * Remove the specified Reviewer
     */
    public function destroy($id)
    {
        $reviewer = Reviewer::findOrFail($id);

        // Check if reviewer has assigned applications
        if (Application::where('reviewer_id', $reviewer->id)->exists()) {
            return back()->with('error', 'Cannot delete reviewer with assigned applications. Please reassign applications first.');
        }

        // Delete photo if exists
        if ($reviewer->photo) {
            Storage::disk('public')->delete($reviewer->photo);
        }

        $reviewer->delete();

        return redirect()->route('admin.reviewers.index')
            ->with('success', 'Reviewer deleted successfully.');
    }

    /**
     * Toggle the status of Reviewer
     */
    public function toggleStatus($id)
    {
        $reviewer = Reviewer::findOrFail($id);
        $newStatus = $reviewer->status === 'active' ? 'inactive' : 'active';
        $reviewer->update(['status' => $newStatus]);

        $statusText = $newStatus === 'active' ? 'activated' : 'deactivated';
        return back()->with('success', "Reviewer {$statusText} successfully.");
    }

    /**
     * Reset password for Reviewer
     */
    public function resetPassword(Request $request, $id)
    {
        $reviewer = Reviewer::findOrFail($id);

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $reviewer->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password reset successfully.');
    }
}