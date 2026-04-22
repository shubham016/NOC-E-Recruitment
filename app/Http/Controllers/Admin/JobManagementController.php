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

        // Handle export
        if ($request->filled('export') && $request->filled('ids')) {
            $ids = explode(',', $request->ids);
            $jobs = JobPosting::whereIn('id', $ids)->get();
            $type = $request->export;

            if ($type === 'csv') {
                return $this->exportToExcel($jobs);
            } elseif ($type === 'pdf') {
                return $this->exportToPdf($jobs);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('advertisement_no', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
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

        // Sort by notice_no ascending (groups same notices together), oldest first within each group
        $sortBy = $request->get('sort_by', 'notice_no');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder)->orderBy('created_at', 'asc');

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
    public function create(Request $request)
    {
        // Pre-fill notice_no when adding another advertisement under the same notice
        $prefillNoticeNo = $request->query('notice_no');
        return view('admin.jobs.create', compact('prefillNoticeNo'));
    }

    /**
     * Store a newly created job
     */
    public function store(Request $request)
    {
        // DEBUG: Log EVERYTHING in the request
        \Log::info('===== JOB STORE REQUEST =====');
        \Log::info('ALL REQUEST DATA:', $request->all());
        \Log::info('has_open value:', ['value' => $request->input('has_open'), 'type' => gettype($request->input('has_open'))]);
        \Log::info('has_inclusive value:', ['value' => $request->input('has_inclusive'), 'type' => gettype($request->input('has_inclusive'))]);
        \Log::info('notice_no value:', ['value' => $request->input('notice_no'), 'type' => gettype($request->input('notice_no'))]);
        \Log::info('===== END REQUEST =====');

        // For Open category: Double Dastur Date and Fee are required
        $isOpenCategory = $request->input('has_open') == '1'
            && $request->input('is_internal_appraisal') != '1'
            && $request->input('has_internal') != '1';

        if ($isOpenCategory) {
            if (empty($request->double_dastur_date)) {
                return redirect()->back()
                    ->withErrors(['double_dastur_date' => 'Double Dastur Date is required for Open category.'])
                    ->withInput();
            }
            if (empty($request->double_dastur_fee) || floatval($request->double_dastur_fee) <= 0) {
                return redirect()->back()
                    ->withErrors(['double_dastur_fee' => 'Double Dastur Fee is required for Open category and must be greater than 0.'])
                    ->withInput();
            }
        }

        $validated = $request->validate([
            'notice_no' => 'required|string|max:50',
            'advertisement_no' => 'required|string|max:50|unique:job_postings,advertisement_no',
            'title' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'level' => 'required|integer|min:1|max:99',
            'service_group' => 'required|string|max:100',
            'category' => 'required|in:open,inclusive,internal,internal_appraisal',
            'internal_type' => 'nullable|string',
            'inclusive_type' => 'nullable|string',
            'has_open' => 'nullable|boolean',
            'has_inclusive' => 'nullable|boolean',
            'has_internal' => 'nullable|boolean',
            'has_internal_open' => 'nullable|boolean',
            'has_internal_inclusive' => 'nullable|boolean',
            'is_internal_appraisal' => 'nullable|boolean',
            'inclusive_types' => 'nullable|array',
            'inclusive_types.*' => 'string|in:Women,A.J,Madhesi,Janajati,Apanga,Dalit,Pichadiyeko Chetra',
            'internal_inclusive_types' => 'nullable|array',
            'internal_inclusive_types.*' => 'string|in:Women,A.J,Madhesi,Janajati,Apanga,Dalit,Pichadiyeko Chetra',
            'open_posts' => 'nullable|integer|min:0',
            'inclusive_posts' => 'nullable|integer|min:0',
            'number_of_posts' => 'required|integer|min:1',
            'minimum_qualification' => 'required|string',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'location' => 'required|string|max:100',
            'application_fee' => 'required|numeric|min:0',
            'deadline' => 'required|date|after:today',
            'deadline_bs' => 'nullable|string',
            'double_dastur_date' => 'nullable|date',
            'double_dastur_bs' => 'nullable|string',
            'double_dastur_fee' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,active,closed',
        ]);

        // Convert checkbox values to boolean
        $validated['has_open'] = $request->has('has_open') && $request->has_open == '1';
        $validated['has_inclusive'] = $request->has('has_inclusive') && $request->has_inclusive == '1';
        $validated['has_internal'] = $request->has('has_internal') && $request->has_internal == '1';
        $validated['has_internal_open'] = $request->has('has_internal_open') && $request->has_internal_open == '1';
        $validated['has_internal_inclusive'] = $request->has('has_internal_inclusive') && $request->has_internal_inclusive == '1';

        // Handle internal appraisal (exclusive category)
        if ($request->has('is_internal_appraisal') && $request->is_internal_appraisal == '1') {
            $validated['category'] = 'internal_appraisal';
            $validated['has_open'] = false;
            $validated['has_inclusive'] = false;
            $validated['has_internal'] = false;
            $validated['has_internal_open'] = false;
            $validated['has_internal_inclusive'] = false;
        }

        // Validate exactly one main category is selected
        $categoriesSelected = 0;
        if ($validated['has_open']) $categoriesSelected++;
        if ($validated['has_internal']) $categoriesSelected++;
        if ($validated['category'] === 'internal_appraisal') $categoriesSelected++;

        if ($categoriesSelected === 0) {
            return redirect()->back()
                ->withErrors(['categories' => 'Please select one main category (Open, Internal, or Internal Appraisal).'])
                ->withInput();
        }

        if ($categoriesSelected > 1) {
            return redirect()->back()
                ->withErrors(['categories' => 'Please select only ONE main category. Open, Internal, and Internal Appraisal are mutually exclusive.'])
                ->withInput();
        }

        // Set double_dastur_fee to 0 for Internal and Internal Appraisal (not applicable)
        if ($validated['has_internal'] || $validated['category'] === 'internal_appraisal') {
            $validated['double_dastur_fee'] = 0;
            $validated['double_dastur_date'] = null;
            $validated['double_dastur_bs'] = null;
        }

        // Handle inclusive types array - convert to JSON for storage
        if ($request->has('inclusive_types') && is_array($request->inclusive_types)) {
            // Store first one in inclusive_type for backward compatibility
            $validated['inclusive_type'] = $request->inclusive_types[0] ?? null;
        }

        // Set department from service_group
        $validated['department'] = $validated['service_group'];

        // Set posted_by to current admin
        $validated['posted_by'] = Auth::guard('admin')->id();

        // CRITICAL DEBUG: Dump validated data before save
        \Log::info('ABOUT TO CREATE JOB:', $validated);
        \Log::info('CRITICAL FIELDS:', [
            'notice_no' => $validated['notice_no'] ?? 'NOT SET',
            'has_open' => $validated['has_open'] ?? 'NOT SET',
            'has_inclusive' => $validated['has_inclusive'] ?? 'NOT SET',
        ]);

        $job = JobPosting::create($validated);

        return redirect()
            ->route('admin.jobs.index')
            ->with('success', 'Vacancy posted successfully! Candidates can now apply under the selected categories.');
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
        // DEBUG: Log EVERYTHING in the request
        \Log::info('===== JOB UPDATE REQUEST =====');
        \Log::info('Job ID:', ['id' => $id]);
        \Log::info('ALL REQUEST DATA:', $request->all());
        \Log::info('has_open value:', ['value' => $request->input('has_open'), 'type' => gettype($request->input('has_open'))]);
        \Log::info('has_inclusive value:', ['value' => $request->input('has_inclusive'), 'type' => gettype($request->input('has_inclusive'))]);
        \Log::info('notice_no value:', ['value' => $request->input('notice_no'), 'type' => gettype($request->input('notice_no'))]);
        \Log::info('===== END REQUEST =====');

        $job = JobPosting::findOrFail($id);

        // For Open category: Double Dastur Date and Fee are required
        $isOpenCategory = $request->input('has_open') == '1'
            && $request->input('is_internal_appraisal') != '1'
            && $request->input('has_internal') != '1';

        if ($isOpenCategory) {
            if (empty($request->double_dastur_date)) {
                return redirect()->back()
                    ->withErrors(['double_dastur_date' => 'Double Dastur Date is required for Open category.'])
                    ->withInput();
            }
            if (empty($request->double_dastur_fee) || floatval($request->double_dastur_fee) <= 0) {
                return redirect()->back()
                    ->withErrors(['double_dastur_fee' => 'Double Dastur Fee is required for Open category and must be greater than 0.'])
                    ->withInput();
            }
        }

        $validated = $request->validate([
            'notice_no' => 'required|string|max:50',
            'advertisement_no' => 'required|string|max:50|unique:job_postings,advertisement_no,' . $id,
            'title' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'level' => 'required|integer|min:1|max:99',
            'service_group' => 'required|string|max:100',
            'category' => 'required|in:open,inclusive,internal,internal_appraisal',
            'internal_type' => 'nullable|string',
            'inclusive_type' => 'nullable|string',
            'has_open' => 'nullable|boolean',
            'has_inclusive' => 'nullable|boolean',
            'has_internal' => 'nullable|boolean',
            'has_internal_open' => 'nullable|boolean',
            'has_internal_inclusive' => 'nullable|boolean',
            'is_internal_appraisal' => 'nullable|boolean',
            'inclusive_types' => 'nullable|array',
            'inclusive_types.*' => 'string|in:Women,A.J,Madhesi,Janajati,Apanga,Dalit,Pichadiyeko Chetra',
            'internal_inclusive_types' => 'nullable|array',
            'internal_inclusive_types.*' => 'string|in:Women,A.J,Madhesi,Janajati,Apanga,Dalit,Pichadiyeko Chetra',
            'open_posts' => 'nullable|integer|min:0',
            'inclusive_posts' => 'nullable|integer|min:0',
            'number_of_posts' => 'required|integer|min:1',
            'minimum_qualification' => 'required|string',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'location' => 'required|string|max:100',
            'application_fee' => 'required|numeric|min:0',
            'deadline' => 'required|date',
            'deadline_bs' => 'nullable|string',
            'double_dastur_date' => 'nullable|date',
            'double_dastur_bs' => 'nullable|string',
            'double_dastur_fee' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,active,closed',
        ]);

        // Convert checkbox values to boolean
        $validated['has_open'] = $request->has('has_open') && $request->has_open == '1';
        $validated['has_inclusive'] = $request->has('has_inclusive') && $request->has_inclusive == '1';
        $validated['has_internal'] = $request->has('has_internal') && $request->has_internal == '1';
        $validated['has_internal_open'] = $request->has('has_internal_open') && $request->has_internal_open == '1';
        $validated['has_internal_inclusive'] = $request->has('has_internal_inclusive') && $request->has_internal_inclusive == '1';

        // Handle internal appraisal (exclusive category)
        if ($request->has('is_internal_appraisal') && $request->is_internal_appraisal == '1') {
            $validated['category'] = 'internal_appraisal';
            $validated['has_open'] = false;
            $validated['has_inclusive'] = false;
            $validated['has_internal'] = false;
            $validated['has_internal_open'] = false;
            $validated['has_internal_inclusive'] = false;
        }

        // Validate exactly one main category is selected
        $categoriesSelected = 0;
        if ($validated['has_open']) $categoriesSelected++;
        if ($validated['has_internal']) $categoriesSelected++;
        if ($validated['category'] === 'internal_appraisal') $categoriesSelected++;

        if ($categoriesSelected === 0) {
            return redirect()->back()
                ->withErrors(['categories' => 'Please select one main category (Open, Internal, or Internal Appraisal).'])
                ->withInput();
        }

        if ($categoriesSelected > 1) {
            return redirect()->back()
                ->withErrors(['categories' => 'Please select only ONE main category. Open, Internal, and Internal Appraisal are mutually exclusive.'])
                ->withInput();
        }

        // Handle inclusive types array
        if ($request->has('inclusive_types') && is_array($request->inclusive_types)) {
            $validated['inclusive_type'] = $request->inclusive_types[0] ?? null;
        }

        // Set department from service_group
        $validated['department'] = $validated['service_group'];

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
        $newJob->posted_by = Auth::guard('admin')->id();
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

    /**
     * Export vacancies to Excel
     */
    private function exportToExcel($jobs)
    {
        $filename = 'vacancies_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($jobs) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($file, [
                'S.N',
                'Advertisement No.',
                'Position',
                'Service/Group',
                'Category',
                'Type',
                'Demand',
                'Minimum Qualification',
                'Applications',
                'Application Fee',
                'Double Dastur Fee',
                'Deadline',
                'Status',
                'Posted On'
            ]);

            // Data rows
            foreach ($jobs as $index => $job) {
                // Format category
                $category = ucfirst($job->category);
                if ($job->category == 'internal_appraisal') {
                    $category = 'Internal Appraisal';
                } elseif ($job->category == 'internal') {
                    if ($job->internal_type == 'open') {
                        $category = 'Internal/Open';
                    } elseif ($job->internal_type == 'inclusive') {
                        $category = 'Internal/Inclusive';
                    }
                } elseif ($job->category == 'inclusive' && $job->inclusive_type) {
                    $category = 'Inclusive/' . ucfirst($job->inclusive_type);
                }

                fputcsv($file, [
                    $index + 1,
                    $job->advertisement_no,
                    $job->position_level,
                    $job->service_group ?: $job->department,
                    $category,
                    ucfirst($job->category),
                    $job->number_of_posts,
                    $job->minimum_qualification,
                    $job->applications_count ?? 0,
                    'NPR ' . number_format($job->application_fee, 2),
                    'NPR ' . number_format($job->double_dastur_fee, 2),
                    $job->deadline ? $job->deadline->format('Y-m-d') : '-',
                    ucfirst($job->status),
                    $job->created_at->format('Y-m-d')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export vacancies to PDF
     */
    private function exportToPdf($jobs)
    {
        $pdf = \PDF::loadView('admin.jobs.pdf.export', compact('jobs'));
        return $pdf->download('vacancies_' . date('Y-m-d_His') . '.pdf');
    }
}