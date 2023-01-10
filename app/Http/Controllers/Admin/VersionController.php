<?php

namespace App\Http\Controllers\Admin;
use App\Models\Version;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;


class VersionController extends Controller
{
  const VIEW_PATH = 'admin.version.';
  public function __construct()
  {

  }

  public function index()
  {
    $this->authorize('read',Version::class);
    $versions = Version::where(['status'=>1])->orderBy('sort','asc')->get();
    return view(self::VIEW_PATH . 'index',compact('versions'));
  }

  public function create()
  {
    $this->authorize('create',Version::class);
    return view(self::VIEW_PATH . 'add_edit');
  }

  public function store(Request $request)
  {
    $this->authorize('create',Version::class);
    //return $request->all();
    //dd($request->all());
    $this->validate($request, [
        //'thumb' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:20000',
        //'thumb' => 'required',
        'sort' => 'required',
        'name' => 'required|min:1|max:128'
    ]);

    $data = $request->except('_token');
    $authUser = Auth::guard('admin')->user()->id;
    array_walk_recursive($data, function (&$val) {
        $val = trim($val);
        $val = is_string($val) && $val === '' ? null : $val;
    });

    $data['created_by'] = $authUser;

    if ($request->hasFile('thumb')) {
      $thumb = $request->file('thumb');
      $randNumber = rand(1, 999);
      $name = 'thumb-' . $authUser . '-' . time() . $randNumber . '.' . $thumb->getClientOriginalExtension();
      $year = date('Y/');
      $month = date('F/');
      $destinationPath = storage_path('app/public/version/' . $year . $month);
      $thumb->move($destinationPath, $name);
      $filePath = 'version/' . $year . $month;
      $data['thumb'] = $filePath . '' . $name;
    }

    Version::create($data);
    Cache::forget('content_categories');

    return redirect()->route('admin.version')->with([
                    'message' => __('admin.common.success'),
                    'alert-type' => 'success'
                ]);
  }

  public function edit(Request $request, $id)
  {
    $this->authorize('update',Version::class);
    $version = Version::where(['id'=>$id])->first();
    return view(self::VIEW_PATH . 'add_edit', compact('version'));
  }

  public function update(Request $request, $id)
  {
    $this->authorize('update',Version::class);
    //dd($request->all());
    $this->validate($request, [
      //'thumb' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:20000',
      //'thumb' => 'required',
      'sort' => 'required',
      'name' => 'required|min:1|max:128'
  ]);

    $version = Version::where(['id'=>$id])->first();
    if ( is_null($version) == true) {
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

    if ($request->hasFile('thumb')) {
      @unlink(storage_path('/app/public/' . $version->thumb));
      $thumb = $request->file('thumb');
      $randNumber = rand(1, 999);
      $name = 'thumb-' . $authUser . '-' . time() . $randNumber . '.' . $thumb->getClientOriginalExtension();
      $year = date('Y/');
      $month = date('F/');
      $destinationPath = storage_path('app/public/version/' . $year . $month);
      $thumb->move($destinationPath, $name);
      $filePath = 'version/' . $year . $month;
      $data['thumb'] = $filePath . '' . $name;
    }

    Version::find($id)->update($data);
    Cache::forget('content_categories');

    return redirect()->route('admin.version')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);

  }

  public function delete(Request $request, $id)
  {
    $this->authorize('delete',Version::class);
    $version = Version::find($id);
    if ( is_null($version) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    @unlink(storage_path('/app/public/' . $version->thumb));
    $version->delete();
    Cache::forget('content_categories');

    return redirect()->route('admin.version')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }


}
