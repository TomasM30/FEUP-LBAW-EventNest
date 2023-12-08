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
    public function showDashboard(Request $request)
    {
        $this->authorize('viewDashboard', Admin::class);
        return view('pages.admin_dashboard');
    }

    public function showUsers(Request $request){
        $this->authorize('viewDashboard', Admin::class);
        $authenticatedUsers = AuthenticatedUser::paginate(8);
        if ($request->ajax()) {
            return view('partials.usersTable', ['users' => $authenticatedUsers])->render();
        }

        return view('pages.admin_users', ['users' => $authenticatedUsers]);
    }

    public function showEvents(Request $request){
        $this->authorize('viewDashboard', Admin::class);
        $now = Carbon::now();

        Event::where('date', '<', $now)
            ->where('closed', false)
            ->update(['closed' => true]);

        $events = Event::orderBy('date')->paginate(8);
        if ($request->ajax()) {
            return view('partials.eventsTable', ['events' => $events])->render();
        }
        log::info($events);

        return view('pages.admin_events', ['events' => $events]);
    }

    public function showReports(Request $request){
        $this->authorize('viewDashboard', Admin::class);
        $reports = Report::orderby('closed')->paginate(8);
        if ($request->ajax()) {
            return view('partials.reportsTable', ['reports' => $reports])->render();
        }

        return view('pages.admin_reports', ['reports' => $reports]);
    }

    public function showTags(Request $request){
        $this->authorize('viewDashboard', Admin::class);
        $tags = Hashtag::orderby('title')->paginate(5);
        if ($request->ajax()) {
            return view('partials.tagsTable', ['tags' => $tags])->render();
        }

        return view('pages.admin_tags', ['tags' => $tags]);
    }

    public function showReportDetails($id)
    {
        $this->authorize('viewReportDetails', Admin::class);
        $report = Report::with('user', 'event')->find($id);
        $reports = Report::orderBy('closed')->get();

        return view('pages.report_details', ['report' => $report, 'reports' => $reports]);
    }

    public function addTag(Request $request)
    {
        //$this->authorize('addTag', Admin::class);
        $tag = new Hashtag();
        $tag->title = $request->hashtag;
        $tag->save();

        return redirect()->route('dashboard');
    }

    public function deleteTag(Request $request)
    {
        //$this->authorize('deleteTag', Admin::class);
        $tag = Hashtag::find($request->id);
    
         $tag->events()->detach();
    
        $tag->delete();
    
        return redirect()->back();
    }

}
