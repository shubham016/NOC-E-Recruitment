<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;

class SmsController extends Controller
{
    public function index()
    {
        $candidateId = session('candidate_id');

        $logs = SmsLog::where('candidate_id', $candidateId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('candidate.sms.index', compact('logs'));
    }
}
