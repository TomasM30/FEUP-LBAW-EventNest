<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdminPolicy
{
    public function viewDashboard(User $user): Response
    {
        return Admin::where('id_user', $user->id)->exists()
            ? Response::allow()
            : Response::deny('You must be an admin to view the admin dashboard.');
    }

    public function viewReportDetails(User $user): Response
    {
        return Admin::where('id_user', $user->id)->exists()
            ? Response::allow()
            : Response::deny('You must be an admin to view the report details.');
    }

    public function addTag(User $user): Response
    {
        return Admin::where('id_user', $user->id)->exists()
            ? Response::allow()
            : Response::deny('You must be an admin.');
    }
}
