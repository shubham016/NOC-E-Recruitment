<?php

namespace App\Http\Controllers\HRAdministrator;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\Vacancy;
use App\Models\Reviewer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HRVacancyController extends Controller
{
    /**
     * Get the authenticated HR Administrator
     */
    private function getAuthUser()
    {
        return Auth::guard('hr_administrator')->user();
    }

    /**
     * Display a listing of vacancies
     */
    public function index(Request $request)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $query = Vacancy::query()->with('postedBy')->withCount('applicationForms');

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
        $vacancies = $query->paginate(10)->withQueryString();

        // Statistics
        $stats = [
            'total' => Vacancy::count(),
            'active' => Vacancy::where('status', 'active')->count(),
            'closed' => Vacancy::where('status', 'closed')->count(),
            'draft' => Vacancy::where('status', 'draft')->count(),
        ];

        return view('hr-administrator.vacancies.index', compact('vacancies', 'stats', 'hrAdmin'));
    }

    /**
     * Show the form for creating a new vacancy
     */
    public function create()
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        return view('hr-administrator.vacancies.create', compact('hrAdmin'));
    }

    /**
     * Store a newly created vacancy
     */
    public function store(Request $request)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $validated = $request->validate([
            'advertisement_no' => 'required|string|max:50|unique:vacancies,advertisement_no',
            'title' => 'required|string|max:255',
            'position_level' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'category' => 'required|in:open,inclusive',
            'inclusive_type' => 'required_if:category,inclusive|nullable|string',
            'number_of_posts' => 'required|integer|min:1',
            'minimum_qualification' => 'required|string',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'location' => 'required|string|max:100',
            'job_type' => 'required|in:permanent,temporary,contract',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'deadline' => 'required|date|after:today',
            'deadline_bs' => 'nullable|string|max:20',
            'status' => 'required|in:draft,active,closed',
        ]);

        // Set posted_by to the HR Administrator's ID
        $validated['posted_by'] = $hrAdmin->id;

        $vacancy = Vacancy::create($validated);

        return redirect()
            ->route('hr-administrator.vacancies.index')
            ->with('success', 'Vacancy posted successfully!');
    }

    /**
     * Display the specified vacancy
     */
    public function show($id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $vacancy = Vacancy::with(['applicationForms.candidate', 'applicationForms.reviewer', 'postedBy'])
            ->withCount('applicationForms')
            ->findOrFail($id);

        // Application statistics
        $applicationStats = [
            'total' => $vacancy->applicationForms->count(),
            'pending' => $vacancy->applicationForms->where('status', 'pending')->count(),
            'approved' => $vacancy->applicationForms->where('status', 'approved')->count(),
            'shortlisted' => $vacancy->applicationForms->where('status', 'shortlisted')->count(),
            'rejected' => $vacancy->applicationForms->where('status', 'rejected')->count(),
        ];

        return view('admin.vacancies.show', compact('vacancy', 'applicationStats', 'hrAdmin'));
    }

    /**
     * Show the form for editing the specified vacancy
     */
    public function edit($id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $vacancy = Vacancy::findOrFail($id);

        return view('hr-administrator.vacancies.edit', compact('vacancy', 'hrAdmin'));
    }

    /**
     * Update the specified vacancy
     */
    public function update(Request $request, $id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $vacancy = Vacancy::findOrFail($id);

        $validated = $request->validate([
            'advertisement_no' => 'required|string|max:50|unique:vacancies,advertisement_no,' . $id,
            'title' => 'required|string|max:255',
            'position_level' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'category' => 'required|in:open,inclusive',
            'inclusive_type' => 'required_if:category,inclusive|nullable|string',
            'number_of_posts' => 'required|integer|min:1',
            'minimum_qualification' => 'required|string',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'location' => 'required|string|max:100',
            'job_type' => 'required|in:permanent,temporary,contract',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'deadline' => 'required|date',
            'deadline_bs' => 'nullable|string|max:20',
            'status' => 'required|in:draft,active,closed',
        ]);

        $vacancy->update($validated);

        return redirect()
            ->route('hr-administrator.vacancies.index')
            ->with('success', 'Vacancy updated successfully!');
    }

    /**
     * Remove the specified vacancy
     */
    public function destroy($id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $vacancy = Vacancy::findOrFail($id);

        // Check if vacancy has applications
        if ($vacancy->applicationForms()->count() > 0) {
            return redirect()
                ->route('hr-administrator.vacancies.index')
                ->with('error', 'Cannot delete vacancy with existing applications. Please close it instead.');
        }

        $vacancy->delete();

        return redirect()
            ->route('hr-administrator.vacancies.index')
            ->with('success', 'Vacancy deleted successfully!');
    }

    /**
     * Duplicate a vacancy
     */
    public function duplicate($id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $vacancy = Vacancy::findOrFail($id);

        $newVacancy = $vacancy->replicate();
        $newVacancy->title = $vacancy->title . ' (Copy)';
        $newVacancy->advertisement_no = $vacancy->advertisement_no . '-COPY-' . time();
        $newVacancy->status = 'draft';
        $newVacancy->posted_by = $hrAdmin->id;
        $newVacancy->deadline = now()->addDays(30);
        $newVacancy->save();

        return redirect()
            ->route('hr-administrator.vacancies.edit', $newVacancy->id)
            ->with('success', 'Vacancy duplicated successfully! Please review and update.');
    }

    /**
     * Change vacancy status
     */
    public function changeStatus(Request $request, $id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $vacancy = Vacancy::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:draft,active,closed',
        ]);

        $vacancy->update(['status' => $validated['status']]);

        return redirect()
            ->back()
            ->with('success', 'Vacancy status updated successfully!');
    }
}
