<?php

namespace App\Http\Controllers\Admin;
use App\Models\State;
use App\Models\ForestState;
use Illuminate\Http\Request;

use App\Models\ForestDivision;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ForestDivisionController extends Controller
{
  const VIEW_PATH = 'admin.forest_division.';
  public function __construct()
  {

  }

  public function index()
  {
    $this->authorize('read',ForestDivision::class);
    $forest_divisions = ForestDivision::with('forestState')->lwd()->get();
    return view(self::VIEW_PATH . 'index',compact('forest_divisions'));
  }

  public function create()
  {
    $this->authorize('create',App\ForestDivision::class);
    $forest_states = ForestState::get();
    return view(self::VIEW_PATH . 'add_edit',compact('forest_states') );
  }

  public function store(Request $request)
  {
    $this->authorize('create',App\ForestDivision::class);
    //return $request->all();
    $this->validate($request, [
        //'name' => 'required|min:1|max:128',
        'title_en' => 'required|min:1|max:128',
        'title_bn' => 'required|min:1|max:128',
    ]);

    try {
      $forest_division = DB::table('forest_divisions')->orderBy('id','DESC')->first();
      $data = $request->except('_token');
      $authUser = Auth::guard('admin')->user()->id;
      array_walk_recursive($data, function (&$val) {
          $val = trim($val);
          $val = is_string($val) && $val === '' ? null : $val;
      });

      // if ($forest_division) {
      //   $data['code'] = $forest_division->code + 1;
      // } else {
      //   $data['code'] = 1;
      // }
      
      $data['created_by'] = $authUser;
      ForestDivision::create($data);
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }

    return redirect()->route('admin.forest_division')->with([
                    'message' => __('admin.common.success'),
                    'alert-type' => 'success'
                ]);
  }

  public function edit(Request $request, $id)
  {
    $this->authorize('update',App\ForestDivision::class);
    //validation
    $arr_id = [];
    $results = ForestDivision::lwd()->orderBy('id','desc')->get();
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
    $forest_states = ForestState::get();
    $forest_division = ForestDivision::where(['id'=>$id])->first();
    if ( is_null($forest_division) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }
    return view(self::VIEW_PATH . 'add_edit', compact('forest_division','forest_states'));
  }

  public function update(Request $request, $id)
  {
    $this->authorize('update',App\ForestDivision::class);
    //dd($request->all());
    $this->validate($request, [
      //'name' => 'required|min:1|max:128',
      'title_en' => 'required|min:1|max:128',
      'title_bn' => 'required|min:1|max:128',
    ]);

    try {
      $forest_division = ForestDivision::where(['id'=>$id])->first();
      if ( is_null($forest_division) == true) {
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

      ForestDivision::find($id)->update($data);
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    return redirect()->route('admin.forest_division')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

  public function delete(Request $request, $id, $sid = 0)
  {    
    $this->authorize('delete',App\ForestDivision::class);
    //validation
      $arr_id = [];
      $results = ForestDivision::lwd()->orderBy('id','desc')->get();
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
    $forest_division = ForestDivision::find($id);
    if ( is_null($forest_division) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    try {
      if ($sid==false) {
        $forest_division->status = false;
        $forest_division->save();
      } else {
        $forest_division->delete();
      }
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    
    return redirect()->route('admin.forest_division')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

}
