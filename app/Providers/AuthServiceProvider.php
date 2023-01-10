<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;



class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        App\Models\Role::class => App\Policies\RolePolicy::class,
        App\Models\UserType::class => App\Policies\UserTypePolicy::class,
        App\Models\User::class => App\Policies\UserPolicy::class,
        App\Models\Admin::class => App\Policies\AdminPolicy::class,
        App\Models\SiteSetting::class => App\Policies\SiteSettingPolicy::class,
        App\Models\Lang::class => App\Policies\LangPolicy::class,
        App\Models\Version::class => App\Policies\VersionPolicy::class,

        App\Models\ContentCategory::class => App\Policies\ContentCategoryPolicy::class,
        App\Models\Content::class => App\Policies\ContentPolicy::class,

        App\Models\Budget::class => App\Policies\BudgetPolicy::class,
        App\Models\FinancialYear::class => App\Policies\FinancialYearPolicy::class,
        App\Models\Color::class => App\Policies\ColorPolicy::class,
        App\Models\Unit::class => App\Policies\UnitPolicy::class,
        App\Models\Size::class => App\Policies\SizePolicy::class,
        App\Models\Age::class => App\Policies\AgePolicy::class,
        App\Models\Category::class => App\Policies\CategoryPolicy::class,
        App\Models\Product::class => App\Policies\ProductPolicy::class,
        App\Models\Nursery::class => App\Policies\NurseryPolicy::class,
        App\Models\RangeOffice::class => App\Policies\RangeOfficePolicy::class,
        App\Models\BeatOffice::class => App\Policies\BeatOfficePolicy::class,
        App\Models\State::class => App\Policies\StatePolicy::class,
        App\Models\Division::class => App\Policies\DivisionPolicy::class,
        App\Models\District::class => App\Policies\DistrictPolicy::class,
        App\Models\Upazila::class => App\Policies\UpazilaPolicy::class,

        App\Models\ForestState::class => App\Policies\ForestStatePolicy::class,
        App\Models\ForestDivision::class => App\Policies\ForestDivisionPolicy::class,
        App\Models\ForestRange::class => App\Policies\ForestRangePolicy::class,
        App\Models\ForestBeat::class => App\Policies\ForestBeatPolicy::class,


        App\Models\StockType::class => App\Policies\StockTypePolicy::class,
        App\Models\Purchase::class => App\Policies\PurchasePolicy::class,
        App\Models\Sale::class => App\Policies\SalePolicy::class,

        App\Models\ReportOne::class => App\Policies\ReportOnePolicy::class,
        App\Models\ReportTwo::class => App\Policies\ReportTwoPolicy::class,
        App\Models\ReportThree::class => App\Policies\ReportThreePolicy::class,
        App\Models\ReportFour::class => App\Policies\ReportFourPolicy::class,
        App\Models\ReportFive::class => App\Policies\ReportFivePolicy::class,
        App\Models\ReportSix::class => App\Policies\ReportSixPolicy::class,
        App\Models\ReportSeven::class => App\Policies\ReportSevenPolicy::class,
        App\Models\ReportEight::class => App\Policies\ReportEightPolicy::class,
        App\Models\ReportNine::class => App\Policies\ReportNinePolicy::class,
    ];

    /*protected $policies = [
        Post::class => PostPolicy::class,
    ];*/

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
   
        /*Gate::define('isSuperAdmin', function($user) {
           return $user->role == 'superadmin';
        });
        Gate::define('isAdmin', function($user) {
           return $user->role == 'admin';
        });
        Gate::define('isManager', function($user) {
            return $user->role == 'manager';
        });
        Gate::define('isUser', function($user) {
            return $user->role == 'user';
        });*/
    }
}
