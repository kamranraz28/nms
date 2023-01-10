<?php

namespace App\Http\Controllers\Admin;
use App\Models\Upazila;
use App\Models\District;
use App\Models\Division;

use App\Models\ForestBeat;
use App\Models\ForestRange;
use Illuminate\Http\Request;
use App\Models\ForestDivision;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ForestBeatController extends Controller
{
  const VIEW_PATH = 'admin.forest_beat.';
  public function __construct()
  {

  }

  public function index()
  {
    $this->authorize('read',ForestBeat::class);
    $forest_beats = ForestBeat::with('forestState','forestDivision','forestRange')->lwd()->get();
    return view(self::VIEW_PATH . 'index',compact('forest_beats'));

  }

  public function create()
  {
    $this->authorize('create',App\ForestBeat::class);
    $forest_divisions = ForestDivision::get();
    $forest_ranges = ForestRange::get();
    $divisions = Division::get();
    $districts = District::get();
    $upazilas = Upazila::get();
    return view(self::VIEW_PATH . 'add_edit', compact('forest_divisions','forest_ranges','divisions','districts','upazilas'));
  }

  public function store(Request $request)
  {
    $this->authorize('create',App\ForestBeat::class);
    //return $request->all();
    $this->validate($request, [
        //'name' => 'required|min:1|max:128',
        'title_en' => 'required|min:1|max:128',
        'title_bn' => 'required|min:1|max:128',
        'forest_division_id' => 'required',
        'forest_range_id' => 'required',
    ]);

    try {
      $forest_beat = DB::table('forest_beats')->orderBy('id','DESC')->first();
      $data = $request->except('_token');
      $authUser = Auth::guard('admin')->user()->id;
      array_walk_recursive($data, function (&$val) {
          $val = trim($val);
          $val = is_string($val) && $val === '' ? null : $val;
      });

      // if ($forest_beat) {
      //   $data['code'] = $forest_beat->code + 1;
      // } else {
      //   $data['code'] = 1;
      // }
      //state
      $state = ForestDivision::select('id','forest_state_id')->where(['id'=>$forest_beat->forest_division_id])->first();
      $data['forest_state_id'] = $state->forest_state_id;
      //state
      
      $data['created_by'] = $authUser;
      ForestBeat::create($data);
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }

    return redirect()->route('admin.forest_beat')->with([
                    'message' => __('admin.common.success'),
                    'alert-type' => 'success'
                ]);
  }

  public function edit(Request $request, $id)
  {
    $this->authorize('update',App\ForestBeat::class);
    //validation
      $arr_id = [];
      $results = ForestBeat::lwd()->orderBy('id','desc')->get();
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
    $forest_beat = ForestBeat::where(['id'=>$id])->first();

    $forest_divisions = ForestDivision::get();
    $forest_ranges = ForestRange::get()->where('forest_division_id',$forest_beat->forest_division_id);
    $divisions = Division::get();
    $districts = District::get();
    $upazilas = Upazila::get();
    
    if ( is_null($forest_beat) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }
    return view(self::VIEW_PATH . 'add_edit', compact('forest_beat','forest_divisions','forest_ranges','divisions','districts','upazilas'));
  }

  public function update(Request $request, $id)
  {
    $this->authorize('update',App\ForestBeat::class);
    //dd($request->all());
    $this->validate($request, [
      //'name' => 'required|min:1|max:128',
      'title_en' => 'required|min:1|max:128',
      'title_bn' => 'required|min:1|max:128',
      'forest_division_id' => 'required',
      'forest_range_id' => 'required',
    ]);

    try {
      $forest_beat = ForestBeat::where(['id'=>$id])->first();
      if ( is_null($forest_beat) == true) {
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
      $state = ForestDivision::select('id','forest_state_id')->where(['id'=>$forest_beat->forest_division_id])->first();
      $data['forest_state_id'] = $state->forest_state_id;
      //state
      ForestBeat::find($id)->update($data);
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    return redirect()->route('admin.forest_beat')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

  public function delete(Request $request, $id, $sid = 0)
  {    
    $this->authorize('delete',App\ForestBeat::class);
    //validation
      $arr_id = [];
      $results = ForestBeat::lwd()->orderBy('id','desc')->get();
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
    $forest_beat = ForestBeat::find($id);
    if ( is_null($forest_beat) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    try {
      if ($sid==false) {
        $forest_beat->status = false;
        $forest_beat->save();
      } else {
        $forest_beat->delete();
      }
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    
    return redirect()->route('admin.forest_beat')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

}
