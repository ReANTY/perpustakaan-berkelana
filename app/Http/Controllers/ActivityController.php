<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->query('type', 'all');

        $query = Activity::query()
            ->where('is_published', true)
            ->latest('event_date')
            ->latest();

        if (in_array($type, ['upcoming_lapak', 'event_report', 'threads_highlight'])) {
            $query->where('type', $type);
        } else {
            $type = 'all';
        }

        $activities = $query->paginate(9)->withQueryString();

        return view('activities.index', compact('activities', 'type'));
    }

    public function show(Activity $activity): View
    {
        abort_unless($activity->is_published, 404);

        return view('activities.show', compact('activity'));
    }
}
