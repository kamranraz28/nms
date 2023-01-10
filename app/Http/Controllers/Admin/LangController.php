<?php

namespace App\Http\Controllers\Admin;
use App\Models\Lang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;

class LangController extends Controller
{
  const VIEW_PATH = 'admin.lang.';
  public function __construct()
  {

  }

  public function index()
  {
    $this->authorize('read',Lang::class);
    
    // bn lang insert into lag table
    // $data = [];
    // $langs = Lang::get('admin');
    // foreach ($langs as $key => $value) {
    //     $data['page'] = $key;
    //     foreach ($value as $key1 => $value1) {
    //     DB::table('langs')->where('key', $key1)->update(['lang_2' => $value1]);
    //     }
    // }
    // bn lang insert into lag table


    $langs = Lang::get();
    return view(self::VIEW_PATH . 'index',compact('langs'));
  }

  public function create()
  {
    $this->authorize('create',App\Lang::class);
    return view(self::VIEW_PATH . 'add_edit');
  }

  public function store(Request $request)
  {
    $this->authorize('create',App\Lang::class);
    //return $request->all();
    $this->validate($request, [
        //'name' => 'required|min:1|max:128',
        'lang_1' => 'required|min:1|max:128',
        'lang_2' => 'required|min:1|max:128',
    ]);

    try {
      $lang = DB::table('langs')->orderBy('id','DESC')->first();

      $data = $request->except('_token');
      $authUser = Auth::guard('admin')->user()->id;
      array_walk_recursive($data, function (&$val) {
          $val = trim($val);
          $val = is_string($val) && $val === '' ? null : $val;
      });

      if ($lang) {
        $data['code'] = $lang->code + 1;
      } else {
        $data['code'] = 1;
      }
      
      $data['created_by'] = $authUser;
      Lang::create($data);
      Cache::forget('langBnDatas');
      Cache::forget('langEnDatas');
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }

    

    return redirect()->route('admin.lang')->with([
      'messlang' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

  public function edit(Request $request, $id)
  {
    $this->authorize('update',App\Lang::class);
    $lang = Lang::where(['id'=>$id])->first();
    if ( is_null($lang) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }
    return view(self::VIEW_PATH . 'add_edit', compact('lang'));
  }

  public function update(Request $request, $id)
  {
    $this->authorize('update',App\Lang::class);
    //dd($request->all());
    $this->validate($request, [
      //'name' => 'required|min:1|max:128',
      'lang_1' => 'required|min:1|max:128',
      'lang_2' => 'required|min:1|max:128',
    ]);

    try {
      $lang = Lang::where(['id'=>$id])->first();
      if ( is_null($lang) == true) {
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

      Lang::find($id)->update($data);
      Cache::forget('langBnDatas');
      Cache::forget('langEnDatas');
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    return redirect()->route('admin.lang')->with([
      'messlang' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

  public function delete(Request $request, $id, $sid = 0)
  {    
    $this->authorize('delete',App\Lang::class);
    $lang = Lang::find($id);
    if ( is_null($lang) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    try {
      if ($sid==false) {
        $lang->status = false;
        $lang->save();
      } else {
        $lang->delete();
      }
      Cache::forget('langBnDatas');
      Cache::forget('langEnDatas');
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    
    return redirect()->route('admin.lang')->with([
      'messlang' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

}
