<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuthenticatedUser;
use App\Models\Event;
use App\Models\Report;
use App\Models\Hashtag;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class AdminController extends Controller
{
    public function showDashboard()
    {
        $this->authorize('viewDashboard', Admin::class);
        $authenticatedUsers = AuthenticatedUser::with('user')->get()->sortBy('user.username');

        $now = Carbon::now();

        Event::where('date', '<', $now)
            ->where('closed', false)
            ->update(['closed' => true]);

        $events = Event::orderBy('date')->get();

        $reports = Report::all();

        $tags = Hashtag::all();
    
        return view('pages.admin_dashboard', ['users' => $authenticatedUsers, 'events' => $events, 'reports' => $reports, 'tags' => $tags]);
    }

    public function showReportDetails($id)
    {
        $this->authorize('viewReportDetails', Admin::class);
        $report = Report::with('user', 'event')->find($id);
        $reports = Report::orderBy('closed')->get();

        return view('pages.report_details', ['report' => $report, 'reports' => $reports]);
    }
}
