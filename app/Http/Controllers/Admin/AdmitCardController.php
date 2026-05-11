<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdmitCardController extends Controller
{
    /**
     * List all job postings with eligible applications for admit card assignment.
     */
    public function index()
    {
        $groups = DB::table('application_form as af')
            ->join('job_postings as jp', 'af.job_posting_id', '=', 'jp.id')
            ->whereIn('af.status', ['approved', 'under_review'])
            ->select(
                'af.job_posting_id',
                'jp.advertisement_no',
                'jp.notice_no',
                'jp.position',
                'jp.level',
                'jp.service_group',
                DB::raw('COUNT(af.id) as total_candidates'),
                DB::raw('SUM(CASE WHEN af.roll_number IS NOT NULL AND af.status = \'assigned\' THEN 1 ELSE 0 END) as assigned_count'),
                DB::raw('MAX(af.exam_date) as exam_date'),
                DB::raw('MAX(af.exam_venue) as exam_venue')
            )
            ->groupBy('af.job_posting_id', 'jp.advertisement_no', 'jp.notice_no', 'jp.position', 'jp.level', 'jp.service_group')
            ->orderBy('jp.notice_no', 'asc')
            ->orderBy('jp.advertisement_no', 'asc')
            ->get();

        return view('admin.admit-card.index', compact('groups'));
    }

    /**
     * Show the bulk admit card assignment form for a job posting.
     */
    public function assign($job_posting_id)
    {
        $job = JobPosting::findOrFail($job_posting_id);

        $applications = DB::table('application_form')
            ->where('job_posting_id', $job_posting_id)
            ->whereIn('status', ['approved', 'under_review', 'assigned'])
            ->orderBy('id', 'asc')
            ->get();

        if ($applications->isEmpty()) {
            return redirect()->route('admin.admit-card.index')
                ->with('error', 'No eligible applications found for this vacancy.');
        }

        $existing = $applications->first();

        // Resolve inclusive sub-types: check this job first, then sibling postings with same position+level
        $inclusiveTypes = [];
        $it = $job->inclusive_type;
        if ($it) {
            $decoded = is_array($it) ? $it : (json_decode($it, true) ?: []);
            $inclusiveTypes = $decoded;
        }
        if (empty($inclusiveTypes)) {
            $siblingInclusive = JobPosting::where('position', $job->position)
                ->where('level', $job->level)
                ->where(function ($q) {
                    $q->where('has_inclusive', 1)->orWhere('category', 'inclusive');
                })
                ->whereNotNull('inclusive_type')
                ->first(['inclusive_type']);
            if ($siblingInclusive) {
                $sit = $siblingInclusive->inclusive_type;
                $inclusiveTypes = is_array($sit) ? $sit : (json_decode($sit, true) ?: []);
            }
        }

        return view('admin.admit-card.assign', compact('job', 'applications', 'existing', 'inclusiveTypes'));
    }

    /**
     * Bulk assign admit card details to all eligible applications for a job posting.
     */
    public function store(Request $request, $job_posting_id)
    {
        $job = JobPosting::findOrFail($job_posting_id);

        $request->validate([
            'exam_date_first'   => 'required|string|max:50',
            'exam_time_first'   => 'required|string|max:50',
            'exam_date_second'  => 'nullable|string|max:50',
            'exam_time_second'  => 'nullable|string|max:50',
            'exam_venue'        => 'required|string|max:500',
            'organization_name' => 'required|string|max:255',
            'post_title'               => 'required|string|max:255',
            'admit_card_service_group' => 'nullable|string|max:255',
            'roll_prefix'              => 'required|string|max:50',
            'exam_instructions'        => 'nullable|string',
        ]);

        $applications = DB::table('application_form')
            ->where('job_posting_id', $job_posting_id)
            ->whereIn('status', ['approved', 'under_review', 'assigned'])
            ->orderBy('id', 'asc')
            ->get();

        if ($applications->isEmpty()) {
            return redirect()->route('admin.admit-card.index')
                ->with('error', 'No eligible applications found.');
        }

        $prefix  = rtrim($request->roll_prefix, '-');
        $counter = 1;

        foreach ($applications as $app) {
            // Preserve existing roll number; only generate new one if not yet assigned
            if (empty($app->roll_number)) {
                $rollNumber = $prefix . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
                // Ensure uniqueness
                while (DB::table('application_form')->where('roll_number', $rollNumber)->exists()) {
                    $counter++;
                    $rollNumber = $prefix . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
                }
            } else {
                $rollNumber = $app->roll_number;
            }

            DB::table('application_form')
                ->where('id', $app->id)
                ->update([
                    'exam_date_first'   => $request->exam_date_first,
                    'exam_time_first'   => $request->exam_time_first,
                    'exam_date_second'  => $request->exam_date_second,
                    'exam_time_second'  => $request->exam_time_second,
                    'exam_venue'        => $request->exam_venue,
                    'exam_instructions' => $request->exam_instructions,
                    'organization_name'        => $request->organization_name,
                    'post_title'               => $request->post_title,
                    'admit_card_service_group' => $request->admit_card_service_group,
                    'roll_number'       => $rollNumber,
                    'status'            => 'assigned',
                    'updated_at'        => now(),
                ]);

            $counter++;
        }

        return redirect()->route('admin.admit-card.index')
            ->with('success', 'Admit cards assigned to ' . $applications->count() . ' candidate(s) for ' . $job->advertisement_no . '.');
    }

    /**
     * Preview assigned admit cards for a job posting.
     */
    public function preview($job_posting_id)
    {
        $job = JobPosting::findOrFail($job_posting_id);

        $applications = DB::table('application_form')
            ->where('job_posting_id', $job_posting_id)
            ->where('status', 'assigned')
            ->whereNotNull('roll_number')
            ->orderBy('roll_number', 'asc')
            ->get();

        return view('admin.admit-card.preview', compact('applications', 'job'));
    }
}
