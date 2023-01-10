<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Auth;
class ReportNinePolicy
{
    use HandlesAuthorization;


    public function grand(Admin $admin)
    {
        return $result = Role::roleHasGrantPermissions($admin->role_id, 'roports');
    }

    public function parent(Admin $admin)
    {
        return $result = Role::roleHasParentPermissions($admin->role_id, 'report_nine');
    }

    public function create(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'report_nine','report_nine.create');
    }

    public function read(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'report_nine','report_nine.read');
    }

    public function print(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'report_nine','report_nine.print');
    }

    public function download(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'report_nine','report_nine.download');
    }

    public function export(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'report_nine','report_nine.export');
    }

}
