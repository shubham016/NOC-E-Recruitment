<?php

namespace App\Http\Controllers\HRAdministrator;

use App\Http\Controllers\Controller;
use App\Models\Reviewer;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class HRReviewerController extends Controller
{
    /**
     * Get the authenticated HR Administrator
     */
    private function getAuthUser()
    {
        return Auth::guard('hr_administrator')->user();
    }

    /**
     * Display a listing of reviewers
     */
    public function index(Request $request)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $query = Reviewer::withCount(['applications as total_reviewed' => function ($q) {
            $q->whereIn('status', ['reviewed', 'shortlisted', 'rejected']);
        }]);

        // Search
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

        $reviewers = $query->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total' => Reviewer::count(),
            'active' => Reviewer::where('status', 'active')->count(),
            'inactive' => Reviewer::where('status', 'inactive')->count(),
        ];

        return view('hr-administrator.reviewers.index', compact('reviewers', 'stats', 'hrAdmin'));
    }

    /**
     * Show the form for creating a new reviewer
     */
    public function create()
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        return view('hr-administrator.reviewers.create', compact('hrAdmin'));
    }

    /**
     * Store a newly created reviewer
     */
    public function store(Request $request)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:reviewers,email',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'status' => 'required|in:active,inactive',
            'specialization' => 'nullable|string|max:255',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        Reviewer::create($validated);

        return redirect()
            ->route('hr-administrator.reviewers.index')
            ->with('success', 'Reviewer created successfully!');
    }

    /**
     * Display the specified reviewer
     */
    public function show($id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $reviewer = Reviewer::withCount([
            'applications as total_assigned',
            'applications as total_reviewed' => function ($q) {
                $q->whereIn('status', ['reviewed', 'shortlisted', 'rejected']);
            },
            'applications as pending' => function ($q) {
                $q->where('status', 'under_review');
            }
        ])->findOrFail($id);

        // Get recent applications assigned to this reviewer
        $recentApplications = Application::with(['candidate', 'jobPosting'])
            ->where('reviewer_id', $id)
            ->latest()
            ->take(10)
            ->get();

        return view('hr-administrator.reviewers.show', compact('reviewer', 'recentApplications', 'hrAdmin'));
    }

    /**
     * Show the form for editing the specified reviewer
     */
    public function edit($id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $reviewer = Reviewer::findOrFail($id);

        return view('hr-administrator.reviewers.edit', compact('reviewer', 'hrAdmin'));
    }

    /**
     * Update the specified reviewer
     */
    public function update(Request $request, $id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $reviewer = Reviewer::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:reviewers,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'password' => ['nullable', 'confirmed', Password::min(8)->letters()->numbers()],
            'status' => 'required|in:active,inactive',
            'specialization' => 'nullable|string|max:255',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $reviewer->update($validated);

        return redirect()
            ->route('hr-administrator.reviewers.index')
            ->with('success', 'Reviewer updated successfully!');
    }

    /**
     * Remove the specified reviewer
     */
    public function destroy($id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $reviewer = Reviewer::findOrFail($id);

        // Check if reviewer has assigned applications
        if ($reviewer->applications()->where('status', 'under_review')->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete reviewer with pending applications. Please reassign them first.');
        }

        $reviewer->delete();

        return redirect()
            ->route('hr-administrator.reviewers.index')
            ->with('success', 'Reviewer deleted successfully!');
    }

    /**
     * Toggle reviewer status
     */
    public function toggleStatus($id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $reviewer = Reviewer::findOrFail($id);

        $newStatus = $reviewer->status === 'active' ? 'inactive' : 'active';
        $reviewer->update(['status' => $newStatus]);

        $statusText = $newStatus === 'active' ? 'activated' : 'deactivated';

        return redirect()
            ->back()
            ->with('success', "Reviewer {$statusText} successfully!");
    }

    /**
     * Reset reviewer password
     */
    public function resetPassword(Request $request, $id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $reviewer = Reviewer::findOrFail($id);

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $reviewer->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Reviewer password reset successfully!');
    }
}