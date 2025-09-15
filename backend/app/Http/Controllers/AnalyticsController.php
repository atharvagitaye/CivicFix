<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function issuesByStatus()
    {
        $data = \App\Models\Issue::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        return response()->json($data);
    }

    public function issuesByCategory()
    {
        $data = \App\Models\Issue::join('users', 'issues.user_id', '=', 'users.id')
            ->join('sub_categories', 'users.category_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->select('categories.name as category', \DB::raw('count(issues.id) as total'))
            ->groupBy('categories.name')
            ->get();
        return response()->json($data);
    }

    public function issuesByDate(Request $request)
    {
        $start = $request->query('start_date');
        $end = $request->query('end_date');
        $query = \App\Models\Issue::query();
        if ($start && $end) {
            $query->whereBetween('created_at', [$start, $end]);
        }
        $data = $query->selectRaw('DATE(created_at) as date, count(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        return response()->json($data);
    }
}
