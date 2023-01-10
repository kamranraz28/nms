<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Auth;
class PurchasePolicy
{
    use HandlesAuthorization;


    public function grand(Admin $admin)
    {
        return $result = Role::roleHasGrantPermissions($admin->role_id, 'inventory');
    }

    public function parent(Admin $admin)
    {
        return $result = Role::roleHasParentPermissions($admin->role_id, 'purchase');
    }

    public function create(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'purchase','purchase.create');
    }

    public function read(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'purchase','purchase.read');
    }

    public function update(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'purchase','purchase.update');
    }

    public function delete(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'purchase','purchase.delete');
    }

    public function delete_purchase_details(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'purchase','purchase.delete.purchaseDetails');
    }

    public function view(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'purchase','purchase.view');
    }

    public function approval(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'purchase','purchase.approval');
    }

    public function print(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'purchase','purchase.print');
    }

}
