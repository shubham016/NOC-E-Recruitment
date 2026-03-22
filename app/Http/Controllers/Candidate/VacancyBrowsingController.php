<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class VacancyBrowsingController extends Controller
{
    /**
     * Display all available vacancies
     */
    public function index(Request $request)
    {
        $query = JobPosting::where('status', 'active')
            ->where('deadline', '>=', now());

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

        // Filter by position level
        if ($request->filled('position_level')) {
            $query->where('position_level', $request->position_level);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Get vacancies with application count
        $vacancies = $query->withCount('applications')->paginate(12)->withQueryString();

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
            ->pluck('position_level')
            ->filter();

        // Check which vacancies candidate has already applied for
        $appliedVacancyIds = [];

        if (Session::has('candidate_logged_in')) {
            $candidate = DB::table('candidate_registration')
                ->where('id', Session::get('candidate_id'))
                ->first();

            if ($candidate) {
                $appliedVacancyIds = DB::table('application_form')
                    ->where('citizenship_number', $candidate->citizenship_number)
                    ->whereNotNull('vacancy_id')
                    ->pluck('vacancy_id')
                    ->toArray();
            }
        }

        return view('candidate.vacancies.index', compact(
            'vacancies',
            'departments',
            'locations',
            'positionLevels',
            'appliedVacancyIds'
        ));
    }

    /**
     * Display vacancy details
     */
    public function show($id)
    {
        $vacancy = JobPosting::where('status', 'active')
            ->where('deadline', '>=', now())
            ->withCount('applications')
            ->findOrFail($id);

        // Check if candidate already applied
        $hasApplied = false;
        $application = null;

        if (Session::has('candidate_logged_in')) {
            $candidate = DB::table('candidate_registration')
                ->where('id', Session::get('candidate_id'))
                ->first();

            if ($candidate) {
                // Check if candidate already applied using citizenship_number and vacancy_id
                $hasApplied = DB::table('application_form')
                    ->where('citizenship_number', $candidate->citizenship_number)
                    ->where('vacancy_id', $id)
                    ->exists();

                // Get candidate's application if exists
                if ($hasApplied) {
                    $application = DB::table('application_form')
                        ->where('citizenship_number', $candidate->citizenship_number)
                        ->where('vacancy_id', $id)
                        ->first();
                }
            }
        }

        return view('candidate.vacancies.show', compact('vacancy', 'hasApplied', 'application'));
    }
}
