<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\JobPosting;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignedToMeController extends Controller
{
    public function index(Request $request)
    {
        $approver = Auth::guard('approver')->user();

        $query = ApplicationForm::with(['jobPosting', 'reviewer'])
            ->where('approver_id', $approver->id)
            ->where('status', '!=', 'draft');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_english', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('jobPosting', function ($q2) use ($search) {
                        $q2->where('title', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Job filter
        if ($request->filled('job_id')) {
            $query->where('job_posting_id', $request->job_id);
        }

        $applications = $query->latest()->paginate(15)->withQueryString();

        $jobs = JobPosting::select('id', 'title')->orderBy('title')->get();

        return view('approver.assignedtome', compact('jobs', 'applications'));
    }

    public function show($id)
    {
        $approver = Auth::guard('approver')->user();

        $application = ApplicationForm::with(['jobPosting', 'reviewer', 'approver'])
            ->where('approver_id', $approver->id)
            ->findOrFail($id);

        return view('approver.show', compact('application'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'         => 'required|in:approved,rejected',
            'approver_notes' => 'required|string|max:1000',
        ]);

        $approver = Auth::guard('approver')->user();

        $application = ApplicationForm::where('approver_id', $approver->id)
            ->findOrFail($id);

        $application->update([
            'status'         => $request->status,
            'approver_notes' => $request->approver_notes,
            'approved_at'    => now(),
        ]);

        // Notify candidate
        $candidate = \App\Models\Candidate::where('email', $application->email)->first();

        if ($request->status == 'approved') {
            Notification::create([
                'user_id'      => $candidate?->id,
                'user_type'    => 'candidate',
                'type'         => 'application_approved',
                'title'        => 'Application Approved',
                'message'      => 'Congratulations! Your application for "' . ($application->vacancy->title ?? 'N/A') . '" has been approved by the approver.',
                'related_id'   => $application->id,
                'related_type' => 'application',
            ]);
        } elseif ($request->status == 'rejected') {
            $rejectionReason = $request->approver_notes ? ' Reason: ' . $request->approver_notes : '';
            Notification::create([
                'user_id'      => $candidate?->id,
                'user_type'    => 'candidate',
                'type'         => 'application_rejected',
                'title'        => 'Application Rejected',
                'message'      => 'Your application for "' . ($application->vacancy->title ?? 'N/A') . '" has been rejected by the approver.' . $rejectionReason,
                'related_id'   => $application->id,
                'related_type' => 'application',
            ]);
        }

        return redirect()->route('approver.assignedtome')
            ->with('success', 'Application ' . ucfirst($request->status) . ' successfully.');
    }

    public function exportCsv(Request $request)
    {
        //
    }

    public function exportPdf(Request $request)
    {
        //
    }
}
