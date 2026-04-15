<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class VacancyBrowsingController extends Controller
{
    private function getSessionCandidate()
    {
        if (!Session::has('candidate_logged_in')) {
            return null;
        }
        return DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();
    }

    private function applyNocFilter($query, $candidate)
    {
        $isNoc = $candidate && $candidate->noc_employee === 'yes';

        if (!$isNoc) {
            $query->whereNotIn('category', ['internal', 'internal_appraisal']);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $candidate = $this->getSessionCandidate();

        $query = Vacancy::where('status', 'active')
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

        $vacancies = $query->withCount('applications')->paginate(12)->withQueryString();

        $baseQuery = Vacancy::where('status', 'active');
        $this->applyNocFilter($baseQuery, $candidate);

        $departments    = (clone $baseQuery)->distinct()->pluck('department')->filter();
        $locations      = (clone $baseQuery)->distinct()->pluck('location')->filter();
        $positionLevels = (clone $baseQuery)->distinct()->pluck('position_level')->filter();

        $appliedVacancyIds = [];
        if ($candidate) {
            $appliedVacancyIds = DB::table('application_form')
                ->where('citizenship_number', $candidate->citizenship_number)
                ->whereNotNull('vacancy_id')
                ->pluck('vacancy_id')
                ->toArray();
        }

        return view('candidate.vacancies.index', compact(
            'vacancies',
            'departments',
            'locations',
            'positionLevels',
            'appliedVacancyIds'
        ));
    }

    public function show($id)
    {
        $candidate = $this->getSessionCandidate();
        $isNoc     = $candidate && $candidate->noc_employee === 'yes';

        $query = Vacancy::where('status', 'active')
            ->where('deadline', '>=', now());

        if (!$isNoc) {
            $query->whereNotIn('category', ['internal', 'internal_appraisal']);
        }

        $vacancy = $query->withCount('applications')->findOrFail($id);

        $hasApplied  = false;
        $application = null;

        if ($candidate) {
            $hasApplied = DB::table('application_form')
                ->where('citizenship_number', $candidate->citizenship_number)
                ->where('vacancy_id', $id)
                ->exists();

            if ($hasApplied) {
                $application = DB::table('application_form')
                    ->where('citizenship_number', $candidate->citizenship_number)
                    ->where('vacancy_id', $id)
                    ->first();
            }
        }

        return view('candidate.vacancies.show', compact('vacancy', 'hasApplied', 'application'));
    }
}
