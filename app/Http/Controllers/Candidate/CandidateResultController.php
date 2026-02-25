<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateResultController extends Controller
{
    /**
     * View all results for the candidate
     */
    public function index()
    {
        $candidate = Auth::guard('candidate')->user();

        $results = Result::where('candidate_id', $candidate->id)
            ->whereNotNull('published_at')
            ->with('applicationForm.jobPosting')
            ->latest('published_at')
            ->get();

        return view('candidate.view-result', compact('results', 'candidate'));
    }

    /**
     * View a specific result detail
     */
    public function show($id)
    {
        $candidate = Auth::guard('candidate')->user();

        $result = Result::where('id', $id)
            ->where('candidate_id', $candidate->id)
            ->whereNotNull('published_at')
            ->with('applicationForm.jobPosting')
            ->firstOrFail();

        return view('candidate.result-detail', compact('result', 'candidate'));
    }

    /**
     * Search results by roll number or citizenship number
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
        ]);

        $query = $request->input('query');

        $results = Result::whereNotNull('published_at')
            ->where(function ($q) use ($query) {
                $q->where('roll_number', 'like', "%{$query}%")
                    ->orWhere('citizenship_number', 'like', "%{$query}%")
                    ->orWhere('full_name', 'like', "%{$query}%");
            })
            ->with('applicationForm.jobPosting')
            ->latest('published_at')
            ->paginate(20);

        return view('candidate.view-result', compact('results'))->with('search', $query);
    }
}
