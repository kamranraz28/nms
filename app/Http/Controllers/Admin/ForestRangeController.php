<?php

namespace App\Http\Controllers\Admin;
use App\Models\Upazila;
use App\Models\District;
use App\Models\Division;
use App\Models\ForestRange;
use Illuminate\Http\Request;
use App\Models\ForestDivision;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ForestRangeController extends Controller
{
  const VIEW_PATH = 'admin.forest_range.';
  public function __construct()
  {

  }

  public function index()
  {
    $this->authorize('read',ForestRange::class);
    $forest_ranges = ForestRange::with('forestState','forestDivision')->lwd()->get();
    return view(self::VIEW_PATH . 'index',compact('forest_ranges'));
  }

  public function create()
  {
    $this->authorize('create',App\ForestRange::class);
    $forest_divisions = ForestDivision::get();
    $divisions = Division::get();
    $districts = District::get();
    $upazilas = Upazila::get();
    return view(self::VIEW_PATH . 'add_edit',compact('forest_divisions','districts','divisions','upazilas'));
  }

  public function store(Request $request)
  {
    $this->authorize('create',App\ForestRange::class);
    //return $request->all();
    $this->validate($request, [
        //'name' => 'required|min:1|max:128',
        'title_en' => 'required|min:1|max:128',
        'title_bn' => 'required|min:1|max:128',
        'forest_division_id' => 'required',
    ]);

    try {
      $forest_range = DB::table('forest_ranges')->orderBy('id','DESC')->first();
      $data = $request->except('_token');
      $authUser = Auth::guard('admin')->user()->id;
      array_walk_recursive($data, function (&$val) {
          $val = trim($val);
          $val = is_string($val) && $val === '' ? null : $val;
      });

      // if ($forest_range) {
      //   $data['code'] = $forest_range->code + 1;
      // } else {
      //   $data['code'] = 1;
      // }
      
      //state
      $state = ForestDivision::select('id','forest_state_id')->where(['id'=>$forest_range->forest_division_id])->first();
      $data['forest_state_id'] = $state->forest_state_id;
      //state
      $data['created_by'] = $authUser;
      ForestRange::create($data);
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }

    return redirect()->route('admin.forest_range')->with([
                    'message' => __('admin.common.success'),
                    'alert-type' => 'success'
                ]);
  }

  public function edit(Request $request, $id)
  {
    $this->authorize('update',App\ForestRange::class);
    //validation
      $arr_id = [];
      $results = ForestRange::lwd()->orderBy('id','desc')->get();
      foreach ($results as $key => $result) {
        $arr_id[] = $result->id;
      }
      if (!in_array($id, $arr_id))
      {
        return redirect()->route('admin.dashboard')->with([
          'error' => __('admin.common.error'),
          'alert-type' => 'error'
        ]);
      }
    //validation
    $forest_range = ForestRange::where(['id'=>$id])->first();
    $forest_divisions = ForestDivision::get();

    $divisions = Division::get();
    $districts = District::get();
    $upazilas = Upazila::get();
    if ( is_null($forest_range) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }
    return view(self::VIEW_PATH . 'add_edit', compact('forest_range','forest_divisions','divisions','districts','upazilas'));
  }

  public function update(Request $request, $id)
  {
    $this->authorize('update',App\ForestRange::class);
    //dd($request->all());
    $this->validate($request, [
      //'name' => 'required|min:1|max:128',
      'title_en' => 'required|min:1|max:128',
      'title_bn' => 'required|min:1|max:128',
      'forest_division_id' => 'required',
    ]);

    try {
      $forest_range = ForestRange::where(['id'=>$id])->first();
      if ( is_null($forest_range) == true) {
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
      $state = ForestDivision::select('id','forest_state_id')->where(['id'=>$forest_range->forest_division_id])->first();
      $data['forest_state_id'] = $state->forest_state_id;
      //state

      $data['updated_by'] = $authUser;

      ForestRange::find($id)->update($data);
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    return redirect()->route('admin.forest_range')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

  public function delete(Request $request, $id, $sid = 0)
  {    
    $this->authorize('delete',App\ForestRange::class);
    //validation
      $arr_id = [];
      $results = ForestRange::lwd()->orderBy('id','desc')->get();
      foreach ($results as $key => $result) {
        $arr_id[] = $result->id;
      }
      if (!in_array($id, $arr_id))
      {
        return redirect()->route('admin.dashboard')->with([
          'error' => __('admin.common.error'),
          'alert-type' => 'error'
        ]);
      }
    //validation
    $forest_range = ForestRange::find($id);
    if ( is_null($forest_range) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    try {
      if ($sid==false) {
        $forest_range->status = false;
        $forest_range->save();
      } else {
        $forest_range->delete();
      }
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    
    return redirect()->route('admin.forest_range')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

}
