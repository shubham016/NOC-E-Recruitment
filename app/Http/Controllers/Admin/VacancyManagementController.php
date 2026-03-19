<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\Vacancy;
use App\Models\Candidate;
use App\Models\Reviewer;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VacancyManagementController extends Controller
{
    /**
     * Display a listing of vacancies
     */
    public function index(Request $request)
    {
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

        return view('admin.vacancies.index', compact('vacancies', 'stats'));
    }

    /**
     * Show the form for creating a new vacancy
     */
    public function create()
    {
        return view('admin.vacancies.create');
    }

    /**
     * Store a newly created vacancy
     */
    public function store(Request $request)
    {
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

        $vacancy = Vacancy::create($validated);

        return redirect()
            ->route('admin.vacancies.index')
            ->with('success', 'Vacancy posted successfully!');
    }

    /**
     * Display the specified vacancy
     */
    public function show($id)
    {
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
            'selected' => $vacancy->applicationForms->where('status', 'selected')->count(),
        ];

        return view('admin.vacancies.show', compact('vacancy', 'applicationStats'));
    }

    /**
     * Show the form for editing the specified vacancy
     */
    public function edit($id)
    {
        $vacancy = Vacancy::findOrFail($id);
        return view('admin.vacancies.edit', compact('vacancy'));
    }

    /**
     * Update the specified vacancy
     */
    public function update(Request $request, $id)
    {
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
            'status' => 'required|in:draft,active,closed',
        ]);

        $vacancy->update($validated);

        return redirect()
            ->route('admin.vacancies.index')
            ->with('success', 'Vacancy updated successfully!');
    }

    /**
     * Remove the specified vacancy
     */
    public function destroy($id)
    {
        $vacancy = Vacancy::findOrFail($id);

        // Check if vacancy has applications
        if ($vacancy->applicationForms()->count() > 0) {
            return redirect()
                ->route('admin.vacancies.index')
                ->with('error', 'Cannot delete vacancy with existing applications. Please close it instead.');
        }

        $vacancy->delete();

        return redirect()
            ->route('admin.vacancies.index')
            ->with('success', 'Vacancy deleted successfully!');
    }

    /**
     * Duplicate a vacancy
     */
    public function duplicate($id)
    {
        $vacancy = Vacancy::findOrFail($id);

        $newVacancy = $vacancy->replicate();
        $newVacancy->title = $vacancy->title . ' (Copy)';
        $newVacancy->advertisement_no = $vacancy->advertisement_no . '-COPY';
        $newVacancy->status = 'draft';

        // Set posted_by for duplicated vacancy
        if (Auth::guard('admin')->check()) {
            $newVacancy->posted_by = Auth::guard('admin')->id();
        } elseif (Auth::guard('hr_administrator')->check()) {
            $newVacancy->posted_by = Auth::guard('hr_administrator')->id();
        }

        $newVacancy->deadline = now()->addDays(30);
        $newVacancy->save();

        return redirect()
            ->route('admin.vacancies.edit', $newVacancy->id)
            ->with('success', 'Vacancy duplicated successfully! Please review and update.');
    }

    /**
     * Change vacancy status
     */
    public function changeStatus(Request $request, $id)
    {
        $vacancy = Vacancy::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:draft,active,closed',
        ]);

        $vacancy->update(['status' => $validated['status']]);

        return redirect()
            ->back()
            ->with('success', 'Vacancy status updated successfully!');
    }

    /**
     * Preview PDF in browser (English or Nepali)
     */
    public function previewPDF(Request $request)
    {
        $lang = $request->get('lang', 'en');

        // Get all active vacancies
        $vacancies = Vacancy::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = \PDF::loadView('admin.vacancies.pdf.' . $lang, [
            'vacancies' => $vacancies,
            'generatedDate' => now()->format('Y-m-d H:i:s')
        ]);

        // Enable Unicode support for Nepali
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf->stream('vacancy-list-preview.pdf');
    }

    /**
     * Download all vacancies as PDF (English or Nepali)
     */
    public function downloadPDF(Request $request)
    {
        $lang = $request->get('lang', 'en'); // default to English

        // Get all active vacancies
        $vacancies = Vacancy::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = \PDF::loadView('admin.vacancies.pdf.' . $lang, [
            'vacancies' => $vacancies,
            'generatedDate' => now()->format('Y-m-d H:i:s')
        ]);

        // Enable Unicode support for Nepali
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        $filename = $lang === 'ne'
            ? 'vacancy-list-nepali-' . now()->format('Y-m-d') . '.pdf'
            : 'vacancy-list-english-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Download all vacancies as Excel
     */
    public function downloadExcel()
    {
        $vacancies = Vacancy::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'vacancy-list-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($vacancies) {
            $file = fopen('php://output', 'w');

            // CSV Headers
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

            // Data rows
            foreach ($vacancies as $index => $vacancy) {
                fputcsv($file, [
                    $index + 1,
                    $vacancy->advertisement_no,
                    $vacancy->position_level,
                    $vacancy->department,
                    ucfirst($vacancy->category) . ($vacancy->inclusive_type ? " ({$vacancy->inclusive_type})" : ''),
                    $vacancy->number_of_posts,
                    $vacancy->deadline->format('Y-m-d'),
                    ucfirst($vacancy->status),
                    $vacancy->created_at->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
