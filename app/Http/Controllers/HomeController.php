<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issue;
use App\Models\Category;

class HomeController extends Controller
{   
    public function showWelcome() {
        return view('welcome');
    }
    /**
     * Show the application home page.
     */
    public function index()
    {
        // Get some basic stats for the home page
        $stats = [
            'total_issues' => Issue::count(),
            'resolved_issues' => Issue::where('status', 'resolved')->count(),
            'categories' => Category::count(),
            'in_progress_issues' => Issue::where('status', 'in_progress')->count(),
        ];

        return view('home', compact('stats'));
    }
}