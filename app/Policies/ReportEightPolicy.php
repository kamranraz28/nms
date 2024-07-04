<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Auth;
class ReportEightPolicy
{
    use HandlesAuthorization;


    public function grand(Admin $admin)
    {
        return $result = Role::roleHasGrantPermissions($admin->role_id, 'roports');
    }

    public function parent(Admin $admin)
    {
        return $result = Role::roleHasParentPermissions($admin->role_id, 'report_eight');
    }

    public function create(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'report_eight','report_eight.create');
    }

    public function read(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'report_eight','report_eight.read');
    }

    public function print(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'report_eight','report_eight.print');
    }

    public function download(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'report_eight','report_eight.print');
    }
}
