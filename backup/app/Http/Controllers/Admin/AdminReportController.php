<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\JobPosting;
use App\Models\Candidate;
use App\Models\Reviewer;
use App\Models\Approver;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index()
    {
        $summary = [
            'total_vacancies'    => JobPosting::count(),
            'active_vacancies'   => JobPosting::where('status', 'active')->count(),
            'total_applications' => ApplicationForm::count(),
            'total_candidates'   => Candidate::count(),
            'total_reviewers'    => Reviewer::count(),
            'total_approvers'    => Approver::count(),
        ];

        return view('admin.reports.index', compact('summary'));
    }

    // ── Applications ──────────────────────────────────────────────────────────

    private function getApplications(Request $request)
    {
        $query = ApplicationForm::with(['reviewer', 'approver'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name_english', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('advertisement_no', 'like', "%{$s}%")
                  ->orWhere('position', 'like', "%{$s}%");
            });
        }
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('from'))   $query->whereDate('created_at', '>=', $request->from);
        if ($request->filled('to'))     $query->whereDate('created_at', '<=', $request->to);

        return $query->get();
    }

    public function previewApplications(Request $request)
    {
        $applications = $this->getApplications($request);
        return view('admin.reports.preview.applications', compact('applications'));
    }

    public function downloadApplications(Request $request)
    {
        $applications = $this->getApplications($request);
        $pdf = Pdf::loadView('admin.reports.pdf.applications', compact('applications'));
        $pdf->getDomPDF()->set_option('isRemoteEnabled', true);
        return $pdf->download('applications_report_' . date('Y-m-d') . '.pdf');
    }

    // ── Candidates ────────────────────────────────────────────────────────────

    private function getCandidates(Request $request)
    {
        $query = Candidate::latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('username', 'like', "%{$s}%")
                  ->orWhere('mobile_number', 'like', "%{$s}%");
            });
        }
        if ($request->filled('gender')) $query->where('gender', $request->gender);
        if ($request->filled('from'))   $query->whereDate('created_at', '>=', $request->from);
        if ($request->filled('to'))     $query->whereDate('created_at', '<=', $request->to);

        return $query->get();
    }

    public function previewCandidates(Request $request)
    {
        $candidates = $this->getCandidates($request);
        return view('admin.reports.preview.candidates', compact('candidates'));
    }

    public function downloadCandidates(Request $request)
    {
        $candidates = $this->getCandidates($request);
        $pdf = Pdf::loadView('admin.reports.pdf.candidates', compact('candidates'));
        $pdf->getDomPDF()->set_option('isRemoteEnabled', true);
        return $pdf->download('candidates_report_' . date('Y-m-d') . '.pdf');
    }

    // ── Vacancies ─────────────────────────────────────────────────────────────

    private function getVacancies(Request $request)
    {
        $query = JobPosting::withCount('applications');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('position', 'like', "%{$s}%")
                  ->orWhere('advertisement_no', 'like', "%{$s}%")
                  ->orWhere('service_group', 'like', "%{$s}%")
                  ->orWhere('department', 'like', "%{$s}%");
            });
        }
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('from'))   $query->whereDate('created_at', '>=', $request->from);
        if ($request->filled('to'))     $query->whereDate('created_at', '<=', $request->to);

        return $query->latest()->get();
    }

    public function previewVacancies(Request $request)
    {
        $jobs = $this->getVacancies($request);
        return view('admin.reports.preview.vacancies', compact('jobs'));
    }

    public function downloadVacancies(Request $request)
    {
        $jobs = $this->getVacancies($request);
        $pdf = Pdf::loadView('admin.reports.pdf.vacancies', compact('jobs'));
        $pdf->getDomPDF()->set_option('isRemoteEnabled', true);
        return $pdf->download('vacancies_report_' . date('Y-m-d') . '.pdf');
    }

    // ── Reviewers ─────────────────────────────────────────────────────────────

    private function getReviewers(Request $request)
    {
        return Reviewer::withCount([
            'applicationForms as application_forms_count',
            'applicationForms as reviewed_count' => fn($q) => $q->whereNotNull('reviewed_at'),
            'applicationForms as pending_count'  => fn($q) => $q->whereNull('reviewed_at'),
        ])
        ->when($request->filled('search'), function ($q) use ($request) {
            $s = $request->search;
            $q->where(function ($q2) use ($s) {
                $q2->where('name', 'like', "%{$s}%")
                   ->orWhere('email', 'like', "%{$s}%");
            });
        })
        ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
        ->get();
    }

    public function previewReviewers(Request $request)
    {
        $reviewers = $this->getReviewers($request);
        return view('admin.reports.preview.reviewers', compact('reviewers'));
    }

    public function downloadReviewers(Request $request)
    {
        $reviewers = $this->getReviewers($request);
        $pdf = Pdf::loadView('admin.reports.pdf.reviewers', compact('reviewers'));
        $pdf->getDomPDF()->set_option('isRemoteEnabled', true);
        return $pdf->download('reviewers_report_' . date('Y-m-d') . '.pdf');
    }

    // ── Approvers ─────────────────────────────────────────────────────────────

    private function getApprovers(Request $request)
    {
        return Approver::withCount([
            'applicationForms as approved_count' => fn($q) => $q->where('status', 'approved'),
            'applicationForms as rejected_count' => fn($q) => $q->where('status', 'rejected'),
        ])
        ->when($request->filled('search'), function ($q) use ($request) {
            $s = $request->search;
            $q->where(function ($q2) use ($s) {
                $q2->where('name', 'like', "%{$s}%")
                   ->orWhere('email', 'like', "%{$s}%");
            });
        })
        ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
        ->get();
    }

    public function previewApprovers(Request $request)
    {
        $approvers = $this->getApprovers($request);
        return view('admin.reports.preview.approvers', compact('approvers'));
    }

    public function downloadApprovers(Request $request)
    {
        $approvers = $this->getApprovers($request);
        $pdf = Pdf::loadView('admin.reports.pdf.approvers', compact('approvers'));
        $pdf->getDomPDF()->set_option('isRemoteEnabled', true);
        return $pdf->download('approvers_report_' . date('Y-m-d') . '.pdf');
    }
}
