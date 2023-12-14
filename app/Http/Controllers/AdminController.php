<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\AuthenticatedUser;
use App\Models\Event;
use App\Models\Report;
use App\Models\Hashtag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class AdminController extends Controller
{
    public function blockUser (Request $request)
    {
        
        $authenticatedUser = AuthenticatedUser::find($request->id);

        try {

            DB::BeginTransaction();

            if ( !($authenticatedUser->is_blocked) )
            {
                $authenticatedUser->is_blocked = true;
                $authenticatedUser->save();
                DB::commit();
                return redirect()->back()->with('success', 'User blocked successfuly');
            }
        }catch(\Exception $e){
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function unblockUser(Request $request)
    {
        
        $authenticatedUser = AuthenticatedUser::find($request->id);

        try {
            DB::beginTransaction();

            
            if ($authenticatedUser->is_blocked) 
            {
                $authenticatedUser->is_blocked = false; 
                $authenticatedUser->save();
                DB::commit();
                return redirect()->back()->with('success', 'User unblocked successfully');
            }

            return redirect()->back()->with('error', 'User is not blocked');
        
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            }
    }




    public function showDashboard(Request $request)
    {
        $this->authorize('viewDashboard', Admin::class);

        $allusersCount = User::count();
        $usersverifiedCount = AuthenticatedUser::where('is_verified', true)->count();
        $usersnotverifiedCount = AuthenticatedUser::where('is_verified', false)->count();
        $hasthagsbycount = Hashtag::withCount('events')->orderBy('events_count', 'desc')->take(3)->get();
        $ongoingevents = Event::where('closed', false)->count();
        $closedevents = Event::where('closed', true)->count();
        $eventscount = Event::count();

        return view('pages.admin_dashboard', [
            'allusersCount' => $allusersCount,
            'usersverifiedCount' => $usersverifiedCount,
            'usersnotverifiedCount' => $usersnotverifiedCount,
            'hasthagsbycount' => $hasthagsbycount,
            'ongoingevents' => $ongoingevents,
            'closedevents' => $closedevents,
            'eventscount' => $eventscount
        ]);
    }
    public function showUsers(Request $request)
    {
        $this->authorize('viewDashboard', Admin::class);
        $authenticatedUsers = AuthenticatedUser::join('users', 'authenticated.id_user', '=', 'users.id')
            ->select('authenticated.*', 'users.username') // select columns from authenticated and username from users
            ->orderBy('users.username')
            ->paginate(8);

        if ($request->ajax()) {
            return view('partials.usersTable', ['users' => $authenticatedUsers])->render();
        }

        return view('pages.admin_users', ['users' => $authenticatedUsers]);
    }

    public function showEvents(Request $request)
    {
        $this->authorize('viewDashboard', Admin::class);
        $now = Carbon::now();

        Event::where('date', '<', $now)
            ->where('closed', false)
            ->update(['closed' => true]);

        $events = Event::orderBy('date')->paginate(8);
        if ($request->ajax()) {
            return view('partials.eventsTable', ['events' => $events])->render();
        }

        return view('pages.admin_events', ['events' => $events]);
    }

    public function showReports(Request $request)
    {
        $this->authorize('viewDashboard', Admin::class);
        $reports = Report::orderby('closed')->paginate(8);
        if ($request->ajax()) {
            return view('partials.reportsTable', ['reports' => $reports])->render();
        }

        return view('pages.admin_reports', ['reports' => $reports]);
    }

    public function showTags(Request $request)
    {
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

    public function searchUsers(Request $request)
    {
        $this->authorize('viewDashboard', Admin::class);
        $search = $request->get('search');
        $users = AuthenticatedUser::join('users', 'authenticated.id_user', '=', 'users.id')
            ->where('users.username', 'like', '%' . $search . '%')
            ->select('authenticated.*')
            ->orderBy('users.username')
            ->paginate(8);
        return view('partials.usersTable', ['users' => $users])->render();
    }

    public function searchEvents(Request $request)
    {
        $this->authorize('viewDashboard', Admin::class);
        $search = $request->get('search');
        if ($search) {
            $events = Event::whereRaw("to_tsvector('english', title) @@ plainto_tsquery('english', ?)", [$search])
                ->paginate(8);
        } else {
            $now = Carbon::now();
            Event::where('date', '<', $now)
                ->where('closed', false)
                ->update(['closed' => true]);
            $events = Event::orderBy('date')->paginate(8);
        }
        return view('partials.eventsTable', ['events' => $events])->render();
    }
}
