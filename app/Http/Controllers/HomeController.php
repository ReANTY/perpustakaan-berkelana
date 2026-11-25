<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Book;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $pinnedLapak = Activity::query()
            ->where('is_published', true)
            ->where('type', 'upcoming_lapak')
            ->where('is_pinned_banner', true)
            ->latest('event_date')
            ->first();

        $featuredBooks = Book::query()
            ->orderByDesc('is_featured')
            ->latest()
            ->take(8)
            ->get();

        $recentActivities = Activity::query()
            ->where('is_published', true)
            ->whereIn('type', ['event_report', 'threads_highlight'])
            ->latest('event_date')
            ->latest()
            ->take(4)
            ->get();

        return view('home', compact('pinnedLapak', 'featuredBooks', 'recentActivities'));
    }
}
