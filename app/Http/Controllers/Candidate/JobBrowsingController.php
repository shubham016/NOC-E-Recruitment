<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobBrowsingController extends Controller
{
    /**
     * Display all available jobs
     */
    public function index(Request $request)
    {
        // Determine if the logged-in candidate is a NOC employee
        $isNocEmployee = false;
        $nocCandidate = Auth::guard('candidate')->user();
        if ($nocCandidate) {
            $isNocEmployee = strtolower((string) $nocCandidate->noc_employee) === 'yes';
        }

        $query = JobPosting::where('status', 'active')
            ->where(function ($q) {
                $q->where(function ($inner) {
                    $inner->whereNotNull('double_dastur_date')
                          ->where('double_dastur_date', '>=', now());
                })->orWhere(function ($inner) {
                    $inner->whereNull('double_dastur_date')
                          ->where('deadline', '>=', now());
                });
            });

        // Non-NOC candidates: hide internal-only vacancies (no open, no inclusive component)
        if (!$isNocEmployee) {
            $query->where(function ($q) {
                $q->where('has_open', true)
                  ->orWhere('has_inclusive', true)
                  ->orWhere('category', 'open')
                  ->orWhere('category', 'inclusive');
            });
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('advertisement_no', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        // Filter by position
        if ($request->filled('position_level')) {
            $query->where('position', $request->position_level);
        }

        // Primary: position+level+advertisement_no so same role groups together in order; Secondary: user-chosen sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy('position', 'asc')
              ->orderBy('level', 'asc')
              ->orderBy('advertisement_no', 'asc')
              ->orderBy($sortBy, $sortOrder);

        // Get jobs with application count
        $jobs = $query->withCount('applications')->paginate(12)->withQueryString();

        // Get filter options
        $departments = JobPosting::where('status', 'active')
            ->distinct()
            ->pluck('department')
            ->filter();

        $locations = JobPosting::where('status', 'active')
            ->distinct()
            ->pluck('location')
            ->filter();

        $positionLevels = JobPosting::where('status', 'active')
            ->distinct()
            ->pluck('position')
            ->filter();

        // Check which jobs candidate has already applied for
        $appliedJobIds = [];

        $candidate = Auth::guard('candidate')->user();
        if ($candidate) {
            $appliedJobIds = DB::table('application_form')
                ->where('citizenship_number', $candidate->citizenship_number)
                ->whereNotNull('job_posting_id')
                ->pluck('job_posting_id')
                ->toArray();
        }

        return view('candidate.jobs.index', compact(
            'jobs',
            'departments',
            'locations',
            'positionLevels',
            'appliedJobIds'
        ));
    }

    /**
     * Display job details
     */
    public function show($id)
    {
        // Determine NOC status for this candidate
        $isNocEmployee = false;
        $showCandidate = Auth::guard('candidate')->user();
        if ($showCandidate) {
            $isNocEmployee = strtolower((string) $showCandidate->noc_employee) === 'yes';
        }

        $jobQuery = JobPosting::where('status', 'active')
            ->where(function ($q) {
                $q->where(function ($inner) {
                    $inner->whereNotNull('double_dastur_date')
                          ->where('double_dastur_date', '>=', now());
                })->orWhere(function ($inner) {
                    $inner->whereNull('double_dastur_date')
                          ->where('deadline', '>=', now());
                });
            });

        if (!$isNocEmployee) {
            $jobQuery->where(function ($q) {
                $q->where('has_open', true)
                  ->orWhere('has_inclusive', true)
                  ->orWhere('category', 'open')
                  ->orWhere('category', 'inclusive');
            });
        }

        $job = $jobQuery->withCount('applications')->findOrFail($id);

        // Check if candidate already applied
        $hasApplied = false;
        $application = null;

        $candidate = Auth::guard('candidate')->user();
        if ($candidate) {
            $hasApplied = DB::table('application_form')
                ->where('citizenship_number', $candidate->citizenship_number)
                ->where('job_posting_id', $id)
                ->exists();

            if ($hasApplied) {
                $application = DB::table('application_form')
                    ->where('citizenship_number', $candidate->citizenship_number)
                    ->where('job_posting_id', $id)
                    ->first();
            }
        }

        $groupJobs = JobPosting::where('status', 'active')
            ->where(function ($q) {
                $q->where(function ($inner) {
                    $inner->whereNotNull('double_dastur_date')
                          ->where('double_dastur_date', '>=', now());
                })->orWhere(function ($inner) {
                    $inner->whereNull('double_dastur_date')
                          ->where('deadline', '>=', now());
                });
            })
            ->where('position', $job->position)
            ->where('level', $job->level)
            ->where('service_group', $job->service_group)
            ->orderBy('advertisement_no', 'asc')
            ->get();

        if ($groupJobs->isEmpty()) {
            $groupJobs = collect([$job]);
        }

        return view('candidate.jobs.show', compact('job', 'hasApplied', 'application', 'groupJobs'));
    }
}