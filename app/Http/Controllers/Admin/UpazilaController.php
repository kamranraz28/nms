<?php

namespace App\Http\Controllers\Admin;
use App\Models\Upazila;
use App\Models\District;
use App\Models\Division;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UpazilaController extends Controller
{
  const VIEW_PATH = 'admin.upazila.';
  public function __construct()
  {

  }

  public function index()
  {
    $this->authorize('read',Upazila::class);
    $upazilas = Upazila::with('division','district')->get();
    return view(self::VIEW_PATH . 'index',compact('upazilas'));
  }

  public function create()
  {
    $this->authorize('create',App\Upazila::class);
    $divisions = Division::get();
    $districts = District::get();
    return view(self::VIEW_PATH . 'add_edit', compact('divisions','districts'));
  }

  public function store(Request $request)
  {
    $this->authorize('create',App\Upazila::class);
    //return $request->all();
    $this->validate($request, [
        //'name' => 'required|min:1|max:128',
        'title_en' => 'required|min:1|max:128',
        'title_bn' => 'required|min:1|max:128',
        'division_id' => 'required',
        'district_id' => 'required',
    ]);

    try {
      $upazila = DB::table('upazilas')->orderBy('id','DESC')->first();
      $data = $request->except('_token');
      $authUser = Auth::guard('admin')->user()->id;
      array_walk_recursive($data, function (&$val) {
          $val = trim($val);
          $val = is_string($val) && $val === '' ? null : $val;
      });

      // if ($upazila) {
      //   $data['code'] = $upazila->code + 1;
      // } else {
      //   $data['code'] = 1;
      // }
      //state
      $state = Division::select('id','state_id')->where(['id'=>$upazila->division_id])->first();
      $data['state_id'] = $state->state_id;
      //state
      
      $data['created_by'] = $authUser;
      Upazila::create($data);
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }

    return redirect()->route('admin.upazila')->with([
                    'message' => __('admin.common.success'),
                    'alert-type' => 'success'
                ]);
  }

  public function edit(Request $request, $id)
  {
    $this->authorize('update',App\Upazila::class);
    $upazila = Upazila::where(['id'=>$id])->first();

    $divisions = Division::get();
    $districts = District::get()->where('division_id',$upazila->division_id);
    if ( is_null($upazila) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }
    return view(self::VIEW_PATH . 'add_edit', compact('upazila','divisions','districts'));
  }

  public function update(Request $request, $id)
  {
    $this->authorize('update',App\Upazila::class);
    //dd($request->all());
    $this->validate($request, [
      //'name' => 'required|min:1|max:128',
      'title_en' => 'required|min:1|max:128',
      'title_bn' => 'required|min:1|max:128',
      'division_id' => 'required',
        'district_id' => 'required',
    ]);

    try {
      $upazila = Upazila::where(['id'=>$id])->first();
      if ( is_null($upazila) == true) {
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
      //state
      $state = Division::select('id','state_id')->where(['id'=>$upazila->division_id])->first();
      $data['state_id'] = $state->state_id;
      //state
      Upazila::find($id)->update($data);
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    return redirect()->route('admin.upazila')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

  public function delete(Request $request, $id, $sid = 0)
  {    
    $this->authorize('delete',App\Upazila::class);
    $upazila = Upazila::find($id);
    if ( is_null($upazila) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    try {
      if ($sid==false) {
        $upazila->status = false;
        $upazila->save();
      } else {
        $upazila->delete();
      }
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    
    return redirect()->route('admin.upazila')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

}
