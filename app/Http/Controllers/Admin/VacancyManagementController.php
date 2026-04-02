<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\JobPosting;
use App\Models\Candidate;
use App\Models\Reviewer;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VacancyManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = JobPosting::query()->with('postedBy')->withCount('applications');

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

        return view('admin.jobs.index', compact('jobs', 'stats'));
    }

    public function create()
    {
        return view('admin.jobs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'advertisement_no' => 'required|string|max:50|unique:job_postings,advertisement_no',
            'title' => 'required|string|max:255',
            'position_level' => 'required|string|max:100',
            'service_group' => 'required|string|max:100',
            'category' => 'required|in:open,inclusive,internal,internal_appraisal',
            'internal_type' => 'nullable|string',
            'inclusive_type' => 'required_if:category,inclusive|nullable|string',
            'number_of_posts' => 'required|integer|min:1',
            'minimum_qualification' => 'required|string',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'location' => 'required|string|max:100',
            'application_fee' => 'required|numeric|min:0',
            'double_dastur_fee' => 'required|numeric|min:0',
            'deadline' => 'required|date|after:today',
            'deadline_bs' => 'nullable|string',
            'double_dastur_date' => 'nullable|date',
            'double_dastur_bs' => 'nullable|string',
            'status' => 'required|in:draft,active,closed',
        ]);

        if (Auth::guard('admin')->check()) {
            $validated['posted_by'] = Auth::guard('admin')->id();
        } elseif (Auth::guard('hr_administrator')->check()) {
            $validated['posted_by'] = Auth::guard('hr_administrator')->id();
        } else {
            $validated['posted_by'] = Auth::guard('admin')->id();
        }

        // Keep department in sync with service_group
        $validated['department'] = $validated['service_group'];

        JobPosting::create($validated);

        return redirect()
            ->route('admin.jobs.index')
            ->with('success', 'Vacancy saved as draft successfully! Change status to "Active" to publish it.');
    }

    public function show($id)
    {
        $job = JobPosting::with(['applications.reviewer', 'postedBy'])
            ->withCount('applications')
            ->findOrFail($id);

        $applicationStats = [
            'total' => $job->applications->count(),
            'assigned' => $job->applications->where('status', 'assigned')->count(),
            'reviewed' => $job->applications->where('status', 'reviewed')->count(),
            'edit_access' => $job->applications->where('status', 'edit')->count(),
            'approved' => $job->applications->where('status', 'approved')->count(),
            'rejected' => $job->applications->where('status', 'rejected')->count(),
        ];

        // Get 5 latest application activities for timeline
        $recentActivities = $job->applications()
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.jobs.show', compact('job', 'applicationStats', 'recentActivities'));
    }

    public function edit($id)
    {
        $job = JobPosting::findOrFail($id);
        return view('admin.jobs.edit', compact('job'));
    }

    public function update(Request $request, $id)
    {
        $job = JobPosting::findOrFail($id);

        $validated = $request->validate([
            'advertisement_no' => 'required|string|max:50|unique:job_postings,advertisement_no,' . $id,
            'title' => 'required|string|max:255',
            'position_level' => 'required|string|max:100',
            'service_group' => 'required|string|max:100',
            'category' => 'required|in:open,inclusive,internal,internal_appraisal',
            'internal_type' => 'nullable|string',
            'inclusive_type' => 'required_if:category,inclusive|nullable|string',
            'number_of_posts' => 'required|integer|min:1',
            'minimum_qualification' => 'required|string',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'location' => 'required|string|max:100',
            'application_fee' => 'required|numeric|min:0',
            'double_dastur_fee' => 'required|numeric|min:0',
            'deadline' => 'required|date',
            'deadline_bs' => 'nullable|string',
            'double_dastur_date' => 'nullable|date',
            'double_dastur_bs' => 'nullable|string',
            'status' => 'required|in:draft,active,closed',
        ]);

        $validated['department'] = $validated['service_group'];
        $job->update($validated);

        return redirect()
            ->route('admin.jobs.index')
            ->with('success', 'Job updated successfully!');
    }

    public function destroy($id)
    {
        $job = JobPosting::findOrFail($id);

        // Only allow deletion if vacancy is in Draft status
        if ($job->status !== 'draft') {
            return redirect()
                ->route('admin.jobs.index')
                ->with('error', 'Cannot delete published vacancy. Only Draft vacancies can be deleted. Please change status to "Closed" instead.');
        }

        // Additional check: Prevent deletion if any applications exist (even for drafts)
        if ($job->applications()->count() > 0) {
            return redirect()
                ->route('admin.jobs.index')
                ->with('error', 'Cannot delete vacancy with existing applications. Please change status to "Closed" instead.');
        }

        $job->delete();

        return redirect()
            ->route('admin.jobs.index')
            ->with('success', 'Draft vacancy deleted successfully!');
    }

    public function duplicate($id)
    {
        $job = JobPosting::findOrFail($id);

        $newJob = $job->replicate();
        $newJob->title = $job->title . ' (Copy)';
        $newJob->advertisement_no = $job->advertisement_no . '-COPY';
        $newJob->status = 'draft';

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

    public function previewPDF(Request $request)
    {
        $lang = $request->get('lang', 'en');

        $jobs = JobPosting::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = \PDF::loadView('admin.jobs.pdf.' . $lang, [
            'jobs' => $jobs,
            'generatedDate' => now()->format('Y-m-d H:i:s')
        ]);

        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf->stream('job-list-preview.pdf');
    }

    public function downloadPDF(Request $request)
    {
        $lang = $request->get('lang', 'en');

        $jobs = JobPosting::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = \PDF::loadView('admin.jobs.pdf.' . $lang, [
            'jobs' => $jobs,
            'generatedDate' => now()->format('Y-m-d H:i:s')
        ]);

        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        $filename = $lang === 'ne'
            ? 'job-list-nepali-' . now()->format('Y-m-d') . '.pdf'
            : 'job-list-english-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    public function downloadExcel()
    {
        $jobs = JobPosting::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'job-list-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($jobs) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'S.N.',
                'Advertisement No.',
                'Position/Level',
                'Department',
                'Category',
                'Posts',
                'Deadline',
                'Status',
                'Posted Date'
            ]);

            foreach ($jobs as $index => $job) {
                fputcsv($file, [
                    $index + 1,
                    $job->advertisement_no,
                    $job->position_level,
                    $job->department,
                    ucfirst($job->category) . ($job->inclusive_type ? " ({$job->inclusive_type})" : ''),
                    $job->number_of_posts,
                    $job->deadline->format('Y-m-d'),
                    ucfirst($job->status),
                    $job->created_at->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
