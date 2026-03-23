<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vacancy;
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

        // If approver is not assigned to any vacancy, show empty results
        if (!$approver->vacancy_id) {
            $vacancies = collect();
            $applications = ApplicationForm::where('id', null)->paginate(10); // Empty collection
            return view('approver.assignedtome', compact('vacancies', 'applications'));
        }

        // Get vacancies for filter (only the assigned vacancy)
        $vacancies = Vacancy::select('id', 'title')
            ->where('id', $approver->vacancy_id)
            ->where('status', 'active')
            ->get();

        // Build query - Only show applications for assigned vacancy
        // and only those that are ready for approval (reviewed/shortlisted)
        $query = ApplicationForm::with(['candidate', 'vacancy'])
            ->where('vacancy_id', $approver->vacancy_id)
            ->whereIn('status', ['shortlisted', 'pending', 'approved', 'rejected']);

        // Apply filters
        if ($request->filled('vacancy_id')) {
            $query->where('vacancy_id', $request->vacancy_id);
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

        // Paginate results (oldest first - ascending order)
        $applications = $query->oldest()->paginate(10);

        return view('approver.assignedtome', compact('vacancies', 'applications'));
    }

    /**
     * Export applications to CSV
     */
    public function exportCsv(Request $request)
    {
        $approver = Auth::guard('approver')->user();
        $ids = $request->ids ?? [];

        $query = ApplicationForm::with(['candidate', 'vacancy']);

        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        } elseif ($approver->vacancy_id) {
            $query->where('vacancy_id', $approver->vacancy_id);
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
                'Vacancy Title',
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
                    $application->vacancy->title ?? 'N/A',
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

        $query = ApplicationForm::with(['candidate', 'vacancy']);

        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        } elseif ($approver->vacancy_id) {
            $query->where('vacancy_id', $approver->vacancy_id);
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

        $application = ApplicationForm::with(['candidate', 'vacancy', 'reviewer'])
            ->findOrFail($id);

        // Check if approver has access to this application
        if ($approver->vacancy_id && $application->vacancy_id != $approver->vacancy_id) {
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
        if ($approver->vacancy_id && $application->vacancy_id != $approver->vacancy_id) {
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
