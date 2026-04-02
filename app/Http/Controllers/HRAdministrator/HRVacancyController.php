<?php

namespace App\Http\Controllers\HRAdministrator;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\JobPosting;
use App\Models\Reviewer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HRVacancyController extends Controller
{
    private function getAuthUser()
    {
        return Auth::guard('hr_administrator')->user();
    }

    public function index(Request $request)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $query = JobPosting::query()->with('postedBy')->withCount('applicationForms');

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

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $jobs = $query->paginate(10)->withQueryString();

        $stats = [
            'total' => JobPosting::count(),
            'active' => JobPosting::where('status', 'active')->count(),
            'closed' => JobPosting::where('status', 'closed')->count(),
            'draft' => JobPosting::where('status', 'draft')->count(),
        ];

        return view('hr-administrator.vacancies.index', compact('jobs', 'stats', 'hrAdmin'));
    }

    public function create()
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        return view('hr-administrator.vacancies.create', compact('hrAdmin'));
    }

    public function store(Request $request)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $validated = $request->validate([
            'advertisement_no' => 'required|string|max:50|unique:job_postings,advertisement_no',
            'title' => 'required|string|max:255',
            'position_level' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'category' => 'required|in:open,inclusive,internal,internal_appraisal',
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

        $validated['posted_by'] = $hrAdmin->id;

        JobPosting::create($validated);

        return redirect()
            ->route('hr-administrator.vacancies.index')
            ->with('success', 'Vacancy saved as draft successfully! Change status to "Active" to publish it.');
    }

    public function show($id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $job = JobPosting::with(['applicationForms.reviewer', 'postedBy'])
            ->withCount('applicationForms')
            ->findOrFail($id);

        $applicationStats = [
            'total' => $job->applicationForms->count(),
            'pending' => $job->applicationForms->where('status', 'pending')->count(),
            'approved' => $job->applicationForms->where('status', 'approved')->count(),
            'shortlisted' => $job->applicationForms->where('status', 'shortlisted')->count(),
            'rejected' => $job->applicationForms->where('status', 'rejected')->count(),
        ];

        return view('hr-administrator.vacancies.show', compact('job', 'applicationStats', 'hrAdmin'));
    }

    public function edit($id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $job = JobPosting::findOrFail($id);

        return view('hr-administrator.vacancies.edit', compact('job', 'hrAdmin'));
    }

    public function update(Request $request, $id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $job = JobPosting::findOrFail($id);

        $validated = $request->validate([
            'advertisement_no' => 'required|string|max:50|unique:job_postings,advertisement_no,' . $id,
            'title' => 'required|string|max:255',
            'position_level' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'category' => 'required|in:open,inclusive,internal,internal_appraisal',
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

        $job->update($validated);

        return redirect()
            ->route('hr-administrator.vacancies.index')
            ->with('success', 'Job updated successfully!');
    }

    public function destroy($id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $job = JobPosting::findOrFail($id);

        // Only allow deletion if vacancy is in Draft status
        if ($job->status !== 'draft') {
            return redirect()
                ->route('hr-administrator.vacancies.index')
                ->with('error', 'Cannot delete published vacancy. Only Draft vacancies can be deleted. Please change status to "Closed" instead.');
        }

        // Additional check: Prevent deletion if any applications exist (even for drafts)
        if ($job->applicationForms()->count() > 0) {
            return redirect()
                ->route('hr-administrator.vacancies.index')
                ->with('error', 'Cannot delete vacancy with existing applications. Please change status to "Closed" instead.');
        }

        $job->delete();

        return redirect()
            ->route('hr-administrator.vacancies.index')
            ->with('success', 'Draft vacancy deleted successfully!');
    }

    public function duplicate($id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $job = JobPosting::findOrFail($id);

        $newJob = $job->replicate();
        $newJob->title = $job->title . ' (Copy)';
        $newJob->advertisement_no = $job->advertisement_no . '-COPY-' . time();
        $newJob->status = 'draft';
        $newJob->posted_by = $hrAdmin->id;
        $newJob->deadline = now()->addDays(30);
        $newJob->save();

        return redirect()
            ->route('hr-administrator.vacancies.edit', $newJob->id)
            ->with('success', 'Job duplicated successfully! Please review and update.');
    }

    public function changeStatus(Request $request, $id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $job = JobPosting::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:draft,active,closed',
        ]);

        $job->update(['status' => $validated['status']]);

        return redirect()
            ->back()
            ->with('success', 'Job status updated successfully!');
    }
}
