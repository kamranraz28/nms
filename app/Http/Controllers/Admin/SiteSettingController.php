<?php

namespace App\Http\Controllers\Admin;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;


class SiteSettingController extends Controller
{
  const VIEW_PATH = 'admin.site_setting.';
  public function __construct()
  {

  }

  public function index()
  {
    $this->authorize('read',SiteSetting::class);
    $site_settings = SiteSetting::where(['status'=>1])->orderBy('sort','asc')->get();
    return view(self::VIEW_PATH . 'index',compact('site_settings'));
  }

  public function create()
  {
    $this->authorize('create',SiteSetting::class);
    return view(self::VIEW_PATH . 'add_edit');
  }

  public function store(Request $request)
  {
    $this->authorize('create',SiteSetting::class);
    //return $request->all();
    $this->validate($request, [
      'favicon' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:20000',
      'favicon' => 'required',
      'logo' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:20000',
      'logo' => 'required',
      'footer_logo' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:20000',
      'footer_logo' => 'required',
      //'sort' => 'required',
      'title_en' => 'required|min:1|max:512',
      'title_bn' => 'required|min:1|max:512',
      'footer_en' => 'required|min:1|max:1024',
      'footer_bn' => 'required|min:1|max:1024',
      'site_address_en' => 'required|min:1|max:1024',
      'site_address_bn' => 'required|min:1|max:1024',
      'site_email' => 'required',
      'site_mobile' => 'required',
    ]);

    $data = $request->except('_token');
    $authUser = Auth::guard('admin')->user()->id;
    array_walk_recursive($data, function (&$val) {
        $val = trim($val);
        $val = is_string($val) && $val === '' ? null : $val;
    });

    $data['created_by'] = $authUser;

    if ($request->hasFile('favicon')) {
      $favicon = $request->file('favicon');
      $randNumber = rand(1, 999);
      $name = 'favicon-' . $authUser . '-' . time() . $randNumber . '.' . $favicon->getClientOriginalExtension();
      $year = date('Y/');
      $month = date('F/');
      $destinationPath = storage_path('app/public/site_setting/' . $year . $month);
      $favicon->move($destinationPath, $name);
      $filePath = 'site_setting/' . $year . $month;
      $data['favicon'] = $filePath . '' . $name;
    }

    if ($request->hasFile('logo')) {
      $logo = $request->file('logo');
      $randNumber = rand(1, 999);
      $name = 'logo-' . $authUser . '-' . time() . $randNumber . '.' . $logo->getClientOriginalExtension();
      $year = date('Y/');
      $month = date('F/');
      $destinationPath = storage_path('app/public/site_setting/' . $year . $month);
      $logo->move($destinationPath, $name);
      $filePath = 'site_setting/' . $year . $month;
      $data['logo'] = $filePath . '' . $name;
    }

    if ($request->hasFile('footer_logo')) {
      $footer_logo = $request->file('footer_logo');
      $randNumber = rand(1, 999);
      $name = 'footer_logo-' . $authUser . '-' . time() . $randNumber . '.' . $footer_logo->getClientOriginalExtension();
      $year = date('Y/');
      $month = date('F/');
      $destinationPath = storage_path('app/public/site_setting/' . $year . $month);
      $footer_logo->move($destinationPath, $name);
      $filePath = 'site_setting/' . $year . $month;
      $data['footer_logo'] = $filePath . '' . $name;
    }
    SiteSetting::create($data);

    Cache::forget('sitesetting');
    
    return redirect()->route('admin.site_setting')->with([
                    'message' => __('admin.common.success'),
                    'alert-type' => 'success'
                ]);
  }

  public function edit(Request $request, $id)
  {
    $this->authorize('update',SiteSetting::class);
    $site_setting = SiteSetting::where(['id'=>$id])->first();
    return view(self::VIEW_PATH . 'add_edit', compact('site_setting'));
  }

  public function update(Request $request, $id)
  {
    $this->authorize('update',SiteSetting::class);
    //dd($request->all());
    $this->validate($request, [
      'favicon' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:20000',
      //'favicon' => 'required',
      'logo' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:20000',
      //'logo' => 'required',
      //'favicon' => 'required',
      'footer_logo' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:20000',
      //'footer_logo' => 'required',
      //'sort' => 'required',
      'title_en' => 'required|min:1|max:512',
      'title_bn' => 'required|min:1|max:512',
      'footer_en' => 'required|min:1|max:1024',
      'footer_bn' => 'required|min:1|max:1024',
      'site_address_en' => 'required|min:1|max:1024',
      'site_address_bn' => 'required|min:1|max:1024',
      'site_email' => 'required',
      'site_mobile' => 'required',
    ]);

    $site_setting = SiteSetting::where(['id'=>$id])->first();
    if ( is_null($site_setting) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }
    $data = $request->except('_token');
    $authUser = Auth::guard('admin')->user()->id;
    array_walk_recursive($data, function (&$val) {
        $val = trim($val);
        $val = is_string($val) && $val === '' ? null : $val;
    });

    $data['updated_by'] = $authUser;

    //dd($data);

    if ($request->hasFile('favicon')) {
      @unlink(storage_path('/app/public/' . $site_setting->favicon));
      $favicon = $request->file('favicon');
      $randNumber = rand(1, 999);
      $name = 'favicon-' . $authUser . '-' . time() . $randNumber . '.' . $favicon->getClientOriginalExtension();
      $year = date('Y/');
      $month = date('F/');
      $destinationPath = storage_path('app/public/site_setting/' . $year . $month);
      $favicon->move($destinationPath, $name);
      $filePath = 'site_setting/' . $year . $month;
      $data['favicon'] = $filePath . '' . $name;
    }

    if ($request->hasFile('logo')) {
      @unlink(storage_path('/app/public/' . $site_setting->logo));
      $logo = $request->file('logo');
      $randNumber = rand(1, 999);
      $name = 'logo-' . $authUser . '-' . time() . $randNumber . '.' . $logo->getClientOriginalExtension();
      $year = date('Y/');
      $month = date('F/');
      $destinationPath = storage_path('app/public/site_setting/' . $year . $month);
      $logo->move($destinationPath, $name);
      $filePath = 'site_setting/' . $year . $month;
      $data['logo'] = $filePath . '' . $name;
    }

    if ($request->hasFile('footer_logo')) {
      @unlink(storage_path('/app/public/' . $site_setting->footer_logo));
      $footer_logo = $request->file('footer_logo');
      $randNumber = rand(1, 999);
      $name = 'footer_logo-' . $authUser . '-' . time() . $randNumber . '.' . $footer_logo->getClientOriginalExtension();
      $year = date('Y/');
      $month = date('F/');
      $destinationPath = storage_path('app/public/site_setting/' . $year . $month);
      $footer_logo->move($destinationPath, $name);
      $filePath = 'site_setting/' . $year . $month;
      $data['footer_logo'] = $filePath . '' . $name;
    }

    SiteSetting::find($id)->update($data);
    Cache::forget('sitesetting');
    return redirect()->route('admin.site_setting')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);

  }

  public function delete(Request $request, $id)
  {
    //dd($id);
    $this->authorize('delete',SiteSetting::class);
    $site_setting = SiteSetting::find($id);
    if ( is_null($site_setting) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    @unlink(storage_path('/app/public/' . $site_setting->favicon));
    @unlink(storage_path('/app/public/' . $site_setting->logo));
    $site_setting->delete();
    Cache::forget('sitesetting');

    return redirect()->route('admin.site_setting')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }


}
