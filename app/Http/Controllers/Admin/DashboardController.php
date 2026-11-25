<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Course, Video, Test, User, Purchase};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_courses' => Course::count(),
            'total_videos' => Video::count(),
            'total_tests' => Test::count(),
            'total_users' => User::where('role', 'student')->count(),
            'total_purchases' => Purchase::count(),
            'total_revenue' => Purchase::sum('amount'),
            'recent_users' => User::where('role', 'student')->latest()->take(5)->get(),
            'recent_purchases' => Purchase::with('user', 'purchasable')->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', $stats);
    }
}
