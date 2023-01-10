<?php

namespace App\Http\Controllers\Admin;
use App\Models\FinancialYear;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FinancialYearController extends Controller
{
  const VIEW_PATH = 'admin.financial_year.';
  public function __construct()
  {

  }

  public function index()
  {
    $this->authorize('read',FinancialYear::class);
    $financial_years = FinancialYear::get();
    return view(self::VIEW_PATH . 'index',compact('financial_years'));
  }

  public function create()
  {
    $this->authorize('create',App\FinancialYear::class);
    return view(self::VIEW_PATH . 'add_edit');
  }

  public function store(Request $request)
  {
    $this->authorize('create',App\FinancialYear::class);
    //return $request->all();
    $this->validate($request, [
        'year' => 'required',
        'title_en' => 'required|min:1|max:128',
        'title_bn' => 'required|min:1|max:128',
    ]);

    try {
      $financial_year = DB::table('financial_years')->orderBy('id','DESC')->first();
      $data = $request->except('_token');
      $authUser = Auth::guard('admin')->user()->id;
      array_walk_recursive($data, function (&$val) {
          $val = trim($val);
          $val = is_string($val) && $val === '' ? null : $val;
      });

      if ($financial_year) {
        $data['code'] = $financial_year->code + 1;
      } else {
        $data['code'] = 1;
      }
      
      $data['created_by'] = $authUser;
      FinancialYear::create($data);
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }

    return redirect()->route('admin.financial_year')->with([
                    'message' => __('admin.common.success'),
                    'alert-type' => 'success'
                ]);
  }

  public function edit(Request $request, $id)
  {
    $this->authorize('update',App\FinancialYear::class);
    $financial_year = FinancialYear::where(['id'=>$id])->first();
    if ( is_null($financial_year) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }
    return view(self::VIEW_PATH . 'add_edit', compact('financial_year'));
  }

  public function update(Request $request, $id)
  {
    $this->authorize('update',App\FinancialYear::class);
    //dd($request->all());
    $this->validate($request, [
      'year' => 'required',
      'title_en' => 'required|min:1|max:128',
      'title_bn' => 'required|min:1|max:128',
    ]);

    try {
      $financial_year = FinancialYear::where(['id'=>$id])->first();
      if ( is_null($financial_year) == true) {
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

      FinancialYear::find($id)->update($data);
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    return redirect()->route('admin.financial_year')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

  public function delete(Request $request, $id, $sid = 0)
  {    
    $this->authorize('delete',App\FinancialYear::class);
    $financial_year = FinancialYear::find($id);
    if ( is_null($financial_year) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    try {
      if ($sid==false) {
        $financial_year->status = false;
        $financial_year->save();
      } else {
        $financial_year->delete();
      }
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    
    return redirect()->route('admin.financial_year')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

}
