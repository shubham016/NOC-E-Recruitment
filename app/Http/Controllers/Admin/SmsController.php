<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\CandidateRegistration;
use App\Models\JobPosting;
use App\Models\SmsLog;
use App\Services\SparrowSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SmsController extends Controller
{
    protected SparrowSmsService $sms;

    public function __construct(SparrowSmsService $sms)
    {
        $this->sms = $sms;
    }

    public function index(Request $request)
    {
        $query = SmsLog::with(['candidate', 'jobPosting'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhereHas('candidate', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('vacancy')) {
            $query->where('job_posting_id', $request->vacancy);
        }

        if ($request->filled('status')) {
            if ($request->status === 'sent') {
                $query->where('response_code', 200);
            } else {
                $query->where(function ($q) {
                    $q->where('response_code', '!=', 200)->orWhereNull('response_code');
                });
            }
        }

        $logs = $query->paginate(20);
        $credits = $this->sms->getCredits();

        return view('admin.sms.index', compact('logs', 'credits'));
    }

    public function create()
    {
        $jobs = JobPosting::orderBy('created_at', 'desc')
            ->get(['id', 'title', 'position', 'level', 'advertisement_no', 'status', 'category']);

        $credits = $this->sms->getCredits();

        return view('admin.sms.create', compact('jobs', 'credits'));
    }

    public function getApplicants(Request $request)
    {
        $request->validate([
            'job_posting_id' => ['required', 'integer', 'exists:job_postings,id'],
        ]);

        $applicants = ApplicationForm::where('job_posting_id', $request->job_posting_id)
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->select('id', 'name_english', 'phone', 'citizenship_number', 'status')
            ->orderBy('name_english')
            ->get();

        return response()->json($applicants);
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_posting_id' => ['required', 'integer', 'exists:job_postings,id'],
            'application_ids' => ['required', 'array', 'min:1'],
            'application_ids.*' => ['integer'],
            'message' => ['required', 'string', 'max:500'],
        ]);

        $job = JobPosting::findOrFail($request->job_posting_id);

        $applications = ApplicationForm::whereIn('id', $request->application_ids)
            ->where('job_posting_id', $request->job_posting_id)
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->get();

        if ($applications->isEmpty()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No applicants with valid phone numbers found.');
        }

        // Collect unique phones (same candidate may not apply twice, but safety check)
        $phoneMap = [];
        foreach ($applications as $app) {
            $phoneMap[$app->phone] = $app;
        }

        $phones = implode(',', array_keys($phoneMap));
        $result = $this->sms->send($phones, $request->message);

        $adminId = Auth::guard('admin')->id();

        foreach ($applications as $app) {
            // Find candidate_id via citizenship_number
            $candidate = DB::table('candidate_registration')
                ->where('citizenship_number', $app->citizenship_number)
                ->first();

            SmsLog::create([
                'admin_id'         => $adminId,
                'candidate_id'     => $candidate->id ?? null,
                'job_posting_id'   => $request->job_posting_id,
                'phone'            => $app->phone,
                'message'          => $request->message,
                'response_code'    => $result['response_code'] ?? null,
                'response_message' => $result['response'] ?? null,
            ]);
        }

        if (($result['response_code'] ?? 0) == 200) {
            $count = $result['count'] ?? count($phoneMap);
            return redirect()->route('admin.sms.index')
                ->with('success', "SMS sent successfully to {$count} applicant(s) of \"{$job->title}\".");
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'SMS sending failed: ' . ($result['response'] ?? 'Unknown error'));
    }

    public function show(SmsLog $smsLog)
    {
        $smsLog->load('candidate', 'admin');
        return view('admin.sms.show', ['sm' => $smsLog]);
    }
}
