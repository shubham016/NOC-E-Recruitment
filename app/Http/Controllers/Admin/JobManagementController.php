<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobPosting;
use App\Models\Candidate;
use App\Models\Reviewer;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobManagementController extends Controller
{
    /**
     * Display a listing of jobs
     */
    public function index(Request $request)
    {
        $query = JobPosting::query()->with('postedBy')->withCount('applications');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('advertisement_no', 'like', "%{$search}%")
                    ->orWhere('position_level', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Job type filter
        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate
        $jobs = $query->paginate(10)->withQueryString();

        // Statistics
        $stats = [
            'total' => JobPosting::count(),
            'active' => JobPosting::where('status', 'active')->count(),
            'closed' => JobPosting::where('status', 'closed')->count(),
            'draft' => JobPosting::where('status', 'draft')->count(),
        ];

        return view('admin.jobs.index', compact('jobs', 'stats'));
    }

    /**
     * Show the form for creating a new job
     */
    public function create()
    {
        return view('admin.jobs.create');
    }

    /**
     * Store a newly created job
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'advertisement_no' => 'required|string|max:50|unique:job_postings,advertisement_no',
            'title' => 'required|string|max:255',
            'position_level' => 'required|string|max:100',
            'service_group' => 'required|string|max:100',
            'category' => 'required|in:open,inclusive',
            'inclusive_type' => 'required_if:category,inclusive|nullable|string',
            'number_of_posts' => 'required|integer|min:1',
            'minimum_qualification' => 'required|string',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'department' => 'required|string|max:100',
            'location' => 'required|string|max:100',
            'job_type' => 'required|in:permanent,temporary,contract',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'deadline' => 'required|date|after:today',
            'status' => 'required|in:draft,active,closed',
        ]);

        // Set posted_by based on who is logged in
        if (Auth::guard('admin')->check()) {
            $validated['posted_by'] = Auth::guard('admin')->id();
        } elseif (Auth::guard('hr_administrator')->check()) {
            $validated['posted_by'] = Auth::guard('hr_administrator')->id();
        } else {
            $validated['posted_by'] = Auth::guard('admin')->id();
        }

        $job = JobPosting::create($validated);

        return redirect()
            ->route('admin.jobs.index')
            ->with('success', 'Vacancy posted successfully!');
    }

    /**
     * Display the specified job
     */
    public function show($id)
    {
        $job = JobPosting::with(['applications.candidate', 'applications.reviewer', 'postedBy'])
            ->withCount('applications')
            ->findOrFail($id);

        // Application statistics
        $applicationStats = [
            'total' => $job->applications->count(),
            'pending' => $job->applications->where('status', 'pending')->count(),
            'under_review' => $job->applications->where('status', 'under_review')->count(),
            'shortlisted' => $job->applications->where('status', 'shortlisted')->count(),
            'rejected' => $job->applications->where('status', 'rejected')->count(),
        ];

        return view('admin.jobs.show', compact('job', 'applicationStats'));
    }

    /**
     * Show the form for editing the specified job
     */
    public function edit($id)
    {
        $job = JobPosting::findOrFail($id);
        return view('admin.jobs.edit', compact('job'));
    }

    /**
     * Update the specified job
     */
    public function update(Request $request, $id)
    {
        $job = JobPosting::findOrFail($id);

        $validated = $request->validate([
            'advertisement_no' => 'required|string|max:50|unique:job_postings,advertisement_no,' . $id,
            'title' => 'required|string|max:255',
            'position_level' => 'required|string|max:100',
            'service_group' => 'required|string|max:100',
            'category' => 'required|in:open,inclusive',
            'inclusive_type' => 'required_if:category,inclusive|nullable|string',
            'number_of_posts' => 'required|integer|min:1',
            'minimum_qualification' => 'required|string',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'department' => 'required|string|max:100',
            'location' => 'required|string|max:100',
            'job_type' => 'required|in:permanent,temporary,contract',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'deadline' => 'required|date',
            'status' => 'required|in:draft,active,closed',
        ]);

        $job->update($validated);

        return redirect()
            ->route('admin.jobs.index')
            ->with('success', 'Vacancy updated successfully!');
    }

    /**
     * Remove the specified job
     */
    public function destroy($id)
    {
        $job = JobPosting::findOrFail($id);

        // Check if job has applications
        if ($job->applications()->count() > 0) {
            return redirect()
                ->route('admin.jobs.index')
                ->with('error', 'Cannot delete vacancy with existing applications. Please close it instead.');
        }

        $job->delete();

        return redirect()
            ->route('admin.jobs.index')
            ->with('success', 'Vacancy deleted successfully!');
    }

    /**
     * Duplicate a job
     */
    public function duplicate($id)
    {
        $job = JobPosting::findOrFail($id);

        $newJob = $job->replicate();
        $newJob->title = $job->title . ' (Copy)';
        $newJob->advertisement_no = $job->advertisement_no . '-COPY';
        $newJob->status = 'draft';

        // Set posted_by for duplicated job
        if (Auth::guard('admin')->check()) {
            $newJob->posted_by = Auth::guard('admin')->id();
        } elseif (Auth::guard('hr_administrator')->check()) {
            $newJob->posted_by = Auth::guard('hr_administrator')->id();
        }

        $newJob->deadline = now()->addDays(30);
        $newJob->save();

        return redirect()
            ->route('admin.jobs.edit', $newJob->id)
            ->with('success', 'Vacancy duplicated successfully! Please review and update.');
    }

    /**
     * Change job status
     */
    public function changeStatus(Request $request, $id)
    {
        $job = JobPosting::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:draft,active,closed',
        ]);

        $job->update(['status' => $validated['status']]);

        return redirect()
            ->back()
            ->with('success', 'Vacancy status updated successfully!');
    }
}