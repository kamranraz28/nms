<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;


class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Permission::truncate();
        DB::table('roles_permissions')->truncate();
        DB::table('permissions')->truncate();

        
        // Permission List as array
        $permissions = [

            
            [
                'group_parent_name' => 'role-permissions',
                'group_name' => 'role',
                'permissions' => [
                    'role.create',
                    'role.read',
                    'role.update',
                    'role.delete',
                    'role.permission.update',
                ]
            ],
            
            [
                'group_parent_name' => 'role-permissions',
                'group_name' => 'user_type',
                'permissions' => [
                    'user_type.create',
                    'user_type.read',
                    'user_type.update',
                    'user_type.delete'
                ]
            ],
            [
                'group_parent_name' => 'role-permissions',
                'group_name' => 'site_setting',
                'permissions' => [
                    'site_setting.create',
                    'site_setting.read',
                    'site_setting.update',
                    'site_setting.delete'
                ]
            ],

            [
                'group_parent_name' => 'role-permissions',
                'group_name' => 'lang',
                'permissions' => [
                    'lang.create',
                    'lang.read',
                    'lang.update',
                    'lang.delete'
                ]
            ],

            // [
            //     'group_parent_name' => 'locations',
            //     'group_name' => 'state',
            //     'permissions' => [
            //         'state.create',
            //         'state.read',
            //         'state.update',
            //         'state.delete'
            //     ]
            // ],

            [
                'group_parent_name' => 'locations',
                'group_name' => 'division',
                'permissions' => [
                    'division.create',
                    'division.read',
                    'division.update',
                    'division.delete'
                ]
            ],

            [
                'group_parent_name' => 'locations',
                'group_name' => 'district',
                'permissions' => [
                    'district.create',
                    'district.read',
                    'district.update',
                    'district.delete'
                ]
            ],

            [
                'group_parent_name' => 'locations',
                'group_name' => 'upazila',
                'permissions' => [
                    'upazila.create',
                    'upazila.read',
                    'upazila.update',
                    'upazila.delete'
                ]
            ],

            [
                'group_parent_name' => 'offices',
                'group_name' => 'forest_state',
                'permissions' => [
                    'forest_state.create',
                    'forest_state.read',
                    'forest_state.update',
                    'forest_state.delete'
                ]
            ],

            [
                'group_parent_name' => 'offices',
                'group_name' => 'forest_division',
                'permissions' => [
                    'forest_division.create',
                    'forest_division.read',
                    'forest_division.update',
                    'forest_division.delete'
                ]
            ],
            

            [
                'group_parent_name' => 'offices',
                'group_name' => 'forest_range',
                'permissions' => [
                    'forest_range.create',
                    'forest_range.read',
                    'forest_range.update',
                    'forest_range.delete'
                ]
            ],

            [
                'group_parent_name' => 'offices',
                'group_name' => 'forest_beat',
                'permissions' => [
                    'forest_beat.create',
                    'forest_beat.read',
                    'forest_beat.update',
                    'forest_beat.delete'
                ]
            ],






            [
                'group_parent_name' => 'users',
                'group_name' => 'user',
                'permissions' => [
                    'user.create',
                    'user.read',
                    'user.update',
                    'user.delete'
                ]
            ],
            [
                'group_parent_name' => 'users',
                'group_name' => 'admin',
                'permissions' => [
                    'admin.create',
                    'admin.read',
                    'admin.update',
                    'admin.delete',
                    'admin.change.password',
                ]
            ],
            
            [
                'group_parent_name' => 'users',
                'group_name' => 'range_office',
                'permissions' => [
                    'range_office.create',
                    'range_office.read',
                    'range_office.update',
                    'range_office.delete',
                    'range_office.change.password',
                ]
            ],
            
            [
                'group_parent_name' => 'users',
                'group_name' => 'beat_office',
                'permissions' => [
                    'beat_office.create',
                    'beat_office.read',
                    'beat_office.update',
                    'beat_office.delete',
                    'beat_office.change.password',
                ]
            ],
            
            // [
            //     'group_parent_name' => 'users',
            //     'group_name' => 'nursery',
            //     'permissions' => [
            //         'nursery.create',
            //         'nursery.read',
            //         'nursery.update',
            //         'nursery.delete'
            //     ]
            // ],

            // [
            //     'group_parent_name' => 'dashboards',
            //     'group_name' => 'dashboard',
            //     'permissions' => [
            //         'dashboard.read'
            //     ]
            // ],
            

            // [
            //     'group_parent_name' => 'pages',
            //     'group_name' => 'version',
            //     'permissions' => [
            //         'version.create',
            //         'version.read',
            //         'version.update',
            //         'version.delete'
            //     ]
            // ],

            // [
            //     'group_parent_name' => 'pages',
            //     'group_name' => 'content_category',
            //     'permissions' => [
            //         'content_category.create',
            //         'content_category.read',
            //         'content_category.update',
            //         'content_category.delete'
            //     ]
            // ],

            // [
            //     'group_parent_name' => 'pages',
            //     'group_name' => 'content',
            //     'permissions' => [
            //         'content.create',
            //         'content.read',
            //         'content.update',
            //         'content.delete'
            //     ]
            // ],

            // [
            //     'group_parent_name' => 'products',
            //     'group_name' => 'color',
            //     'permissions' => [
            //         'color.create',
            //         'color.read',
            //         'color.update',
            //         'color.delete'
            //     ]
            // ],

            [
                'group_parent_name' => 'products',
                'group_name' => 'unit',
                'permissions' => [
                    'unit.create',
                    'unit.read',
                    'unit.update',
                    'unit.delete'
                ]
            ],

            [
                'group_parent_name' => 'products',
                'group_name' => 'size',
                'permissions' => [
                    'size.create',
                    'size.read',
                    'size.update',
                    'size.delete'
                ]
            ],

            [
                'group_parent_name' => 'products',
                'group_name' => 'age',
                'permissions' => [
                    'age.create',
                    'age.read',
                    'age.update',
                    'age.delete'
                ]
            ],

            [
                'group_parent_name' => 'products',
                'group_name' => 'category',
                'permissions' => [
                    'category.create',
                    'category.read',
                    'category.update',
                    'category.delete'
                ]
            ],

            [
                'group_parent_name' => 'products',
                'group_name' => 'stock_type',
                'permissions' => [
                    'stock_type.create',
                    'stock_type.read',
                    'stock_type.update',
                    'stock_type.delete'
                ]
            ],

            [
                'group_parent_name' => 'products',
                'group_name' => 'product',
                'permissions' => [
                    'product.create',
                    'product.read',
                    'product.update',
                    'product.delete'
                ]
            ],

            [
                'group_parent_name' => 'products',
                'group_name' => 'budget',
                'permissions' => [
                    'budget.create',
                    'budget.read',
                    'budget.update',
                    'budget.delete'
                ]
            ],

            [
                'group_parent_name' => 'products',
                'group_name' => 'financial_year',
                'permissions' => [
                    'financial_year.create',
                    'financial_year.read',
                    'financial_year.update',
                    'financial_year.delete'
                ]
            ],

            [
                'group_parent_name' => 'inventory',
                'group_name' => 'purchase',
                'permissions' => [
                    'purchase.create',
                    'purchase.read',
                    'purchase.update',
                    'purchase.delete',
                    'purchase.delete.purchaseDetails',
                    'purchase.view',
                    'purchase.approval',
                    'purchase.print'
                ]
            ],

            [
                'group_parent_name' => 'inventory',
                'group_name' => 'sale',
                'permissions' => [
                    'sale.create',
                    'sale.read',
                    'sale.update',
                    'sale.delete',
                    'sale.delete.saleDetails',
                    'sale.view',
                    'sale.approval',
                    'sale.print'
                ]
            ],

            [
                'group_parent_name' => 'reports',
                'group_name' => 'report_one',
                'permissions' => [
                    'report_one.create',
                    'report_one.read',
                    'report_one.print'
                ]
            ],

            [
                'group_parent_name' => 'reports',
                'group_name' => 'report_two',
                'permissions' => [
                    'report_two.create',
                    'report_two.read',
                    'report_two.print'
                ]
            ],

            [
                'group_parent_name' => 'reports',
                'group_name' => 'report_three',
                'permissions' => [
                    'report_three.create',
                    'report_three.read',
                    'report_three.print',
                ]
            ],

            // [
            //     'group_parent_name' => 'reports',
            //     'group_name' => 'report_four',
            //     'permissions' => [
            //         'report_four.create',
            //         'report_four.read',
            //         'report_four.print'
            //     ]
            // ],

            [
                'group_parent_name' => 'reports',
                'group_name' => 'report_five',
                'permissions' => [
                    'report_five.create',
                    'report_five.read',
                    'report_five.print'
                ]
            ],

            [
                'group_parent_name' => 'reports',
                'group_name' => 'report_six',
                'permissions' => [
                    'report_six.create',
                    'report_six.read',
                    'report_six.print'
                ]
            ],

            [
                'group_parent_name' => 'reports',
                'group_name' => 'report_seven',
                'permissions' => [
                    'report_seven.create',
                    'report_seven.read',
                    'report_seven.print'
                ]
            ],

            [
                'group_parent_name' => 'reports',
                'group_name' => 'report_eight',
                'permissions' => [
                    'report_eight.create',
                    'report_eight.read',
                    'report_eight.print'
                ]
            ],

            [
                'group_parent_name' => 'reports',
                'group_name' => 'report_nine',
                'permissions' => [
                    'report_nine.create',
                    'report_nine.read',
                    'report_nine.print',
                    'report_nine.download',
                    'report_nine.export',
                ]
            ],
        
        ];


        // Create and Assign Permissions
        for ($i = 0; $i < count($permissions); $i++) {
            $permissionParentGroup = $permissions[$i]['group_parent_name'];
            $permissionGroup = $permissions[$i]['group_name'];
            for ($j = 0; $j < count($permissions[$i]['permissions']); $j++) {
                $permission = Permission::create(['name' => $permissions[$i]['permissions'][$j], 'group_parent_name' => $permissionParentGroup, 'group_name' => $permissionGroup, 'guard_name'=>'admin']);
            }
        }

        $roles_permission_data = [
            ['role_id'=>1,'permission_id'=>1],
            ['role_id'=>1,'permission_id'=>2],
            ['role_id'=>1,'permission_id'=>3],
            ['role_id'=>1,'permission_id'=>4],
            ['role_id'=>1,'permission_id'=>5],
            ['role_id'=>1,'permission_id'=>7],
            ['role_id'=>1,'permission_id'=>8],
            //['role_id'=>1,'permission_id'=>9],
            //['role_id'=>1,'permission_id'=>10],
            //['role_id'=>1,'permission_id'=>11],
            //['role_id'=>1,'permission_id'=>12],
            //['role_id'=>1,'permission_id'=>13],
            //['role_id'=>1,'permission_id'=>14],
            //['role_id'=>1,'permission_id'=>15],
            //['role_id'=>1,'permission_id'=>16],
            //['role_id'=>1,'permission_id'=>17],
            //['role_id'=>1,'permission_id'=>18],
            //['role_id'=>1,'permission_id'=>19],
            //['role_id'=>1,'permission_id'=>20],
            //['role_id'=>1,'permission_id'=>21],
        ];
        //DB::table('roles_permissions')->insert($roles_permission_data);
        //DB::insert("");

        $permissions = Permission::get();
        foreach ($permissions as $key => $value) {
            DB::insert("INSERT INTO `roles_permissions` (`role_id`,`permission_id`) VALUES (1, $value->id)");
        }
        foreach ($permissions as $key => $value) {
            DB::insert("INSERT INTO `roles_permissions` (`role_id`,`permission_id`) VALUES (7, $value->id)");
        }
        foreach ($permissions as $key => $value) {
            DB::insert("INSERT INTO `roles_permissions` (`role_id`,`permission_id`) VALUES (8, $value->id)");
        }
        foreach ($permissions as $key => $value) {
            DB::insert("INSERT INTO `roles_permissions` (`role_id`,`permission_id`) VALUES (9, $value->id)");
        }
        foreach ($permissions as $key => $value) {
            DB::insert("INSERT INTO `roles_permissions` (`role_id`,`permission_id`) VALUES (10, $value->id)");
        }
    }
}

