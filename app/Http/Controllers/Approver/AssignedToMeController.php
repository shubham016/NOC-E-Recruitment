<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPosting;
use App\Models\ApplicationForm;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class AssignedToMeController extends Controller
{
    /**
     * Display applications assigned to this approver
     */
    public function index(Request $request)
    {
        $approver = Auth::guard('approver')->user();

        // Get job postings for filter
        $jobs = JobPosting::select('id', 'title')->where('status', 'active')->get();

        // Build query
        $query = ApplicationForm::with(['candidate', 'jobPosting'])
            ->where('status', '!=', 'draft');

        // If approver is assigned to specific job posting
        if ($approver->job_posting_id) {
            $query->where('job_posting_id', $approver->job_posting_id);
        }

        // Apply filters
        if ($request->filled('job_posting_id')) {
            $query->where('job_posting_id', $request->job_posting_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('candidate', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Paginate results
        $applications = $query->latest()->paginate(10);

        return view('approver.assignedtome', compact('jobs', 'applications'));
    }

    /**
     * Export applications to CSV
     */
    public function exportCsv(Request $request)
    {
        $approver = Auth::guard('approver')->user();
        $ids = $request->ids ?? [];

        $query = ApplicationForm::with(['candidate', 'jobPosting']);

        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        } elseif ($approver->job_posting_id) {
            $query->where('job_posting_id', $approver->job_posting_id);
        }

        $applications = $query->get();

        $filename = 'applications_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'Application ID',
                'Candidate Name',
                'Email',
                'Phone',
                'Job Title',
                'Status',
                'Applied Date'
            ]);

            // Add data rows
            foreach ($applications as $application) {
                fputcsv($file, [
                    $application->id,
                    $application->candidate->name ?? 'N/A',
                    $application->candidate->email ?? 'N/A',
                    $application->candidate->phone ?? 'N/A',
                    $application->jobPosting->title ?? 'N/A',
                    $application->status,
                    $application->created_at->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export applications to PDF
     */
    public function exportPdf(Request $request)
    {
        $approver = Auth::guard('approver')->user();
        $ids = $request->ids ?? [];

        $query = ApplicationForm::with(['candidate', 'jobPosting']);

        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        } elseif ($approver->job_posting_id) {
            $query->where('job_posting_id', $approver->job_posting_id);
        }

        $applications = $query->get();

        $pdf = Pdf::loadView('approver.pdf.applications', compact('applications', 'approver'));

        return $pdf->download('applications_' . date('Y-m-d_His') . '.pdf');
    }

    /**
     * Show application detail
     */
    public function show($id)
    {
        $approver = Auth::guard('approver')->user();

        $application = ApplicationForm::with(['candidate', 'jobPosting', 'reviewer'])
            ->findOrFail($id);

        // Check if approver has access to this application
        if ($approver->job_posting_id && $application->job_posting_id != $approver->job_posting_id) {
            abort(403, 'Unauthorized access to this application.');
        }

        return view('approver.show', compact('application'));
    }

    /**
     * Update application status (approve/reject)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $approver = Auth::guard('approver')->user();

        $application = ApplicationForm::findOrFail($id);

        // Check if approver has access to this application
        if ($approver->job_posting_id && $application->job_posting_id != $approver->job_posting_id) {
            abort(403, 'Unauthorized access to this application.');
        }

        $application->update([
            'status' => $request->status,
            'approver_remarks' => $request->remarks,
            'approved_at' => $request->status === 'approved' ? now() : null,
            'approved_by' => $request->status === 'approved' ? $approver->id : null,
        ]);

        return redirect()->back()->with('success', 'Application status updated successfully.');
    }
}
