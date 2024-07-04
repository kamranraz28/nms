<?php

use App\Models\ActivityLog;
//use Spatie\Activitylog\Models\Activity;
use App\Helper\NumberToBanglaWord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Guest Route
Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\Guest\LanguageController@switchLang']);

Route::get('/division/{id?}', ['as'=>'get.division','uses'=>'App\Http\Controllers\Guest\AjaxController@getDivision']);
Route::get('/district/{id?}', ['as'=>'get.district','uses'=>'App\Http\Controllers\Guest\AjaxController@getDsitrict']);
Route::get('/upazila/{id?}', ['as'=>'get.upazila','uses'=>'App\Http\Controllers\Guest\AjaxController@getUpazila']);
Route::get('/upazila/self/{id?}', ['as'=>'get.upazila.self','uses'=>'App\Http\Controllers\Guest\AjaxController@getUpazilaSelf']);

Route::get('/forest-division/{id?}', ['as'=>'get.forest_division','uses'=>'App\Http\Controllers\Guest\AjaxController@getForestDivision']);
Route::get('/plant/{id?}', ['as'=>'get.plant','uses'=>'App\Http\Controllers\Guest\AjaxController@getPlant']);
Route::get('/plant2/{id?}', ['as'=>'get.plant2','uses'=>'App\Http\Controllers\Guest\AjaxController@getPlant2']);
Route::get('/plant3/{id?}', ['as'=>'get.plant3','uses'=>'App\Http\Controllers\Guest\AjaxController@getPlant3']);
Route::get('/forest-range/{id?}', ['as'=>'get.forest_range','uses'=>'App\Http\Controllers\Guest\AjaxController@getForestRange']);
Route::get('/forest-beat/{id?}', ['as'=>'get.forest_beat','uses'=>'App\Http\Controllers\Guest\AjaxController@getForestBeat']);
Route::get('/forest-beat/forest-division/{id?}', ['as'=>'get.forest_beat.forest_division','uses'=>'App\Http\Controllers\Guest\AjaxController@getForestBeatForestDivision']);
Route::get('/forest-range/self/{id?}', ['as'=>'get.forest_range.self','uses'=>'App\Http\Controllers\Guest\AjaxController@getForestRangeSelf']);
Route::get('/forest-beat/self/{id?}', ['as'=>'get.forest_beat.self','uses'=>'App\Http\Controllers\Guest\AjaxController@getForestBeatSelf']);
Route::get('/forest-beat-form-division/{id?}', ['as'=>'get.forest_beat_from_division','uses'=>'App\Http\Controllers\Guest\AjaxController@getForestBeatFromDivision']);

Route::get('/product/{id?}', ['as'=>'get.product','uses'=>'App\Http\Controllers\Guest\AjaxController@getProduct']);
Route::get('/products/{id?}', ['as'=>'get.products','uses'=>'App\Http\Controllers\Guest\AjaxController@getProducts']);
Route::get('/unit/{id?}', ['as'=>'get.unit','uses'=>'App\Http\Controllers\Guest\AjaxController@getUnit']);
Route::get('/nursery/{id?}', ['as'=>'get.nursery','uses'=>'App\Http\Controllers\Guest\AjaxController@getNursery']);
Route::get('/nursery1/{id?}', ['as'=>'get.nursery1','uses'=>'App\Http\Controllers\Guest\AjaxController@getNursery1']);
Route::get('/get-users', ['as'=>'get.users','uses'=>'App\Http\Controllers\Guest\AjaxController@getUsers']);
Route::get('/get-range-office/{id?}', ['as'=>'get.rangeOffice','uses'=>'App\Http\Controllers\Guest\AjaxController@rangeOffice']);



// this route for user panel not needed
Route::get('/test',function(){
    return NumberToBanglaWord::engToBn(123);
});


//Site Route
Route::get('/', ['as'=>'site.home','uses'=>'App\Http\Controllers\Site\SiteAllController@index']);

Route::group(['prefix'=>'pages'], function(){
    Route::get('{slug?}', ['as'=>'site.content','uses'=>'App\Http\Controllers\Site\SiteAllController@content']);
});

// User Auth Part
Auth::routes();
Route::get('/logout', ['middleware'=>'auth:web','as'=>'logout','uses'=>'App\Http\Controllers\Auth\LoginController@logout']);

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware'=>'auth', 'prefix'=>'user','as'=>'user.'], function() {
    Route::get('/dashboard', ['as'=>'dashboard','uses'=>'App\Http\Controllers\User\DashboardController@index']);
});


//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize:clear');
    return '<h1>Reoptimized class loader</h1>';
});

Route::get('/config-clear', function() {
    $exitCode = Artisan::call('config:clear');
    return '<h1>Config Clear loader</h1>';
});



//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear View cache:
Route::get('/storage-link', function() {
    $exitCode = Artisan::call('storage:link');
    return '<h1>Storage Link Created </h1>';
});

Route::get('/key-gen', function() {
    $exitCode = Artisan::call('key:generate');
    return '<h1>Key generated </h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

Route::get('/seed', function () {
Artisan::call('db:seed --class=RolePermissionSeeder');
Artisan::call('optimize:clear');
});



// Admin Auth Part

Route::group(['middleware'=>'guest:admin', 'prefix'=>'admin','as'=>'admin.'], function() {

    Route::get('/',function(){
        return redirect()->route('admin.login');
    });
    Route::get('/login', ['as'=>'login','uses'=>'App\Http\Controllers\Admin\AuthController@login']);
    Route::post('/login', ['as'=>'login','uses'=>'App\Http\Controllers\Admin\AuthController@loginStore']);

    Route::get('/password/reset', ['as'=>'password.request','uses'=>'App\Http\Controllers\Admin\AuthController@showLinkRequestForm']);
    Route::post('/password/reset', ['as'=>'password.email','uses'=>'App\Http\Controllers\Admin\AuthController@sendResetLinkEmail']);
    Route::post('/reset-password', ['as'=>'reset.password.post','uses'=>'App\Http\Controllers\Admin\AuthController@submitResetPasswordForm']);
    Route::get('/reset-password/{token}', ['as'=>'reset.password.get','uses'=>'App\Http\Controllers\Admin\AuthController@showResetPasswordForm']);

});
Route::group(['prefix'=>'admin','as'=>'admin.'], function() {
    Route::post('/logout', ['as'=>'logout','uses'=>'App\Http\Controllers\Admin\AuthController@logout']);
    Route::post('/change-password/{id?}', ['as'=>'change.password','uses'=>'App\Http\Controllers\Admin\AuthController@changePassword']);
});

Route::group(['middleware'=>'auth:admin', 'prefix'=>'admin','as'=>'admin.'], function() {

    Route::get('/log', function(){

        //return ActivityLog::inLog(['versions','users'])->get();
        //return ActivityLog::inLog('versions')->get();
        return $result = ActivityLog::get();
        return $result = ActivityLog::logNames(['versions','users'])->get();


        return ActivityLog::with('admin')->get();
        return ActivityLog::with('admin')->get();
        return ActivityLog::with('causer')->get()->first();
        return ActivityLog::with('admin')->get()->last();
    });

    Route::get('/dashboard', ['as'=>'dashboard','uses'=>'App\Http\Controllers\Admin\DashboardController@index']);
    Route::get('/dashboard-view', ['as'=>'dashboard.view','uses'=>'App\Http\Controllers\Admin\DashboardController@view']);
    Route::get('/dashboard-view-category/{id?}', ['as'=>'dashboard.view-category','uses'=>'App\Http\Controllers\Admin\DashboardController@viewcategory']);
    Route::get('/dashboard-view-category-range/{id1?}/{id?}', ['as'=>'dashboard.view-category-range','uses'=>'App\Http\Controllers\Admin\DashboardController@viewcategoryrange']);
    Route::get('/dashboard-view-category-bit/{id1?}/{id?}', ['as'=>'dashboard.view-category-bit','uses'=>'App\Http\Controllers\Admin\DashboardController@viewcategorybit']);
    Route::get('/yearl-total-stock/{id?}', ['as'=>'yearl_total_stock','uses'=>'App\Http\Controllers\Admin\DashboardController@yearl_total_stock']);
    Route::get('/dashboard-view-seedling/{id?}', ['as'=>'dashboard.view-seedling','uses'=>'App\Http\Controllers\Admin\DashboardController@viewseedling']);
    Route::get('/dashboard-view-seedling-range/{id1?}/{id?}', ['as'=>'dashboard.view-seedling-range','uses'=>'App\Http\Controllers\Admin\DashboardController@viewseedlingrange']);
    Route::get('/dashboard-view-seedling-bit/{id1?}/{id?}', ['as'=>'dashboard.view-seedling-bit','uses'=>'App\Http\Controllers\Admin\DashboardController@viewseedlingbit']);

    Route::group(['prefix'=>'role-permissions'], function(){
        //Role route
        Route::get('/role', ['as'=>'role','uses'=>'App\Http\Controllers\Admin\RoleController@index'])->middleware('can:read,App\Models\Role');

        Route::get('/role/create', ['as'=>'role.create','uses'=>'App\Http\Controllers\Admin\RoleController@create'])->middleware('can:create,App\Models\Role');

        Route::post('/role/store', ['as'=>'role.store','uses'=>'App\Http\Controllers\Admin\RoleController@store'])->middleware('can:create,App\Models\Role');

        Route::get('/role/edit/{id?}', ['as'=>'role.edit','uses'=>'App\Http\Controllers\Admin\RoleController@edit'])->middleware('can:update,App\Models\Role');

        Route::put('/role/update/{id?}', ['as'=>'role.update','uses'=>'App\Http\Controllers\Admin\RoleController@update'])->middleware('can:update,App\Models\Role');

        Route::get('/role/delete/{id?}/{sid?}', ['as'=>'role.delete','uses'=>'App\Http\Controllers\Admin\RoleController@delete'])->middleware('can:delete,App\Models\Role');
        Route::get('/role/permission/{id?}', ['as'=>'role.permission','uses'=>'App\Http\Controllers\Admin\RoleController@permission'])->middleware('can:permission_update,App\Models\Role');

        Route::put('/role/permission/update/{id?}', ['as'=>'role.permission.update','uses'=>'App\Http\Controllers\Admin\RoleController@permission_update'])->middleware('can:permission_update,App\Models\Role');

        //UserType route
        Route::get('/user-type', ['as'=>'user_type','uses'=>'App\Http\Controllers\Admin\UserTypeController@index'])->middleware('can:read,App\Models\UserType');

        Route::get('/user-type/create', ['as'=>'user_type.create','uses'=>'App\Http\Controllers\Admin\UserTypeController@create'])->middleware('can:create,App\Models\UserType');

        Route::post('/user-type/store', ['as'=>'user_type.store','uses'=>'App\Http\Controllers\Admin\UserTypeController@store'])->middleware('can:create,App\Models\UserType');

        Route::get('/user-type/edit/{id?}', ['as'=>'user_type.edit','uses'=>'App\Http\Controllers\Admin\UserTypeController@edit'])->middleware('can:update,App\Models\UserType');

        Route::put('/user-type/update/{id?}', ['as'=>'user_type.update','uses'=>'App\Http\Controllers\Admin\UserTypeController@update'])->middleware('can:update,App\Models\UserType');

        Route::get('/user-type/delete/{id?}/{sid?}', ['as'=>'user_type.delete','uses'=>'App\Http\Controllers\Admin\UserTypeController@delete'])->middleware('can:delete,App\Models\UserType');


        //SiteSetting route
        Route::get('/site-setting', ['as'=>'site_setting','uses'=>'App\Http\Controllers\Admin\SiteSettingController@index'])->middleware('can:read,App\Models\SiteSetting');

        Route::get('/site-setting/create', ['as'=>'site_setting.create','uses'=>'App\Http\Controllers\Admin\SiteSettingController@create'])->middleware('can:create,App\Models\SiteSetting');

        Route::post('/site-setting/store', ['as'=>'site_setting.store','uses'=>'App\Http\Controllers\Admin\SiteSettingController@store'])->middleware('can:create,App\Models\SiteSetting');

        Route::get('/site-setting/edit/{id?}', ['as'=>'site_setting.edit','uses'=>'App\Http\Controllers\Admin\SiteSettingController@edit'])->middleware('can:update,App\Models\SiteSetting');

        Route::put('/site-setting/update/{id?}', ['as'=>'site_setting.update','uses'=>'App\Http\Controllers\Admin\SiteSettingController@update'])->middleware('can:update,App\Models\SiteSetting');

        Route::get('/site-setting/delete/{id?}/{sid?}', ['as'=>'site_setting.delete','uses'=>'App\Http\Controllers\Admin\SiteSettingController@delete'])->middleware('can:delete,App\Models\SiteSetting');


        //Lang route
        Route::get('/lang', ['as'=>'lang','uses'=>'App\Http\Controllers\Admin\LangController@index'])->middleware('can:read,App\Models\Lang');

        Route::get('/lang/create', ['as'=>'lang.create','uses'=>'App\Http\Controllers\Admin\LangController@create'])->middleware('can:create,App\Models\Lang');

        Route::post('/lang/store', ['as'=>'lang.store','uses'=>'App\Http\Controllers\Admin\LangController@store'])->middleware('can:create,App\Models\Lang');

        Route::get('/lang/edit/{id?}', ['as'=>'lang.edit','uses'=>'App\Http\Controllers\Admin\LangController@edit'])->middleware('can:update,App\Models\Lang');

        Route::put('/lang/update/{id?}', ['as'=>'lang.update','uses'=>'App\Http\Controllers\Admin\LangController@update'])->middleware('can:update,App\Models\Lang');

        Route::get('/lang/delete/{id?}/{sid?}', ['as'=>'lang.delete','uses'=>'App\Http\Controllers\Admin\LangController@delete'])->middleware('can:delete,App\Models\Lang');


    });

    Route::group(['prefix'=>'locations'], function(){
        //State route
        Route::get('/state', ['as'=>'state','uses'=>'App\Http\Controllers\Admin\StateController@index'])->middleware('can:read,App\Models\State');

        Route::get('/state/create', ['as'=>'state.create','uses'=>'App\Http\Controllers\Admin\StateController@create'])->middleware('can:create,App\Models\State');

        Route::post('/state/store', ['as'=>'state.store','uses'=>'App\Http\Controllers\Admin\StateController@store'])->middleware('can:create,App\Models\State');

        Route::get('/state/edit/{id?}', ['as'=>'state.edit','uses'=>'App\Http\Controllers\Admin\StateController@edit'])->middleware('can:update,App\Models\State');

        Route::put('/state/update/{id?}', ['as'=>'state.update','uses'=>'App\Http\Controllers\Admin\StateController@update'])->middleware('can:update,App\Models\State');

        Route::get('/state/delete/{id?}/{sid?}', ['as'=>'state.delete','uses'=>'App\Http\Controllers\Admin\StateController@delete'])->middleware('can:delete,App\Models\State');
        
        //Division route
        Route::get('/division', ['as'=>'division','uses'=>'App\Http\Controllers\Admin\DivisionController@index'])->middleware('can:read,App\Models\Division');

        Route::get('/division/create', ['as'=>'division.create','uses'=>'App\Http\Controllers\Admin\DivisionController@create'])->middleware('can:create,App\Models\Division');

        Route::post('/division/store', ['as'=>'division.store','uses'=>'App\Http\Controllers\Admin\DivisionController@store'])->middleware('can:create,App\Models\Division');

        Route::get('/division/edit/{id?}', ['as'=>'division.edit','uses'=>'App\Http\Controllers\Admin\DivisionController@edit'])->middleware('can:update,App\Models\Division');

        Route::put('/division/update/{id?}', ['as'=>'division.update','uses'=>'App\Http\Controllers\Admin\DivisionController@update'])->middleware('can:update,App\Models\Division');

        Route::get('/division/delete/{id?}/{sid?}', ['as'=>'division.delete','uses'=>'App\Http\Controllers\Admin\DivisionController@delete'])->middleware('can:delete,App\Models\Division');
        
        //District route
        Route::get('/district', ['as'=>'district','uses'=>'App\Http\Controllers\Admin\DistrictController@index'])->middleware('can:read,App\Models\District');

        Route::get('/district/create', ['as'=>'district.create','uses'=>'App\Http\Controllers\Admin\DistrictController@create'])->middleware('can:create,App\Models\District');

        Route::post('/district/store', ['as'=>'district.store','uses'=>'App\Http\Controllers\Admin\DistrictController@store'])->middleware('can:create,App\Models\District');

        Route::get('/district/edit/{id?}', ['as'=>'district.edit','uses'=>'App\Http\Controllers\Admin\DistrictController@edit'])->middleware('can:update,App\Models\District');

        Route::put('/district/update/{id?}', ['as'=>'district.update','uses'=>'App\Http\Controllers\Admin\DistrictController@update'])->middleware('can:update,App\Models\District');

        Route::get('/district/delete/{id?}/{sid?}', ['as'=>'district.delete','uses'=>'App\Http\Controllers\Admin\DistrictController@delete'])->middleware('can:delete,App\Models\District');
        
        //Upazila route
        Route::get('/upazila', ['as'=>'upazila','uses'=>'App\Http\Controllers\Admin\UpazilaController@index'])->middleware('can:read,App\Models\Upazila');

        Route::get('/upazila/create', ['as'=>'upazila.create','uses'=>'App\Http\Controllers\Admin\UpazilaController@create'])->middleware('can:create,App\Models\Upazila');

        Route::post('/upazila/store', ['as'=>'upazila.store','uses'=>'App\Http\Controllers\Admin\UpazilaController@store'])->middleware('can:create,App\Models\Upazila');

        Route::get('/upazila/edit/{id?}', ['as'=>'upazila.edit','uses'=>'App\Http\Controllers\Admin\UpazilaController@edit'])->middleware('can:update,App\Models\Upazila');

        Route::put('/upazila/update/{id?}', ['as'=>'upazila.update','uses'=>'App\Http\Controllers\Admin\UpazilaController@update'])->middleware('can:update,App\Models\Upazila');

        Route::get('/upazila/delete/{id?}/{sid?}', ['as'=>'upazila.delete','uses'=>'App\Http\Controllers\Admin\UpazilaController@delete'])->middleware('can:delete,App\Models\Upazila');
    });


    Route::group(['prefix'=>'offices'], function(){
        //ForestState route
        Route::get('/forest-state', ['as'=>'forest_state','uses'=>'App\Http\Controllers\Admin\ForestStateController@index'])->middleware('can:read,App\Models\ForestState');

        Route::get('/forest-state/create', ['as'=>'forest_state.create','uses'=>'App\Http\Controllers\Admin\ForestStateController@create'])->middleware('can:create,App\Models\ForestState');

        Route::post('/forest-state/store', ['as'=>'forest_state.store','uses'=>'App\Http\Controllers\Admin\ForestStateController@store'])->middleware('can:create,App\Models\ForestState');

        Route::get('/forest-state/edit/{id?}', ['as'=>'forest_state.edit','uses'=>'App\Http\Controllers\Admin\ForestStateController@edit'])->middleware('can:update,App\Models\ForestState');

        Route::put('/forest-state/update/{id?}', ['as'=>'forest_state.update','uses'=>'App\Http\Controllers\Admin\ForestStateController@update'])->middleware('can:update,App\Models\ForestState');

        Route::get('/forest-state/delete/{id?}/{sid?}', ['as'=>'forest_state.delete','uses'=>'App\Http\Controllers\Admin\ForestStateController@delete'])->middleware('can:delete,App\Models\ForestState');
        
        //ForestDivision route
        Route::get('/forest-division', ['as'=>'forest_division','uses'=>'App\Http\Controllers\Admin\ForestDivisionController@index'])->middleware('can:read,App\Models\ForestDivision');

        Route::get('/forest-division/create', ['as'=>'forest_division.create','uses'=>'App\Http\Controllers\Admin\ForestDivisionController@create'])->middleware('can:create,App\Models\ForestDivision');

        Route::post('/forest-division/store', ['as'=>'forest_division.store','uses'=>'App\Http\Controllers\Admin\ForestDivisionController@store'])->middleware('can:create,App\Models\ForestDivision');

        Route::get('/forest-division/edit/{id?}', ['as'=>'forest_division.edit','uses'=>'App\Http\Controllers\Admin\ForestDivisionController@edit'])->middleware('can:update,App\Models\ForestDivision');

        Route::put('/forest-division/update/{id?}', ['as'=>'forest_division.update','uses'=>'App\Http\Controllers\Admin\ForestDivisionController@update'])->middleware('can:update,App\Models\ForestDivision');

        Route::get('/forest-division/delete/{id?}/{sid?}', ['as'=>'forest_division.delete','uses'=>'App\Http\Controllers\Admin\ForestDivisionController@delete'])->middleware('can:delete,App\Models\ForestDivision');
        
        //ForestRange route
        Route::get('/forest-range', ['as'=>'forest_range','uses'=>'App\Http\Controllers\Admin\ForestRangeController@index'])->middleware('can:read,App\Models\ForestRange');

        Route::get('/forest-range/create', ['as'=>'forest_range.create','uses'=>'App\Http\Controllers\Admin\ForestRangeController@create'])->middleware('can:create,App\Models\ForestRange');

        Route::post('/forest-range/store', ['as'=>'forest_range.store','uses'=>'App\Http\Controllers\Admin\ForestRangeController@store'])->middleware('can:create,App\Models\ForestRange');

        Route::get('/forest-range/edit/{id?}', ['as'=>'forest_range.edit','uses'=>'App\Http\Controllers\Admin\ForestRangeController@edit'])->middleware('can:update,App\Models\ForestRange');

        Route::put('/forest-range/update/{id?}', ['as'=>'forest_range.update','uses'=>'App\Http\Controllers\Admin\ForestRangeController@update'])->middleware('can:update,App\Models\ForestRange');

        Route::get('/forest-range/delete/{id?}/{sid?}', ['as'=>'forest_range.delete','uses'=>'App\Http\Controllers\Admin\ForestRangeController@delete'])->middleware('can:delete,App\Models\ForestRange');
        
        //ForestBeat route
        Route::get('/forest-beat', ['as'=>'forest_beat','uses'=>'App\Http\Controllers\Admin\ForestBeatController@index'])->middleware('can:read,App\Models\ForestBeat');

        Route::get('/forest-beat/create', ['as'=>'forest_beat.create','uses'=>'App\Http\Controllers\Admin\ForestBeatController@create'])->middleware('can:create,App\Models\ForestBeat');

        Route::post('/forest-beat/store', ['as'=>'forest_beat.store','uses'=>'App\Http\Controllers\Admin\ForestBeatController@store'])->middleware('can:create,App\Models\ForestBeat');

        Route::get('/forest-beat/edit/{id?}', ['as'=>'forest_beat.edit','uses'=>'App\Http\Controllers\Admin\ForestBeatController@edit'])->middleware('can:update,App\Models\ForestBeat');

        Route::put('/forest-beat/update/{id?}', ['as'=>'forest_beat.update','uses'=>'App\Http\Controllers\Admin\ForestBeatController@update'])->middleware('can:update,App\Models\ForestBeat');

        Route::get('/forest-beat/delete/{id?}/{sid?}', ['as'=>'forest_beat.delete','uses'=>'App\Http\Controllers\Admin\ForestBeatController@delete'])->middleware('can:delete,App\Models\ForestBeat');
    });

    Route::group(['prefix'=>'users'], function(){
        
        //User route
        Route::get('/user', ['as'=>'user','uses'=>'App\Http\Controllers\Admin\UserController@index'])->middleware('can:read,App\Models\User');
        Route::get('/user-export', ['as'=>'user.export','uses'=>'App\Http\Controllers\Admin\UserController@export']);
        Route::get('/user-pdf', ['as'=>'user.pdf','uses'=>'App\Http\Controllers\Admin\UserController@pdf']);

        Route::get('/user/datatable', ['as'=>'user.datatable','uses'=>'App\Http\Controllers\Admin\UserController@datatable'])->middleware('can:read,App\Models\User');

        Route::get('/user/create', ['as'=>'user.create','uses'=>'App\Http\Controllers\Admin\UserController@create'])->middleware('can:create,App\Models\User');

        Route::post('/user/store', ['as'=>'user.store','uses'=>'App\Http\Controllers\Admin\UserController@store'])->middleware('can:create,App\Models\User');

        Route::get('/user/edit/{id?}', ['as'=>'user.edit','uses'=>'App\Http\Controllers\Admin\UserController@edit'])->middleware('can:update,App\Models\User');

        Route::put('/user/update/{id?}', ['as'=>'user.update','uses'=>'App\Http\Controllers\Admin\UserController@update'])->middleware('can:update,App\Models\User');

        Route::get('/user/delete/{id?}/{sid?}', ['as'=>'user.delete','uses'=>'App\Http\Controllers\Admin\UserController@delete'])->middleware('can:delete,App\Models\User');


        //Admin route
        Route::get('/admin', ['as'=>'admin','uses'=>'App\Http\Controllers\Admin\AdminController@index'])->middleware('can:read,App\Models\Admin');
        Route::get('/admin-export', ['as'=>'admin.export','uses'=>'App\Http\Controllers\Admin\AdminController@export']);
        Route::get('/admin-pdf', ['as'=>'admin.pdf','uses'=>'App\Http\Controllers\Admin\AdminController@pdf']);

        Route::get('/admin/datatable', ['as'=>'admin.datatable','uses'=>'App\Http\Controllers\Admin\AdminController@datatable'])->middleware('can:read,App\Models\Admin');

        Route::get('/admin/create', ['as'=>'admin.create','uses'=>'App\Http\Controllers\Admin\AdminController@create'])->middleware('can:create,App\Models\Admin');

        Route::post('/admin/store', ['as'=>'admin.store','uses'=>'App\Http\Controllers\Admin\AdminController@store'])->middleware('can:create,App\Models\Admin');

        Route::get('/admin/edit/{id?}', ['as'=>'admin.edit','uses'=>'App\Http\Controllers\Admin\AdminController@edit'])->middleware('can:update,App\Models\Admin');

        Route::put('/admin/update/{id?}', ['as'=>'admin.update','uses'=>'App\Http\Controllers\Admin\AdminController@update'])->middleware('can:update,App\Models\Admin');

        Route::get('/admin/delete/{id?}/{sid?}', ['as'=>'admin.delete','uses'=>'App\Http\Controllers\Admin\AdminController@delete'])->middleware('can:delete,App\Models\Admin');

        //RangeOffice route
        Route::get('/range-office', ['as'=>'range_office','uses'=>'App\Http\Controllers\Admin\RangeOfficeController@index'])->middleware('can:read,App\Models\RangeOffice');
        Route::get('/range-office-export', ['as'=>'range_office.export','uses'=>'App\Http\Controllers\Admin\RangeOfficeController@export']);
        Route::get('/range-office-pdf', ['as'=>'range_office.pdf','uses'=>'App\Http\Controllers\Admin\RangeOfficeController@pdf']);


        Route::get('/range-office/create', ['as'=>'range_office.create','uses'=>'App\Http\Controllers\Admin\RangeOfficeController@create'])->middleware('can:create,App\Models\RangeOffice');

        Route::post('/range-office/store', ['as'=>'range_office.store','uses'=>'App\Http\Controllers\Admin\RangeOfficeController@store'])->middleware('can:create,App\Models\RangeOffice');

        Route::get('/range-office/edit/{id?}', ['as'=>'range_office.edit','uses'=>'App\Http\Controllers\Admin\RangeOfficeController@edit'])->middleware('can:update,App\Models\RangeOffice');

        Route::put('/range-office/update/{id?}', ['as'=>'range_office.update','uses'=>'App\Http\Controllers\Admin\RangeOfficeController@update'])->middleware('can:update,App\Models\RangeOffice');

        Route::get('/range-office/delete/{id?}/{sid?}', ['as'=>'range_office.delete','uses'=>'App\Http\Controllers\Admin\RangeOfficeController@delete'])->middleware('can:delete,App\Models\RangeOffice');


        //BeatOffice route
        Route::get('/beat-office', ['as'=>'beat_office','uses'=>'App\Http\Controllers\Admin\BeatOfficeController@index'])->middleware('can:read,App\Models\BeatOffice');
        Route::get('/beat-office-export', ['as'=>'beat_office.export','uses'=>'App\Http\Controllers\Admin\BeatOfficeController@export']);
        Route::get('/beat-office-pdf', ['as'=>'beat_office.pdf','uses'=>'App\Http\Controllers\Admin\BeatOfficeController@pdf']);

        Route::get('/beat-office/datatable', ['as'=>'beat_office.datatable','uses'=>'App\Http\Controllers\Admin\BeatOfficeController@datatable'])->middleware('can:read,App\Models\BeatOffice');

        Route::get('/beat-office/create', ['as'=>'beat_office.create','uses'=>'App\Http\Controllers\Admin\BeatOfficeController@create'])->middleware('can:create,App\Models\BeatOffice');

        Route::post('/beat-office/store', ['as'=>'beat_office.store','uses'=>'App\Http\Controllers\Admin\BeatOfficeController@store'])->middleware('can:create,App\Models\BeatOffice');

        Route::get('/beat-office/edit/{id?}', ['as'=>'beat_office.edit','uses'=>'App\Http\Controllers\Admin\BeatOfficeController@edit'])->middleware('can:update,App\Models\BeatOffice');

        Route::put('/beat-office/update/{id?}', ['as'=>'beat_office.update','uses'=>'App\Http\Controllers\Admin\BeatOfficeController@update'])->middleware('can:update,App\Models\BeatOffice');

        Route::get('/beat-office/delete/{id?}/{sid?}', ['as'=>'beat_office.delete','uses'=>'App\Http\Controllers\Admin\BeatOfficeController@delete'])->middleware('can:delete,App\Models\BeatOffice');


        //Nursery route
        Route::get('/nursery', ['as'=>'nursery','uses'=>'App\Http\Controllers\Admin\NurseryController@index'])->middleware('can:read,App\Models\Nursery');
        Route::get('/nursery-export', ['as'=>'nursery.export','uses'=>'App\Http\Controllers\Admin\NurseryController@export']);
        Route::get('/nursery-pdf', ['as'=>'nursery.pdf','uses'=>'App\Http\Controllers\Admin\NurseryController@pdf']);

        Route::get('/nursery/datatable', ['as'=>'nursery.datatable','uses'=>'App\Http\Controllers\Admin\NurseryController@datatable'])->middleware('can:read,App\Models\Nursery');

        Route::get('/nursery/create', ['as'=>'nursery.create','uses'=>'App\Http\Controllers\Admin\NurseryController@create'])->middleware('can:create,App\Models\Nursery');

        Route::post('/nursery/store', ['as'=>'nursery.store','uses'=>'App\Http\Controllers\Admin\NurseryController@store'])->middleware('can:create,App\Models\Nursery');

        Route::get('/nursery/edit/{id?}', ['as'=>'nursery.edit','uses'=>'App\Http\Controllers\Admin\NurseryController@edit'])->middleware('can:update,App\Models\Nursery');

        Route::put('/nursery/update/{id?}', ['as'=>'nursery.update','uses'=>'App\Http\Controllers\Admin\NurseryController@update'])->middleware('can:update,App\Models\Nursery');

        Route::get('/nursery/delete/{id?}/{sid?}', ['as'=>'nursery.delete','uses'=>'App\Http\Controllers\Admin\NurseryController@delete'])->middleware('can:delete,App\Models\Nursery');
     
        
    });
    Route::group(['prefix'=>'products'], function(){
        
        //Budget route
        Route::get('/budget', ['as'=>'budget','uses'=>'App\Http\Controllers\Admin\BudgetController@index'])->middleware('can:read,App\Models\Budget');

        Route::get('/budget/create', ['as'=>'budget.create','uses'=>'App\Http\Controllers\Admin\BudgetController@create'])->middleware('can:create,App\Models\Budget');

        Route::post('/budget/store', ['as'=>'budget.store','uses'=>'App\Http\Controllers\Admin\BudgetController@store'])->middleware('can:create,App\Models\Budget');

        Route::get('/budget/edit/{id?}', ['as'=>'budget.edit','uses'=>'App\Http\Controllers\Admin\BudgetController@edit'])->middleware('can:update,App\Models\Budget');

        Route::put('/budget/update/{id?}', ['as'=>'budget.update','uses'=>'App\Http\Controllers\Admin\BudgetController@update'])->middleware('can:update,App\Models\Budget');

        Route::get('/budget/delete/{id?}/{sid?}', ['as'=>'budget.delete','uses'=>'App\Http\Controllers\Admin\BudgetController@delete'])->middleware('can:delete,App\Models\Budget');

        
        //FinancialYear route
        Route::get('/financial-year', ['as'=>'financial_year','uses'=>'App\Http\Controllers\Admin\FinancialYearController@index'])->middleware('can:read,App\Models\FinancialYear');

        Route::get('/financial-year/create', ['as'=>'financial_year.create','uses'=>'App\Http\Controllers\Admin\FinancialYearController@create'])->middleware('can:create,App\Models\FinancialYear');

        Route::post('/financial-year/store', ['as'=>'financial_year.store','uses'=>'App\Http\Controllers\Admin\FinancialYearController@store'])->middleware('can:create,App\Models\FinancialYear');

        Route::get('/financial-year/edit/{id?}', ['as'=>'financial_year.edit','uses'=>'App\Http\Controllers\Admin\FinancialYearController@edit'])->middleware('can:update,App\Models\FinancialYear');

        Route::put('/financial-year/update/{id?}', ['as'=>'financial_year.update','uses'=>'App\Http\Controllers\Admin\FinancialYearController@update'])->middleware('can:update,App\Models\FinancialYear');

        Route::get('/financial-year/delete/{id?}/{sid?}', ['as'=>'financial_year.delete','uses'=>'App\Http\Controllers\Admin\FinancialYearController@delete'])->middleware('can:delete,App\Models\FinancialYear');
        

        //Color route
        Route::get('/color', ['as'=>'color','uses'=>'App\Http\Controllers\Admin\ColorController@index'])->middleware('can:read,App\Models\Color');

        Route::get('/color/create', ['as'=>'color.create','uses'=>'App\Http\Controllers\Admin\ColorController@create'])->middleware('can:create,App\Models\Color');

        Route::post('/color/store', ['as'=>'color.store','uses'=>'App\Http\Controllers\Admin\ColorController@store'])->middleware('can:create,App\Models\Color');

        Route::get('/color/edit/{id?}', ['as'=>'color.edit','uses'=>'App\Http\Controllers\Admin\ColorController@edit'])->middleware('can:update,App\Models\Color');

        Route::put('/color/update/{id?}', ['as'=>'color.update','uses'=>'App\Http\Controllers\Admin\ColorController@update'])->middleware('can:update,App\Models\Color');

        Route::get('/color/delete/{id?}/{sid?}', ['as'=>'color.delete','uses'=>'App\Http\Controllers\Admin\ColorController@delete'])->middleware('can:delete,App\Models\Color');

        //Unit route
        Route::get('/unit', ['as'=>'unit','uses'=>'App\Http\Controllers\Admin\UnitController@index'])->middleware('can:read,App\Models\Unit');

        Route::get('/unit/create', ['as'=>'unit.create','uses'=>'App\Http\Controllers\Admin\UnitController@create'])->middleware('can:create,App\Models\Unit');

        Route::post('/unit/store', ['as'=>'unit.store','uses'=>'App\Http\Controllers\Admin\UnitController@store'])->middleware('can:create,App\Models\Unit');

        Route::get('/unit/edit/{id?}', ['as'=>'unit.edit','uses'=>'App\Http\Controllers\Admin\UnitController@edit'])->middleware('can:update,App\Models\Unit');

        Route::put('/unit/update/{id?}', ['as'=>'unit.update','uses'=>'App\Http\Controllers\Admin\UnitController@update'])->middleware('can:update,App\Models\Unit');

        Route::get('/unit/delete/{id?}/{sid?}', ['as'=>'unit.delete','uses'=>'App\Http\Controllers\Admin\UnitController@delete'])->middleware('can:delete,App\Models\Unit');

        //Size route
        Route::get('/size', ['as'=>'size','uses'=>'App\Http\Controllers\Admin\SizeController@index'])->middleware('can:read,App\Models\Size');

        Route::get('/size/create', ['as'=>'size.create','uses'=>'App\Http\Controllers\Admin\SizeController@create'])->middleware('can:create,App\Models\Size');

        Route::post('/size/store', ['as'=>'size.store','uses'=>'App\Http\Controllers\Admin\SizeController@store'])->middleware('can:create,App\Models\Size');

        Route::get('/size/edit/{id?}', ['as'=>'size.edit','uses'=>'App\Http\Controllers\Admin\SizeController@edit'])->middleware('can:update,App\Models\Size');

        Route::put('/size/update/{id?}', ['as'=>'size.update','uses'=>'App\Http\Controllers\Admin\SizeController@update'])->middleware('can:update,App\Models\Size');

        Route::get('/size/delete/{id?}/{sid?}', ['as'=>'size.delete','uses'=>'App\Http\Controllers\Admin\SizeController@delete'])->middleware('can:delete,App\Models\Size');

        //Age route
        Route::get('/age', ['as'=>'age','uses'=>'App\Http\Controllers\Admin\AgeController@index'])->middleware('can:read,App\Models\Age');

        Route::get('/age/create', ['as'=>'age.create','uses'=>'App\Http\Controllers\Admin\AgeController@create'])->middleware('can:create,App\Models\Age');

        Route::post('/age/store', ['as'=>'age.store','uses'=>'App\Http\Controllers\Admin\AgeController@store'])->middleware('can:create,App\Models\Age');

        Route::get('/age/edit/{id?}', ['as'=>'age.edit','uses'=>'App\Http\Controllers\Admin\AgeController@edit'])->middleware('can:update,App\Models\Age');

        Route::put('/age/update/{id?}', ['as'=>'age.update','uses'=>'App\Http\Controllers\Admin\AgeController@update'])->middleware('can:update,App\Models\Age');

        Route::get('/age/delete/{id?}/{sid?}', ['as'=>'age.delete','uses'=>'App\Http\Controllers\Admin\AgeController@delete'])->middleware('can:delete,App\Models\Age');

        //Category route
        Route::get('/category', ['as'=>'category','uses'=>'App\Http\Controllers\Admin\CategoryController@index'])->middleware('can:read,App\Models\Category');

        Route::get('/category/create', ['as'=>'category.create','uses'=>'App\Http\Controllers\Admin\CategoryController@create'])->middleware('can:create,App\Models\Category');

        Route::post('/category/store', ['as'=>'category.store','uses'=>'App\Http\Controllers\Admin\CategoryController@store'])->middleware('can:create,App\Models\Category');

        Route::get('/category/edit/{id?}', ['as'=>'category.edit','uses'=>'App\Http\Controllers\Admin\CategoryController@edit'])->middleware('can:update,App\Models\Category');

        Route::put('/category/update/{id?}', ['as'=>'category.update','uses'=>'App\Http\Controllers\Admin\CategoryController@update'])->middleware('can:update,App\Models\Category');

        Route::get('/category/delete/{id?}/{sid?}', ['as'=>'category.delete','uses'=>'App\Http\Controllers\Admin\CategoryController@delete'])->middleware('can:delete,App\Models\Category');

        //Product route
        Route::get('/product', ['as'=>'product','uses'=>'App\Http\Controllers\Admin\ProductController@index'])->middleware('can:read,App\Models\Product');

        Route::get('/product/create', ['as'=>'product.create','uses'=>'App\Http\Controllers\Admin\ProductController@create'])->middleware('can:create,App\Models\Product');

        Route::post('/product/store', ['as'=>'product.store','uses'=>'App\Http\Controllers\Admin\ProductController@store'])->middleware('can:create,App\Models\Product');

        Route::get('/product/edit/{id?}', ['as'=>'product.edit','uses'=>'App\Http\Controllers\Admin\ProductController@edit'])->middleware('can:update,App\Models\Product');

        Route::put('/product/update/{id?}', ['as'=>'product.update','uses'=>'App\Http\Controllers\Admin\ProductController@update'])->middleware('can:update,App\Models\Product');

        Route::get('/product/delete/{id?}/{sid?}', ['as'=>'product.delete','uses'=>'App\Http\Controllers\Admin\ProductController@delete'])->middleware('can:delete,App\Models\Product');
    
        //StockType route
        Route::get('/stock_type', ['as'=>'stock_type','uses'=>'App\Http\Controllers\Admin\StockTypeController@index'])->middleware('can:read,App\Models\StockType');

        Route::get('/stock_type/create', ['as'=>'stock_type.create','uses'=>'App\Http\Controllers\Admin\StockTypeController@create'])->middleware('can:create,App\Models\StockType');

        Route::post('/stock_type/store', ['as'=>'stock_type.store','uses'=>'App\Http\Controllers\Admin\StockTypeController@store'])->middleware('can:create,App\Models\StockType');

        Route::get('/stock_type/edit/{id?}', ['as'=>'stock_type.edit','uses'=>'App\Http\Controllers\Admin\StockTypeController@edit'])->middleware('can:update,App\Models\StockType');

        Route::put('/stock_type/update/{id?}', ['as'=>'stock_type.update','uses'=>'App\Http\Controllers\Admin\StockTypeController@update'])->middleware('can:update,App\Models\StockType');

        Route::get('/stock_type/delete/{id?}/{sid?}', ['as'=>'stock_type.delete','uses'=>'App\Http\Controllers\Admin\StockTypeController@delete'])->middleware('can:delete,App\Models\StockType');

    });



    Route::group(['prefix'=>'pages'], function(){

        //Version route
        Route::get('/version', ['as'=>'version','uses'=>'App\Http\Controllers\Admin\VersionController@index'])->middleware('can:read,App\Models\Version');

        Route::get('/version/create', ['as'=>'version.create','uses'=>'App\Http\Controllers\Admin\VersionController@create'])->middleware('can:create,App\Models\Version');

        Route::post('/version/store', ['as'=>'version.store','uses'=>'App\Http\Controllers\Admin\VersionController@store'])->middleware('can:create,App\Models\Version');

        Route::get('/version/edit/{id?}', ['as'=>'version.edit','uses'=>'App\Http\Controllers\Admin\VersionController@edit'])->middleware('can:update,App\Models\Version');

        Route::put('/version/update/{id?}', ['as'=>'version.update','uses'=>'App\Http\Controllers\Admin\VersionController@update'])->middleware('can:update,App\Models\Version');

        Route::get('/version/delete/{id?}/{sid?}', ['as'=>'version.delete','uses'=>'App\Http\Controllers\Admin\VersionController@delete'])->middleware('can:delete,App\Models\Version');


        //ContentCategory route
        Route::get('/content-category', ['as'=>'content_category','uses'=>'App\Http\Controllers\Admin\ContentCategoryController@index'])->middleware('can:read,App\Models\ContentCategory');

        Route::get('/content-category/create', ['as'=>'content_category.create','uses'=>'App\Http\Controllers\Admin\ContentCategoryController@create'])->middleware('can:create,App\Models\ContentCategory');

        Route::post('/content-category/store', ['as'=>'content_category.store','uses'=>'App\Http\Controllers\Admin\ContentCategoryController@store'])->middleware('can:create,App\Models\ContentCategory');

        Route::get('/content-category/edit/{id?}', ['as'=>'content_category.edit','uses'=>'App\Http\Controllers\Admin\ContentCategoryController@edit'])->middleware('can:update,App\Models\ContentCategory');

        Route::put('/content-category/update/{id?}', ['as'=>'content_category.update','uses'=>'App\Http\Controllers\Admin\ContentCategoryController@update'])->middleware('can:update,App\Models\ContentCategory');

        Route::get('/content-category/delete/{id?}/{sid?}', ['as'=>'content_category.delete','uses'=>'App\Http\Controllers\Admin\ContentCategoryController@delete'])->middleware('can:delete,App\Models\ContentCategory');


        //Content route
        Route::get('/content', ['as'=>'content','uses'=>'App\Http\Controllers\Admin\ContentController@index'])->middleware('can:read,App\Models\Content');

        Route::get('/content/create', ['as'=>'content.create','uses'=>'App\Http\Controllers\Admin\ContentController@create'])->middleware('can:create,App\Models\Content');

        Route::post('/content/store', ['as'=>'content.store','uses'=>'App\Http\Controllers\Admin\ContentController@store'])->middleware('can:create,App\Models\Content');

        Route::get('/content/edit/{id?}', ['as'=>'content.edit','uses'=>'App\Http\Controllers\Admin\ContentController@edit'])->middleware('can:update,App\Models\Content');

        Route::put('/content/update/{id?}', ['as'=>'content.update','uses'=>'App\Http\Controllers\Admin\ContentController@update'])->middleware('can:update,App\Models\Content');

        Route::get('/content/delete/{id?}/{sid?}', ['as'=>'content.delete','uses'=>'App\Http\Controllers\Admin\ContentController@delete'])->middleware('can:delete,App\Models\Content');

        Route::get('/content/body-details/{id?}', ['as'=>'content.bodyDetails','uses'=>'App\Http\Controllers\Admin\ContentController@bodyDetails'])->middleware('can:create,App\Models\Content');

        Route::post('content-page/image-upload', ['as'=>'contentPage.imageUpload','uses'=>'App\Http\Controllers\Admin\ContentController@imageUpload']);
    });


    
    Route::group(['prefix'=>'inventory'], function(){
        //Purchase route
        Route::get('/purchase', ['as'=>'purchase','uses'=>'App\Http\Controllers\Admin\PurchaseController@index'])->middleware('can:read,App\Models\Purchase');

        Route::get('/purchase/print/{id?}', ['as'=>'purchase.print','uses'=>'App\Http\Controllers\Admin\PurchaseController@print'])->middleware('can:print,App\Models\Purchase');
        
        Route::get('/purchase/view/{id?}', ['as'=>'purchase.view','uses'=>'App\Http\Controllers\Admin\PurchaseController@view'])->middleware('can:view,App\Models\Purchase');
        Route::get('/purchase/approval/{id?}', ['as'=>'purchase.approval','uses'=>'App\Http\Controllers\Admin\PurchaseController@approval'])->middleware('can:approval,App\Models\Purchase');
        Route::get('/purchase/disapproval/{id?}', ['as'=>'purchase.disapproval','uses'=>'App\Http\Controllers\Admin\PurchaseController@disapproval'])->middleware('can:approval,App\Models\Purchase');
        Route::get('/purchase/disapproval_change/{id?}', ['as'=>'purchase.disapproval_change','uses'=>'App\Http\Controllers\Admin\PurchaseController@disapproval_change'])->middleware('can:approval,App\Models\Purchase');

        Route::get('/purchase/create', ['as'=>'purchase.create','uses'=>'App\Http\Controllers\Admin\PurchaseController@create'])->middleware('can:create,App\Models\Purchase');

        Route::post('/purchase/store', ['as'=>'purchase.store','uses'=>'App\Http\Controllers\Admin\PurchaseController@store'])->middleware('can:create,App\Models\Purchase');

        Route::get('/purchase/edit/{id?}', ['as'=>'purchase.edit','uses'=>'App\Http\Controllers\Admin\PurchaseController@edit'])->middleware('can:update,App\Models\Purchase');

        Route::put('/purchase/update/{id?}', ['as'=>'purchase.update','uses'=>'App\Http\Controllers\Admin\PurchaseController@update'])->middleware('can:update,App\Models\Purchase');

        Route::get('/purchase/delete/{id?}/{sid?}', ['as'=>'purchase.delete','uses'=>'App\Http\Controllers\Admin\PurchaseController@delete'])->middleware('can:delete,App\Models\Purchase');
        Route::get('/purchase-details/delete/{id?}', ['as'=>'purchase_details.delete','uses'=>'App\Http\Controllers\Admin\PurchaseController@deletepurchaseDetails'])->middleware('can:delete_purchase_details,App\Models\Purchase');
                
        //Sale route
        Route::get('/sale', ['as'=>'sale','uses'=>'App\Http\Controllers\Admin\SaleController@index'])->middleware('can:read,App\Models\Sale');

        Route::get('/sale/print/{id?}', ['as'=>'sale.print','uses'=>'App\Http\Controllers\Admin\SaleController@print'])->middleware('can:print,App\Models\Sale');
        
        Route::get('/sale/view/{id?}', ['as'=>'sale.view','uses'=>'App\Http\Controllers\Admin\SaleController@view'])->middleware('can:view,App\Models\Sale');
        Route::get('/sale/approval/{id?}', ['as'=>'sale.approval','uses'=>'App\Http\Controllers\Admin\SaleController@approval'])->middleware('can:approval,App\Models\Sale');

        Route::get('/sale/disapproval/{id?}', ['as'=>'sale.disapproval','uses'=>'App\Http\Controllers\Admin\SaleController@disapproval'])->middleware('can:approval,App\Models\Sale');
        Route::get('/sale/disapproval_change/{id?}', ['as'=>'sale.disapproval_change','uses'=>'App\Http\Controllers\Admin\SaleController@disapproval_change'])->middleware('can:approval,App\Models\Sale');

        Route::get('/sale/create', ['as'=>'sale.create','uses'=>'App\Http\Controllers\Admin\SaleController@create'])->middleware('can:create,App\Models\Sale');

        Route::post('/sale/store', ['as'=>'sale.store','uses'=>'App\Http\Controllers\Admin\SaleController@store'])->middleware('can:create,App\Models\Sale');

        Route::get('/sale/edit/{id?}', ['as'=>'sale.edit','uses'=>'App\Http\Controllers\Admin\SaleController@edit'])->middleware('can:update,App\Models\Sale');

        Route::put('/sale/update/{id?}', ['as'=>'sale.update','uses'=>'App\Http\Controllers\Admin\SaleController@update'])->middleware('can:update,App\Models\Sale');

        Route::get('/sale/delete/{id?}/{sid?}', ['as'=>'sale.delete','uses'=>'App\Http\Controllers\Admin\SaleController@delete'])->middleware('can:delete,App\Models\Sale');
        Route::get('/sale-details/delete/{id?}', ['as'=>'sale_details.delete','uses'=>'App\Http\Controllers\Admin\SaleController@deletesaleDetails'])->middleware('can:delete_sale_details,App\Models\Sale');
        
    });
    
    Route::group(['prefix'=>'reports'], function(){
        //ReportOne route
        Route::get('/report-one', ['as'=>'report_one','uses'=>'App\Http\Controllers\Admin\ReportOneController@index'])->middleware('can:read,App\Models\ReportOne');
        Route::post('/report-one/store', ['as'=>'report_one.store','uses'=>'App\Http\Controllers\Admin\ReportOneController@store'])->middleware('can:create,App\Models\ReportOne');
        Route::get('/report-one/print/{id?}/{sid?}', ['as'=>'report_one.print','uses'=>'App\Http\Controllers\Admin\ReportOneController@print'])->middleware('can:print,App\Models\ReportOne');
        Route::get('/report-one/download', ['as'=>'report_one.download','uses'=>'App\Http\Controllers\Admin\ReportOneController@download'])->middleware('can:print,App\Models\ReportOne');


        //ReportTwo route
        Route::get('/report-two', ['as'=>'report_two','uses'=>'App\Http\Controllers\Admin\ReportTwoController@index'])->middleware('can:read,App\Models\ReportTwo');
        Route::post('/report-two/store', ['as'=>'report_two.store','uses'=>'App\Http\Controllers\Admin\ReportTwoController@store'])->middleware('can:create,App\Models\ReportTwo');
        Route::get('/report-two/print/{id?}/{sid?}', ['as'=>'report_two.print','uses'=>'App\Http\Controllers\Admin\ReportTwoController@print'])->middleware('can:print,App\Models\ReportTwo');
        Route::get('/report-two/download', ['as'=>'report_two.download','uses'=>'App\Http\Controllers\Admin\ReportTwoController@download'])->middleware('can:print,App\Models\ReportTwo');

        //ReportThree route
        Route::get('/report-three', ['as'=>'report_three','uses'=>'App\Http\Controllers\Admin\ReportThreeController@index'])->middleware('can:read,App\Models\ReportThree');
        Route::post('/report-three/store', ['as'=>'report_three.store','uses'=>'App\Http\Controllers\Admin\ReportThreeController@store'])->middleware('can:create,App\Models\ReportThree');
        Route::get('/report-three/print/{id?}/{sid?}', ['as'=>'report_three.print','uses'=>'App\Http\Controllers\Admin\ReportThreeController@print'])->middleware('can:print,App\Models\ReportThree');
        Route::get('/report-three/download', ['as'=>'report_three.download','uses'=>'App\Http\Controllers\Admin\ReportThreeController@download'])->middleware('can:print,App\Models\ReportThree');


        //ReportFour route
        Route::get('/report-four', ['as'=>'report_four','uses'=>'App\Http\Controllers\Admin\ReportFourController@index'])->middleware('can:read,App\Models\ReportFour');
        Route::post('/report-four/store', ['as'=>'report_four.store','uses'=>'App\Http\Controllers\Admin\ReportFourController@store'])->middleware('can:create,App\Models\ReportFour');
        Route::get('/report-four/print/{id?}/{sid?}', ['as'=>'report_four.print','uses'=>'App\Http\Controllers\Admin\ReportFourController@print'])->middleware('can:print,App\Models\ReportFour');
        
        //ReportFive route
        Route::get('/report-five', ['as'=>'report_five','uses'=>'App\Http\Controllers\Admin\ReportFiveController@index'])->middleware('can:read,App\Models\ReportFive');
        Route::post('/report-five/store', ['as'=>'report_five.store','uses'=>'App\Http\Controllers\Admin\ReportFiveController@store'])->middleware('can:create,App\Models\ReportFive');
        Route::get('/report-five/print/{id?}/{sid?}', ['as'=>'report_five.print','uses'=>'App\Http\Controllers\Admin\ReportFiveController@print'])->middleware('can:print,App\Models\ReportFive');
        Route::get('/report-five/download', ['as'=>'report_five.download','uses'=>'App\Http\Controllers\Admin\ReportFiveController@download'])->middleware('can:print,App\Models\ReportFive');


        //ReportSix route
        Route::get('/report-six', ['as'=>'report_six','uses'=>'App\Http\Controllers\Admin\ReportSixController@index'])->middleware('can:read,App\Models\ReportSix');
        Route::post('/report-six/store', ['as'=>'report_six.store','uses'=>'App\Http\Controllers\Admin\ReportSixController@store'])->middleware('can:create,App\Models\ReportSix');
        Route::get('/report-six/print/{id?}/{sid?}', ['as'=>'report_six.print','uses'=>'App\Http\Controllers\Admin\ReportSixController@print'])->middleware('can:print,App\Models\ReportSix');
        Route::get('/report-six/download', ['as'=>'report_six.download','uses'=>'App\Http\Controllers\Admin\ReportSixController@download'])->middleware('can:print,App\Models\ReportSix');


        //ReportSeven route
        Route::get('/report-seven', ['as'=>'report_seven','uses'=>'App\Http\Controllers\Admin\ReportSevenController@index'])->middleware('can:read,App\Models\ReportSeven');
        Route::post('/report-seven/store', ['as'=>'report_seven.store','uses'=>'App\Http\Controllers\Admin\ReportSevenController@store'])->middleware('can:create,App\Models\ReportSeven');
        Route::get('/report-seven/print/{id?}/{sid?}', ['as'=>'report_seven.print','uses'=>'App\Http\Controllers\Admin\ReportSevenController@print'])->middleware('can:print,App\Models\ReportSeven');
        Route::get('/report-seven/download', ['as'=>'report_seven.download','uses'=>'App\Http\Controllers\Admin\ReportSevenController@download'])->middleware('can:print,App\Models\ReportSeven');


        //ReportEight route
        Route::get('/report-eight', ['as'=>'report_eight','uses'=>'App\Http\Controllers\Admin\ReportEightController@index'])->middleware('can:read,App\Models\ReportEight');
        Route::post('/report-eight/store', ['as'=>'report_eight.store','uses'=>'App\Http\Controllers\Admin\ReportEightController@store'])->middleware('can:create,App\Models\ReportEight');
        Route::get('/report-eight/print/{id?}/{sid?}', ['as'=>'report_eight.print','uses'=>'App\Http\Controllers\Admin\ReportEightController@print'])->middleware('can:print,App\Models\ReportEight');
        Route::get('/report-eight/download', ['as'=>'report_eight.download','uses'=>'App\Http\Controllers\Admin\ReportEightController@download'])->middleware('can:print,App\Models\ReportEight');


        //ReportNine route
        Route::get('/report-nine', ['as'=>'report_nine','uses'=>'App\Http\Controllers\Admin\ReportNineController@index'])->middleware('can:read,App\Models\ReportNine');
        Route::post('/report-nine/store', ['as'=>'report_nine.store','uses'=>'App\Http\Controllers\Admin\ReportNineController@store'])->middleware('can:create,App\Models\ReportNine');
        Route::get('/report-nine/print', ['as'=>'report_nine.print','uses'=>'App\Http\Controllers\Admin\ReportNineController@print'])->middleware('can:print,App\Models\ReportNine');
        Route::get('/report-nine/download', ['as'=>'report_nine.download','uses'=>'App\Http\Controllers\Admin\ReportNineController@download'])->middleware('can:download,App\Models\ReportNine');

    });

});

Route::post('/purchaseStore', ['as'=>'purchaseStore','uses'=>'App\Http\Controllers\Admin\PurchaseController@disapproval']);
Route::post('/purchaseStore_change', ['as'=>'purchaseStore_change','uses'=>'App\Http\Controllers\Admin\PurchaseController@disapproval_change']);


Route::post('/saleStore', ['as'=>'saleStore','uses'=>'App\Http\Controllers\Admin\SaleController@disapproval']);
Route::post('/saleStore_change', ['as'=>'saleStore_change','uses'=>'App\Http\Controllers\Admin\SaleController@disapproval_change']);




