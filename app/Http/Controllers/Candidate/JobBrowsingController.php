<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class JobBrowsingController extends Controller
{
    /**
     * Resolve the logged-in candidate from session.
     */
    private function getSessionCandidate()
    {
        if (!Session::has('candidate_logged_in')) {
            return null;
        }
        return DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();
    }

    /**
     * Apply NOC visibility filter.
     * NOC employees see all categories.
     * General public see only Open / Open+Inclusive.
     */
    private function applyNocFilter($query, $candidate)
    {
        $isNoc = $candidate && $candidate->noc_employee === 'yes';

        if (!$isNoc) {
            $query->whereNotIn('category', ['internal', 'internal_appraisal']);
        }

        return $query;
    }

    /**
     * Display all available jobs (filtered by NOC status).
     */
    public function index(Request $request)
    {
        $candidate = $this->getSessionCandidate();

        $query = JobPosting::where('status', 'active')
            ->where('deadline', '>=', now());

        $this->applyNocFilter($query, $candidate);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('advertisement_no', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        if ($request->filled('position_level')) {
            $query->where('position_level', $request->position_level);
        }

        $sortBy    = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $jobs = $query->withCount('applications')->paginate(12)->withQueryString();

        // Filter options — respect the same NOC filter
        $baseQuery = JobPosting::where('status', 'active');
        $this->applyNocFilter($baseQuery, $candidate);

        $departments    = (clone $baseQuery)->distinct()->pluck('department')->filter();
        $locations      = (clone $baseQuery)->distinct()->pluck('location')->filter();
        $positionLevels = (clone $baseQuery)->distinct()->pluck('position_level')->filter();

        $appliedJobIds = [];
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
     * Display job details (block Internal/Appraisal from non-NOC candidates).
     */
    public function show($id)
    {
        $candidate = $this->getSessionCandidate();
        $isNoc     = $candidate && $candidate->noc_employee === 'yes';

        $query = JobPosting::where('status', 'active')
            ->where('deadline', '>=', now());

        if (!$isNoc) {
            $query->whereNotIn('category', ['internal', 'internal_appraisal']);
        }

        $job = $query->withCount('applications')->findOrFail($id);

        $hasApplied  = false;
        $application = null;

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

        return view('candidate.jobs.show', compact('job', 'hasApplied', 'application'));
    }
}
