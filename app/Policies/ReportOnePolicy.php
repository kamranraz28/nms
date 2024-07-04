<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Auth;
class ReportOnePolicy
{
    use HandlesAuthorization;


    public function grand(Admin $admin)
    {
        return $result = Role::roleHasGrantPermissions($admin->role_id, 'roports');
    }

    public function parent(Admin $admin)
    {
        return $result = Role::roleHasParentPermissions($admin->role_id, 'report_one');
    }

    public function create(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'report_one','report_one.create');
    }

    public function read(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'report_one','report_one.read');
    }

    public function print(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'report_one','report_one.print');
    }

    public function download(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'report_one','report_one.download');
    }

}
