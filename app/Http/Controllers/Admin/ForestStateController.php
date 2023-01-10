<?php

namespace App\Http\Controllers\Admin;
use App\Models\ForestState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ForestStateController extends Controller
{
  const VIEW_PATH = 'admin.forest_state.';
  public function __construct()
  {

  }

  public function index()
  {
    $this->authorize('read',ForestState::class);
    $forest_states = ForestState::lwd()->get();
    return view(self::VIEW_PATH . 'index',compact('forest_states'));
  }

  public function create()
  {
    $this->authorize('create',App\ForestState::class);
    return view(self::VIEW_PATH . 'add_edit');
  }

  public function store(Request $request)
  {
    $this->authorize('create',App\ForestState::class);
    //return $request->all();
    $this->validate($request, [
        //'name' => 'required|min:1|max:128',
        'title_en' => 'required|min:1|max:128',
        'title_bn' => 'required|min:1|max:128',
    ]);

    try {
      $forest_state = DB::table('forest_states')->orderBy('id','DESC')->first();
      $data = $request->except('_token');
      $authUser = Auth::guard('admin')->user()->id;
      array_walk_recursive($data, function (&$val) {
          $val = trim($val);
          $val = is_string($val) && $val === '' ? null : $val;
      });

      // if ($forest_state) {
      //   $data['code'] = $forest_state->code + 1;
      // } else {
      //   $data['code'] = 1;
      // }
      
      $data['created_by'] = $authUser;
      ForestState::create($data);
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }

    return redirect()->route('admin.forest_state')->with([
                    'message' => __('admin.common.success'),
                    'alert-type' => 'success'
                ]);
  }

  public function edit(Request $request, $id)
  {
    $this->authorize('update',App\ForestState::class);
    //validation
      $arr_id = [];
      $results = ForestState::lwd()->orderBy('id','desc')->get();
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
    $forest_state = ForestState::where(['id'=>$id])->first();
    if ( is_null($forest_state) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }
    return view(self::VIEW_PATH . 'add_edit', compact('forest_state'));
  }

  public function update(Request $request, $id)
  {
    $this->authorize('update',App\ForestState::class);
    //dd($request->all());
    $this->validate($request, [
      //'name' => 'required|min:1|max:128',
      'title_en' => 'required|min:1|max:128',
      'title_bn' => 'required|min:1|max:128',
    ]);

    try {
      $forest_state = ForestState::where(['id'=>$id])->first();
      if ( is_null($forest_state) == true) {
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

      ForestState::find($id)->update($data);
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    return redirect()->route('admin.forest_state')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

  public function delete(Request $request, $id, $sid = 0)
  {    
    $this->authorize('delete',App\ForestState::class);
    //validation
      $arr_id = [];
      $results = ForestState::lwd()->orderBy('id','desc')->get();
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
    $forest_state = ForestState::find($id);
    if ( is_null($forest_state) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    try {
      if ($sid==false) {
        $forest_state->status = false;
        $forest_state->save();
      } else {
        $forest_state->delete();
      }
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    
    return redirect()->route('admin.forest_state')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

}
