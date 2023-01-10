<?php

namespace App\Http\Controllers\Admin;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DivisionController extends Controller
{
  const VIEW_PATH = 'admin.division.';
  public function __construct()
  {

  }

  public function index()
  {
    $this->authorize('read',Division::class);
    $divisions = Division::with('state')->get();
    return view(self::VIEW_PATH . 'index',compact('divisions'));
  }

  public function create()
  {
    $this->authorize('create',App\Division::class);
    $states = State::get();
    return view(self::VIEW_PATH . 'add_edit',compact('states') );
  }

  public function store(Request $request)
  {
    $this->authorize('create',App\Division::class);
    //return $request->all();
    $this->validate($request, [
        //'name' => 'required|min:1|max:128',
        'title_en' => 'required|min:1|max:128',
        'title_bn' => 'required|min:1|max:128',
    ]);

    try {
      $division = DB::table('divisions')->orderBy('id','DESC')->first();
      $data = $request->except('_token');
      $authUser = Auth::guard('admin')->user()->id;
      array_walk_recursive($data, function (&$val) {
          $val = trim($val);
          $val = is_string($val) && $val === '' ? null : $val;
      });

      // if ($division) {
      //   $data['code'] = $division->code + 1;
      // } else {
      //   $data['code'] = 1;
      // }
      
      $data['created_by'] = $authUser;
      Division::create($data);
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }

    return redirect()->route('admin.division')->with([
                    'message' => __('admin.common.success'),
                    'alert-type' => 'success'
                ]);
  }

  public function edit(Request $request, $id)
  {
    $this->authorize('update',App\Division::class);
    $states = State::get();
    $division = Division::where(['id'=>$id])->first();
    if ( is_null($division) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }
    return view(self::VIEW_PATH . 'add_edit', compact('division','states'));
  }

  public function update(Request $request, $id)
  {
    $this->authorize('update',App\Division::class);
    //dd($request->all());
    $this->validate($request, [
      //'name' => 'required|min:1|max:128',
      'title_en' => 'required|min:1|max:128',
      'title_bn' => 'required|min:1|max:128',
    ]);

    try {
      $division = Division::where(['id'=>$id])->first();
      if ( is_null($division) == true) {
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

      Division::find($id)->update($data);
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    return redirect()->route('admin.division')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

  public function delete(Request $request, $id, $sid = 0)
  {    
    $this->authorize('delete',App\Division::class);
    $division = Division::find($id);
    if ( is_null($division) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    try {
      if ($sid==false) {
        $division->status = false;
        $division->save();
      } else {
        $division->delete();
      }
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    
    return redirect()->route('admin.division')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

}
