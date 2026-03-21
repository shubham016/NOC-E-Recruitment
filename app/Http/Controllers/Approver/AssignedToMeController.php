<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job; 
use Illuminate\Pagination\LengthAwarePaginator; 

class AssignedToMeController extends Controller
{
    public function index()
    {
        $jobs = Job::select('id','title')->get();

        $applications = new LengthAwarePaginator(
        [], // empty data
        0,
        10
    );
        return view('approver.assignedtome', compact('jobs', 'applications'));
    }

    public function exportCsv(Request $request)
    {
        $ids = $request->ids ?? [];
        // Fetch applications based on $ids and export CSV
    }

    public function exportPdf(Request $request)
    {
        $ids = $request->ids ?? [];
        // Fetch applications based on $ids and export PDF
    }
}
