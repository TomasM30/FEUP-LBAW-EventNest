<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuthenticatedUser;
use App\Models\Event;
use App\Models\Report;
use Carbon\Carbon;


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
    
        return view('pages.admin_dashboard', ['users' => $authenticatedUsers, 'events' => $events]);
    }

    public function showReportDetails($id)
    {
        $report = Report::with('user', 'event')->find($id);

        return view('pages.report_details', ['report' => $report]);
    }
}
