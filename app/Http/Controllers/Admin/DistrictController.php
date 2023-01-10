<?php

namespace App\Http\Controllers\Admin;
use App\Models\District;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DistrictController extends Controller
{
  const VIEW_PATH = 'admin.district.';
  public function __construct()
  {

  }

  public function index()
  {
    $this->authorize('read',District::class);
    $districts = District::with('division')->get();
    return view(self::VIEW_PATH . 'index',compact('districts'));
  }

  public function create()
  {
    $this->authorize('create',App\District::class);
    $divisions = Division::get();
    return view(self::VIEW_PATH . 'add_edit',compact('divisions'));
  }

  public function store(Request $request)
  {
    $this->authorize('create',App\District::class);
    //return $request->all();
    $this->validate($request, [
        //'name' => 'required|min:1|max:128',
        'title_en' => 'required|min:1|max:128',
        'title_bn' => 'required|min:1|max:128',
        'division_id' => 'required',
    ]);

    try {
      $district = DB::table('districts')->orderBy('id','DESC')->first();
      $data = $request->except('_token');
      $authUser = Auth::guard('admin')->user()->id;
      array_walk_recursive($data, function (&$val) {
          $val = trim($val);
          $val = is_string($val) && $val === '' ? null : $val;
      });

      // if ($district) {
      //   $data['code'] = $district->code + 1;
      // } else {
      //   $data['code'] = 1;
      // }
      
      //state
      $state = Division::select('id','state_id')->where(['id'=>$district->division_id])->first();
      $data['state_id'] = $state->state_id;
      //state
      $data['created_by'] = $authUser;
      District::create($data);
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }

    return redirect()->route('admin.district')->with([
                    'message' => __('admin.common.success'),
                    'alert-type' => 'success'
                ]);
  }

  public function edit(Request $request, $id)
  {
    $this->authorize('update',App\District::class);
    $district = District::where(['id'=>$id])->first();
    $divisions = Division::get();
    if ( is_null($district) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }
    return view(self::VIEW_PATH . 'add_edit', compact('district','divisions'));
  }

  public function update(Request $request, $id)
  {
    $this->authorize('update',App\District::class);
    //dd($request->all());
    $this->validate($request, [
      //'name' => 'required|min:1|max:128',
      'title_en' => 'required|min:1|max:128',
      'title_bn' => 'required|min:1|max:128',
      'division_id' => 'required',
    ]);

    try {
      $district = District::where(['id'=>$id])->first();
      if ( is_null($district) == true) {
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

      //state
      $state = Division::select('id','state_id')->where(['id'=>$district->division_id])->first();
      $data['state_id'] = $state->state_id;
      //state

      $data['updated_by'] = $authUser;

      District::find($id)->update($data);
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    return redirect()->route('admin.district')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

  public function delete(Request $request, $id, $sid = 0)
  {    
    $this->authorize('delete',App\District::class);
    $district = District::find($id);
    if ( is_null($district) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    try {
      if ($sid==false) {
        $district->status = false;
        $district->save();
      } else {
        $district->delete();
      }
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    
    return redirect()->route('admin.district')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

}
