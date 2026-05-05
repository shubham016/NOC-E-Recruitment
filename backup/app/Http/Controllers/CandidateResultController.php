<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CandidateResultController extends Controller
{
    /**
     * Display all results for the logged-in candidate
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function viewResult()
{
    $candidateId = Session::get('candidate_id');

    if (!$candidateId) {
        return redirect()->route('candidate.login')
            ->with('error', 'Please login to view your results.');
    }

    try {
        $results = Result::forCandidate($candidateId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('candidate.view-result', compact('results'));
        
    } catch (\Exception $e) {
        \Log::error('Error fetching results: ' . $e->getMessage());
        
        // Return empty results instead of redirecting
        $results = collect([]);
        return view('candidate.view-result', compact('results'));
    }
}
    /**
     * Display only published results for the logged-in candidate
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function viewPublishedResult()
    {
        $candidateId = Session::get('candidate_id');

        if (!$candidateId) {
            return redirect()->route('candidate.login')
                ->with('error', 'Please login to view your results.');
        }

        try {
            // Fetch only published results (status = 'published' and marks not null)
            // Orders by highest marks first
            $results = Result::forCandidate($candidateId)
                ->published()
                ->orderBy('marks', 'desc')
                ->get();

            return view('candidate.view-result', compact('results'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching published results: ' . $e->getMessage());
            
            return redirect()->route('candidate.dashboard')
                ->with('error', 'Unable to fetch results. Please try again later.');
        }
    }

    /**
     * Show detailed view of a specific result
     * 
     * @param int $id Result ID
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showResult($id)
    {
        $candidateId = Session::get('candidate_id');

        if (!$candidateId) {
            return redirect()->route('candidate.login')
                ->with('error', 'Please login to view your results.');
        }

        try {
            // Fetch specific result and verify it belongs to the logged-in candidate
            $result = Result::where('id', $id)
                ->forCandidate($candidateId)
                ->first();

            // If result not found or doesn't belong to this candidate
            if (!$result) {
                return redirect()->route('candidate.viewresult')
                    ->with('error', 'Result not found or you do not have permission to view it.');
            }

            return view('candidate.result-detail', compact('result'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching result detail: ' . $e->getMessage());
            
            return redirect()->route('candidate.viewresult')
                ->with('error', 'Unable to fetch result details. Please try again later.');
        }
    }

    /**
     * Get result statistics for the candidate (useful for dashboard widgets)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getResultStats()
    {
        $candidateId = Session::get('candidate_id');

        if (!$candidateId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $stats = [
                'total' => Result::forCandidate($candidateId)->count(),
                'published' => Result::forCandidate($candidateId)->published()->count(),
                'pending' => Result::forCandidate($candidateId)->pending()->count(),
                'withheld' => Result::forCandidate($candidateId)->where('status', 'withheld')->count(),
            ];

            // Get latest result
            $latestResult = Result::forCandidate($candidateId)
                ->latest('created_at')
                ->first();

            if ($latestResult) {
                $stats['latest'] = [
                    'post' => $latestResult->post,
                    'status' => $latestResult->status,
                    'marks' => $latestResult->marks,
                    'class' => $latestResult->class,
                ];
            }

            return response()->json($stats);
            
        } catch (\Exception $e) {
            Log::error('Error fetching result stats: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch statistics'], 500);
        }
    }

    /**
     * Download result as PDF (future feature)
     * 
     * @param int $id Result ID
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function downloadResultPdf($id)
    {
        $candidateId = Session::get('candidate_id');

        if (!$candidateId) {
            return redirect()->route('candidate.login')
                ->with('error', 'Please login to download your result.');
        }

        try {
            $result = Result::where('id', $id)
                ->forCandidate($candidateId)
                ->published()
                ->first();

            if (!$result) {
                return redirect()->route('candidate.viewresult')
                    ->with('error', 'Result not found or not yet published.');
            }

            // TODO: Implement PDF generation
            // For now, redirect back with message
            return redirect()->route('candidate.result.show', $id)
                ->with('info', 'PDF download feature coming soon!');
            
        } catch (\Exception $e) {
            Log::error('Error downloading result PDF: ' . $e->getMessage());
            
            return redirect()->route('candidate.viewresult')
                ->with('error', 'Unable to download result. Please try again later.');
        }
    }

    /**
     * Search results by roll number or advertisement code
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function searchResults(Request $request)
    {
        $candidateId = Session::get('candidate_id');

        if (!$candidateId) {
            return redirect()->route('candidate.login')
                ->with('error', 'Please login to search results.');
        }

        $request->validate([
            'search' => 'nullable|string|max:100',
            'filter' => 'nullable|in:all,published,pending,withheld',
        ]);

        try {
            $query = Result::forCandidate($candidateId);

            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('roll_number', 'LIKE', "%{$search}%")
                      ->orWhere('advertisement_code', 'LIKE', "%{$search}%")
                      ->orWhere('post', 'LIKE', "%{$search}%");
                });
            }

            // Apply status filter
            if ($request->filled('filter') && $request->filter !== 'all') {
                if ($request->filter === 'published') {
                    $query->published();
                } elseif ($request->filter === 'pending') {
                    $query->pending();
                } else {
                    $query->where('status', $request->filter);
                }
            }

            $results = $query->orderBy('created_at', 'desc')->get();

            return view('candidate.view-result', compact('results'));
            
        } catch (\Exception $e) {
            Log::error('Error searching results: ' . $e->getMessage());
            
            return redirect()->route('candidate.viewresult')
                ->with('error', 'Unable to search results. Please try again later.');
        }
    }

    /**
     * Check if new results are available (for AJAX polling)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkNewResults()
    {
        $candidateId = Session::get('candidate_id');

        if (!$candidateId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Get count of results published in last 24 hours
            $newResults = Result::forCandidate($candidateId)
                ->where('published_at', '>=', now()->subDay())
                ->count();

            return response()->json([
                'has_new' => $newResults > 0,
                'count' => $newResults,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error checking new results: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to check for new results'], 500);
        }
    }

    /**
     * Get results grouped by status for analytics
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getResultsByStatus()
    {
        $candidateId = Session::get('candidate_id');

        if (!$candidateId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $results = Result::forCandidate($candidateId)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status');

            return response()->json($results);
            
        } catch (\Exception $e) {
            Log::error('Error fetching results by status: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch data'], 500);
        }
    }

    /**
     * Export results to CSV
     * 
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
     */
    public function exportResults()
    {
        $candidateId = Session::get('candidate_id');

        if (!$candidateId) {
            return redirect()->route('candidate.login')
                ->with('error', 'Please login to export your results.');
        }

        try {
            $results = Result::forCandidate($candidateId)
                ->orderBy('created_at', 'desc')
                ->get();

            $filename = 'my_results_' . date('Y-m-d') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function() use ($results) {
                $file = fopen('php://output', 'w');
                
                // Add CSV headers
                fputcsv($file, [
                    'Roll Number',
                    'Full Name',
                    'Post',
                    'Advertisement Code',
                    'Marks',
                    'Class',
                    'Status',
                    'Published Date',
                ]);

                // Add data rows
                foreach ($results as $result) {
                    fputcsv($file, [
                        $result->roll_number,
                        $result->full_name,
                        $result->post,
                        $result->advertisement_code,
                        $result->marks ?? 'N/A',
                        $result->class ?? 'N/A',
                        ucfirst($result->status),
                        $result->published_at ? $result->published_at->format('Y-m-d') : 'N/A',
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('Error exporting results: ' . $e->getMessage());
            
            return redirect()->route('candidate.viewresult')
                ->with('error', 'Unable to export results. Please try again later.');
        }
    }
}