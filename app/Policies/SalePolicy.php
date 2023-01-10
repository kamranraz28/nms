<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Auth;
class SalePolicy
{
    use HandlesAuthorization;


    public function grand(Admin $admin)
    {
        return $result = Role::roleHasGrantPermissions($admin->role_id, 'inventory');
    }

    public function parent(Admin $admin)
    {
        return $result = Role::roleHasParentPermissions($admin->role_id, 'sale');
    }

    public function create(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'sale','sale.create');
    }

    public function read(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'sale','sale.read');
    }

    public function update(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'sale','sale.update');
    }

    public function delete(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'sale','sale.delete');
    }

    public function delete_sale_details(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'sale','sale.delete.saleDetails');
    }

    public function view(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'sale','sale.view');
    }

    public function approval(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'sale','sale.approval');
    }

    public function print(Admin $admin)
    {
        return $result = Role::roleHasChildPermissions($admin->role_id,'sale','sale.print');
    }

}