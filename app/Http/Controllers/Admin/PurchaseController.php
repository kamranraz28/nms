<?php

namespace App\Http\Controllers\Admin;
use App\Models\Unit;
use App\Models\Admin;
use App\Models\Budget;
use App\Models\Nursery;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\PriceType;
use App\Models\StockType;
use App\Models\ForestBeat;
use Illuminate\Http\Request;
use App\Models\PurchaseDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
  const VIEW_PATH = 'admin.purchase.';
  public function __construct()
  {

  }

  public function index()
  {
    $this->authorize('read',Purchase::class);
    $purchases = Purchase::with('purchaseDetail','nursery','approvedBy','range_comment','acf_comment','dfo_comment','division','district','upazila','stockType','budget','forestBeat')->lwd()->orderBy('id','desc')->paginate(100);
    // dd($purchases);
    return view(self::VIEW_PATH . 'index',compact('purchases'));
  }

  public function create()
  {
    $this->authorize('create',App\Purchase::class);

    $stock_types = StockType::get();
    $price_types = PriceType::get();
    $budgets = Budget::get();
    $categories = Category::get()->where('last',true);
    $purchases = Purchase::with('purchaseDetail')->get();

    $authUser = Auth::guard('admin')->user()->load(['userType']);
    $default_role = $authUser->userType->default_role;
    if (Auth::guard('admin')->user()->userType->default_role <= Admin::DEFAULT_ROLE_LIST[5]) {
      $forest_beats = ForestBeat::lwd()->get();
    } else {
      $forest_beats = ForestBeat::where('id',$authUser->forest_beat_id)->get();
    }

    return view(self::VIEW_PATH . 'add_edit',compact('categories','stock_types','purchases','price_types','budgets','forest_beats'));
  }

  public function print(Request $request, $id)
  {
    $this->authorize('print',App\Purchase::class);

    $purchase = Purchase::with('purchaseDetail','nursery','approvedBy','division','district','upazila','forestBeat')->lwd()->find($id);
    $purchase_etails = PurchaseDetail::with('product','unit','category','stockType','priceType','forestBeat')->where(['purchase_id' => $purchase->id])->get();
    return view(self::VIEW_PATH . 'print',compact('purchase','purchase_etails'));
  }

  public function view(Request $request, $id)
  {
    $this->authorize('view',App\Purchase::class);

    $purchase = Purchase::with('purchaseDetail','nursery','approvedBy','division','district','upazila','budget','forestBeat')->lwd()->find($id);
    $purchase_etails = PurchaseDetail::with('product','unit','category','stockType','priceType','forestBeat')->where(['purchase_id' => $purchase->id])->get();
    return view(self::VIEW_PATH . 'view',compact('purchase','purchase_etails'));
  }

  public function approval(Request $request, $id)
  {
    $this->authorize('approval',App\Purchase::class);
    $purchase = Purchase::find($id);
    if ( is_null($purchase) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }
    

    try {
      DB::beginTransaction();
      $authUser = Auth::guard('admin')->user()->load(['userType']);
      if($authUser->userType->id == Admin::BO){
        return back()->with([
          'error' => __('admin.common.error_eligible_msg'),
          'alert-type' => 'error'
        ]);
      } elseif($authUser->userType->id == Admin::RO){
        //code    
        if ($purchase->app_status == 1 && $purchase->range_comment== '') {
          $auth_id = Auth::guard('admin')->user()->id;
          DB::update('update purchase_details set app_status = 2, approved_by = ? where purchase_id = ?', [$auth_id,$id]);
          $purchase->approved_by = $auth_id;
          $purchase->app_status = 2;
          $purchase->save();
        }elseif($purchase->app_status == 2){
          $auth_id = Auth::guard('admin')->user()->id;
          DB::update('update purchase_details set app_status = 1, approved_by = ? where purchase_id = ?', [$auth_id,$id]);
          $purchase->approved_by = $auth_id;
          $purchase->app_status = 1;
          $purchase->save();
        } else {
          return back()->with([
            'error' => __('admin.common.error_eligible_msg'),
            'alert-type' => 'error'
          ]);
        }
        //code          
      } elseif($authUser->userType->id == Admin::ACF){
        //code    
        if ($purchase->app_status == 2 && $purchase->acf_comment == '' ) {
          $auth_id = Auth::guard('admin')->user()->id;
          DB::update('update purchase_details set app_status = 3, approved_by = ? where purchase_id = ?', [$auth_id,$id]);
          $purchase->approved_by = $auth_id;
          $purchase->app_status = 3;
          $purchase->save();
        }elseif($purchase->app_status == 3){
          $auth_id = Auth::guard('admin')->user()->id;
          DB::update('update purchase_details set app_status = 2, approved_by = ? where purchase_id = ?', [$auth_id,$id]);
          $purchase->approved_by = $auth_id;
          $purchase->app_status = 2;
          $purchase->save();
        } else {
          return back()->with([
            'error' => __('admin.common.error_eligible_msg'),
            'alert-type' => 'error'
          ]);
        }
        //code  
      } elseif($authUser->userType->id == Admin::DFO){
        //code    
        if ($purchase->app_status == 3 && $purchase->dfo_comment== '') {
          $auth_id = Auth::guard('admin')->user()->id;
          DB::update('update purchase_details set approved = 1, app_status = 4, approved_by = ? where purchase_id = ?', [$auth_id,$id]);
          $purchase->approved_by = $auth_id;
          $purchase->app_status = 4;
          $purchase->approved = true; // final approve
          $purchase->save();
        }elseif($purchase->app_status == 4){
          $auth_id = Auth::guard('admin')->user()->id;
          DB::update('update purchase_details set approved = 0, app_status = 3, approved_by = ? where purchase_id = ?', [$auth_id,$id]);
          $purchase->approved_by = $auth_id;
          $purchase->app_status = 3;
          $purchase->approved = false; // final approve
          $purchase->save();
        } else {
          return back()->with([
            'error' => __('admin.common.error_eligible_msg'),
            'alert-type' => 'error'
          ]);
        }
        //code  
      }elseif($authUser->userType->id == Admin::MASTER){
        //code    
        if ($purchase->app_status == 1 || $purchase->app_status == 2 || $purchase->app_status == 3) {
          $auth_id = Auth::guard('admin')->user()->id;
          DB::update('update purchase_details set approved = 1, app_status = 4, approved_by = ? where purchase_id = ?', [$auth_id,$id]);
          $purchase->approved_by = $auth_id;
          $purchase->app_status = 4;
          $purchase->approved = true; // final approve
          $purchase->save();
        }elseif($purchase->app_status == 4){
          $auth_id = Auth::guard('admin')->user()->id;
          DB::update('update purchase_details set approved = 0, app_status = 3, approved_by = ? where purchase_id = ?', [$auth_id,$id]);
          $purchase->approved_by = $auth_id;
          $purchase->app_status = 3;
          $purchase->approved = false; // final approve
          $purchase->save();
        } else {
          return back()->with([
            'error' => __('admin.common.error_eligible_msg'),
            'alert-type' => 'error'
          ]);
        }
        //code  
      }
      
      
      else{
        return back()->with([
          'error' => __('admin.common.error_eligible_msg'),
          'alert-type' => 'error'
        ]);
      }
      DB::commit();
    } catch (\Throwable $exception) {
      DB::rollBack();
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    
    return redirect()->route('admin.purchase')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);

  }

public function disapproval(Request $request)
{
  $auth_id = Auth::guard('admin')->user()->id;
  // dd($auth_id);
  $UserInfo = Admin:: find($auth_id);
  // dd($UserInfo);
  $roleId= $UserInfo->role_id;
  // dd($roleId);
  $comment = $request->input('inputValue');
  // dd($comment);
  $purchaseId = $request->input('inputId');
  // dd($purchaseId);
  $purchaseInfo = Purchase::find($purchaseId);
  // dd($purchaseInfo);
  $status = $purchaseInfo->app_status;
  // dd($status);
  if ($status !=4){


  if ($roleId == 9 && $status==1 && $purchaseInfo) {
      $purchaseInfo->update([
          'range_comment' => $comment,
      ]);
  }elseif($roleId == 8 && $status==2 && $purchaseInfo){
    $purchaseInfo->update([
      'acf_comment' => $comment,
  ]);
  }elseif($roleId == 7 && $status==3 && $purchaseInfo){
    $purchaseInfo->update([
      'dfo_comment' => $comment,
  ]);
  }
  else{
    return back()->with([
      'error' => __('admin.common.error_eligible_msg'),
      'alert-type' => 'error'
    ]);
  }

  if ($purchaseInfo->range_comment || $purchaseInfo->acf_comment || $purchaseInfo->dfo_comment) {
    $purchaseInfo->update([
        'disapprove_status' => 1,
        'app_status' =>1,
        'approved' =>0,

    ]);
}
else{
  return back()->with([
    'error' => __('admin.common.error_eligible_msg'),
    'alert-type' => 'error'
  ]);
}
  }
  else{
    return back()->with([
      'error' => __('admin.common.error_eligible_msg'),
      'alert-type' => 'error'
    ]);
  }

  return redirect()->back();
}




  public function disapproval_change(Request $request)
  {
    $auth_id = Auth::guard('admin')->user()->id;
  // dd($auth_id);
  $UserInfo = Admin:: find($auth_id);
  // dd($UserInfo);
  $roleId= $UserInfo->role_id;
  // dd($roleId);
  $purchaseId = $request->input('inputId');
  // dd($purchaseId);
  $purchaseInfo = Purchase::find($purchaseId);
  // dd($purchaseInfo);

  if ($roleId == 7) {
    $purchaseInfo->update([
        'dfo_comment' => ''
    ]);
  } elseif ($roleId == 8) {
    $purchaseInfo->update([
        'acf_comment' => ''
    ]);
  } elseif ($roleId == 9) {
    $purchaseInfo->update([
        'range_comment' => ''
    ]);
  }  else{
    return back()->with([
      'error' => __('admin.common.error_eligible_msg'),
      'alert-type' => 'error'
    ]);
  }

  if (empty($purchaseInfo->dfo_comment) && empty($purchaseInfo->acf_comment) && empty($purchaseInfo->range_comment)) {
    $purchaseInfo->update([
        'disapprove_status' => 0
    ]);
  }  else{
    return back()->with([
      'error' => __('admin.common.error_eligible_msg'),
      'alert-type' => 'error'
    ]);
  }


  return redirect()->back();
  }


  public function store(Request $request)
  {
    $this->authorize('create',App\Purchase::class);
    //return $request->all();

    $this->validate($request, [
      'stock_type_id' => 'required',
      //'forest_beat_id' => 'required',
      'vch_date' => 'required',
    ]);
    foreach ($request['arraydata'] as  $key => $arraydata){
      if (!$arraydata['product_id']) {
        return back()->with([
          'error' => (app()->getLocale() == 'en') ? 'Please select product id' : 'পণ্য আইডি নির্বাচন করুন',
          'alert-type' => 'error'
        ]);
      }elseif(!$arraydata['quantity']){
        return back()->with([
          'error' => (app()->getLocale() == 'en') ? 'Please select product id' : 'পরিমাণ নির্বাচন করুন',
          'alert-type' => 'error'
        ]);
      }
    }



    $authUser = Auth::guard('admin')->user()->load(['userType']);
    $default_role = $authUser->userType->default_role;
    if (Auth::guard('admin')->user()->userType->default_role <= Admin::DEFAULT_ROLE_LIST[5]) {
      //Validator
      $validator = Validator::make($request->all(), [
        'forest_beat_id' => 'required',
      ]);
      if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
      }
      //Validator

      $request['forest_beat_id'] = $request->forest_beat_id;
    } else {
      $forest_beat = ForestBeat::findOrFail($authUser->forest_beat_id);
      $request['forest_beat_id'] = $forest_beat->id;
    }

    try {
      DB::beginTransaction();

      $purchase = DB::table('purchases')->orderBy('id','DESC')->first();
      $data = $request->except('_token');
      $authUser = Auth::guard('admin')->user()->id;

      $year = date_format(date_create($request->vch_date),"Y");

      array_walk_recursive($data, function (&$val) {
          $val = trim($val);
          $val = is_string($val) && $val === '' ? null : $val;
      });

      if ($purchase) {
        $data['code'] = $purchase->code + 1;
      } else {
        $data['code'] = 1;
      }


      $data['vch_date'] = date_format(date_create($request->vch_date),"Y-m-d");
      $data['created_by'] = $authUser;

      $forest_beat = ForestBeat::find($data['forest_beat_id']);
      $data['forest_state_id'] = $forest_beat->forest_state_id;
      $data['forest_division_id'] = $forest_beat->forest_division_id;
      $data['forest_range_id'] = $forest_beat->forest_range_id;

      $data['division_id'] = @$forest_beat->division_id;
      $data['district_id'] = @$forest_beat->district_id;
      $data['upazila_id'] = @$forest_beat->upazila_id;
      $data['year'] = $year;
      $data['web'] = true;
      $purchase = Purchase::create($data);
      $data['purchase_id'] = $purchase->id;

      

      foreach ($data['arraydata'] as  $key => $arraydata) {
        $product_id = $arraydata['product_id'];
        $product = Product::find($product_id);
        $arraydata['purchase_id'] = $purchase->id;
        $arraydata['stock_type_id'] = $data['stock_type_id'];
        $arraydata['budget_id'] = $data['budget_id'];
        $arraydata['forest_beat_id'] = $data['forest_beat_id'];
        $arraydata['forest_state_id'] = $data['forest_state_id'];
        $arraydata['forest_division_id'] = $data['forest_division_id'];
        $arraydata['forest_range_id'] = $data['forest_range_id'];
        $arraydata['forest_range_id'] = $data['forest_range_id'];

        $arraydata['division_id'] = $data['division_id'];
        $arraydata['district_id'] = $data['district_id'];
        $arraydata['upazila_id'] = $data['upazila_id'];


        $arraydata['code'] = $data['code'];
        $arraydata['year'] = $year;

        $arraydata['unit_id'] = $product->unit_id;
        $arraydata['color_id'] = $product->color_id;
        $arraydata['age_id'] = $product->age_id;
        $arraydata['size_id'] = $product->size_id;
        $arraydata['vch_date'] = $data['vch_date'];
        $arraydata['created_by'] = $authUser;
        $arraydata['price'] = $product->price;
        $arraydata['web'] = true;
        PurchaseDetail::create($arraydata);
      }
      DB::commit();
    } catch (\Throwable $exception) {
      DB::rollBack();
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }

    return redirect()->route('admin.purchase')->with([
                    'message' => __('admin.common.success'),
                    'alert-type' => 'success'
                ]);
  }

  public function edit(Request $request, $id)
  {
    $this->authorize('update',App\Purchase::class);

    //validation
    $arr_id = [];
    $results = Purchase::lwd()->orderBy('id','desc')->get();
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

    $purchase = Purchase::with('purchaseDetail')->where(['id'=>$id])->first();
    $purchase_etails = PurchaseDetail::with('product','unit')->where(['purchase_id' => $purchase->id])->get();
    
    $nurseries = Nursery::lwd()->get();
    $stock_types = StockType::get();
    $categories = Category::get()->where('last',true);

    $price_types = PriceType::get();

    $products = Product::get();
    $budgets = Budget::get();
    $units = Unit::get();

    $authUser = Auth::guard('admin')->user()->load(['userType']);
    $default_role = $authUser->userType->default_role;
    if (Auth::guard('admin')->user()->userType->default_role <= Admin::DEFAULT_ROLE_LIST[5]) {
      $forest_beats = ForestBeat::lwd()->get();
    } else {
      $forest_beats = ForestBeat::where('id',$authUser->forest_beat_id)->get();
    }

    if ( is_null($purchase) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }
    return view(self::VIEW_PATH . 'add_edit', compact('purchase','purchase_etails','stock_types','categories','nurseries','products','units','price_types','budgets','forest_beats'));
  }

  public function update(Request $request, $id)
  {
    $this->authorize('update',App\Purchase::class);
    //dd($request->all());
    $this->validate($request, [
      'stock_type_id' => 'required',
      'budget_id' => 'required',
      'vch_date' => 'required',
    ]);

    //validation
    $purchase = Purchase::find($id);
    $purchaseedit = Purchase::find($id);
    $authUser = Auth::guard('admin')->user()->load(['userType']);
    if($authUser->userType->id == Admin::BO){
      if ($purchase->app_status > 1) {
        return back()->with([
          'error' => __('admin.common.error_eligible_msg'),
          'alert-type' => 'error'
        ]);
      }
      
    } elseif($authUser->userType->id == Admin::RO){
    //code    
      if ($purchase->app_status > 2) {
        return back()->with([
          'error' => __('admin.common.error_eligible_msg'),
          'alert-type' => 'error'
        ]);
      }
    //code          
    } elseif($authUser->userType->id == Admin::ACF){
    //code    
      if ($purchase->app_status > 3) {
        return back()->with([
          'error' => __('admin.common.error_eligible_msg'),
          'alert-type' => 'error'
        ]);
      }
    //code 
    }
    //validation

    foreach ($request['arraydata'] as  $key => $arraydata){
      if (!$arraydata['product_id']) {
        return back()->with([
          'error' => (app()->getLocale() == 'en') ? 'Please select product id' : 'পণ্য আইডি নির্বাচন করুন',
          'alert-type' => 'error'
        ]);
      }elseif(!$arraydata['quantity']){
        return back()->with([
          'error' => (app()->getLocale() == 'en') ? 'Please select product id' : 'পরিমাণ নির্বাচন করুন',
          'alert-type' => 'error'
        ]);
      }

      $cid = $arraydata['category_id'];
      $pid = $arraydata['product_id'];

      $count = Product::where(['id'=>$pid, 'category_id'=>$cid])->count();
      if ($count == 0) {
        return back()->with([
          'error' => (app()->getLocale() == 'en') ? 'This product is not belongs to category' : 'এই পণ্য বিভাগের অন্তর্গত নয়',
          'alert-type' => 'error'
        ]);
      }

    }

    $authUser = Auth::guard('admin')->user()->load(['userType']);
    $default_role = $authUser->userType->default_role;
    if (Auth::guard('admin')->user()->userType->default_role <= Admin::DEFAULT_ROLE_LIST[5]) {
      //Validator
      $validator = Validator::make($request->all(), [
        'forest_beat_id' => 'required',
      ]);
      if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
      }
      //Validator
      
      $request['forest_beat_id'] = $request->forest_beat_id;
    } else {
      $forest_beat = ForestBeat::findOrFail($authUser->forest_beat_id);
      $request['forest_beat_id'] = $forest_beat->id;
    }

    try {
      DB::beginTransaction();
      $purchase = Purchase::orderBy('id','DESC')->first();
      $data = $request->except('_token');
      $authUser = Auth::guard('admin')->user()->id;

      $year = date_format(date_create($request->vch_date),"Y");

      array_walk_recursive($data, function (&$val) {
          $val = trim($val);
          $val = is_string($val) && $val === '' ? null : $val;
      });

      // if ($purchase) {
      //   $data['code'] = $purchase->code + 1;
      // } else {
      //   $data['code'] = 1;
      // }

      $purchase = Purchase::find($id);
      $data['vch_date'] = date_format(date_create($request->vch_date),"Y-m-d");
      $data['updated_by'] = $authUser;

      $forest_beat = ForestBeat::find($data['forest_beat_id']);
      $data['forest_state_id'] = $forest_beat->forest_state_id;
      $data['forest_division_id'] = $forest_beat->forest_division_id;
      $data['forest_range_id'] = $forest_beat->forest_range_id;

      $data['division_id'] = @$forest_beat->division_id;
      $data['district_id'] = @$forest_beat->district_id;
      $data['upazila_id'] = @$forest_beat->upazila_id;
      $data['year'] = $year;
      $data['web'] = true;

      $purchase->update($data);
      $data['purchase_id'] = $purchase->id;

      foreach ($data['arraydata'] as  $key => $arraydata) {
        if(@$arraydata['id']){

          $product_id = $arraydata['product_id'];
          $product = Product::find($product_id);
          $arraydata['purchase_id'] = $purchase->id;
          $arraydata['stock_type_id'] = $data['stock_type_id'];
          $arraydata['budget_id'] = $data['budget_id'];
          $arraydata['forest_beat_id'] = $data['forest_beat_id'];
          $arraydata['forest_state_id'] = $data['forest_state_id'];
          $arraydata['forest_division_id'] = $data['forest_division_id'];
          $arraydata['forest_range_id'] = $data['forest_range_id'];

          $arraydata['division_id'] = @$data['division_id'];
          $arraydata['district_id'] = @$data['district_id'];
          $arraydata['upazila_id'] = @$data['upazila_id'];

          $arraydata['code'] = $purchaseedit->code;
          $arraydata['year'] = $year;

          $arraydata['unit_id'] = $product->unit_id;
          $arraydata['color_id'] = $product->color_id;
          $arraydata['age_id'] = $product->age_id;
          $arraydata['size_id'] = $product->size_id;
          $arraydata['vch_date'] = $data['vch_date'];
          $arraydata['price'] = $product->price;
          $arraydata['updated_by'] = $authUser;
          $arraydata['web'] = true;
          $purchase_detail = PurchaseDetail::find($arraydata['id']);
          $purchase_detail->update($arraydata);
        }else{
          $product_id = $arraydata['product_id'];
          $product = Product::find($product_id);
          $arraydata['purchase_id'] = $purchase->id;
          $arraydata['stock_type_id'] = $data['stock_type_id'];
          $arraydata['budget_id'] = $data['budget_id'];
          $arraydata['forest_beat_id'] = $data['forest_beat_id'];
          $arraydata['forest_state_id'] = $data['forest_state_id'];
          $arraydata['forest_division_id'] = $data['forest_division_id'];
          $arraydata['forest_range_id'] = $data['forest_range_id'];

          $arraydata['division_id'] = @$data['division_id'];
          $arraydata['district_id'] = @$data['district_id'];
          $arraydata['upazila_id'] = @$data['upazila_id'];

          $arraydata['code'] = $purchaseedit->code;
          $arraydata['year'] = $year;

          $arraydata['unit_id'] = $product->unit_id;
          $arraydata['color_id'] = $product->color_id;
          $arraydata['age_id'] = $product->age_id;
          $arraydata['size_id'] = $product->size_id;
          $arraydata['vch_date'] = $data['vch_date'];
          $arraydata['price'] = $product->price;
          $arraydata['created_by'] = $authUser;
          $arraydata['updated_by'] = $authUser;
          $arraydata['web'] = true;
          PurchaseDetail::create($arraydata);
        }
      }
      DB::commit();
    } catch (\Throwable $exception) {
      DB::rollBack();
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    return redirect()->route('admin.purchase')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

  public function delete(Request $request, $id, $sid = 0)
  {    
    $this->authorize('delete',App\Purchase::class);
    //validation
    $arr_id = [];
    $results = Purchase::lwd()->orderBy('id','desc')->get();
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

    //validation
    $purchase = Purchase::find($id);
    $authUser = Auth::guard('admin')->user()->load(['userType']);
    if($authUser->userType->id == Admin::BO){
      if ($purchase->app_status > 1) {
        return back()->with([
          'error' => __('admin.common.error_eligible_msg'),
          'alert-type' => 'error'
        ]);
      }
      
    } elseif($authUser->userType->id == Admin::RO){
    //code    
      if ($purchase->app_status > 2) {
        return back()->with([
          'error' => __('admin.common.error_eligible_msg'),
          'alert-type' => 'error'
        ]);
      }
    //code          
    } elseif($authUser->userType->id == Admin::ACF){
    //code    
      if ($purchase->app_status > 3) {
        return back()->with([
          'error' => __('admin.common.error_eligible_msg'),
          'alert-type' => 'error'
        ]);
      }
    //code 
    }
    //validation
    $purchase = Purchase::find($id);
    if ( is_null($purchase) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    try {
      DB::beginTransaction();
      if ($sid==false) {
        $purchase->status = false;
        DB::update('update purchase_details set status = 0 where purchase_id = ?', [$id]);
        $purchase->save();
      } else {
        DB::delete('delete from purchase_details where purchase_id = ?', [$id]);
        $purchase->delete();
      }
      DB::commit();
    } catch (\Throwable $exception) {
      DB::rollBack();
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    
    return redirect()->route('admin.purchase')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

  public function deletepurchaseDetails(Request $request, $id)
  {    
    $this->authorize('delete_purchase_details',App\Purchase::class);
    $purchase_detail = PurchaseDetail::find($id);
    if ( is_null($purchase_detail) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    

    //validation
    $arr_id = [];
    $results = Purchase::lwd()->where('id',$purchase_detail->purchase_id)->orderBy('id','desc')->get();
    
    //dd($results);
    foreach ($results as $key => $result) {
      $arr_id[] = $result->id;
    }
    if (!in_array($purchase_detail->purchase_id, $arr_id))
    {
      return redirect()->route('admin.dashboard')->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }
    //validation

    //validation
    $purchase = Purchase::find($purchase_detail->purchase_id);
    $authUser = Auth::guard('admin')->user()->load(['userType']);
    if($authUser->userType->id == Admin::BO){
      if ($purchase->app_status > 1) {
        return back()->with([
          'error' => __('admin.common.error_eligible_msg'),
          'alert-type' => 'error'
        ]);
      }
      
    } elseif($authUser->userType->id == Admin::RO){
    //code    
      if ($purchase->app_status > 2) {
        return back()->with([
          'error' => __('admin.common.error_eligible_msg'),
          'alert-type' => 'error'
        ]);
      }
    //code          
    } elseif($authUser->userType->id == Admin::ACF){
    //code    
      if ($purchase->app_status > 3) {
        return back()->with([
          'error' => __('admin.common.error_eligible_msg'),
          'alert-type' => 'error'
        ]);
      }
    //code 
    }
    //validation



    try {
      DB::beginTransaction();
      $purchase_detail->delete();
      DB::commit();
    } catch (\Throwable $exception) {
      DB::rollBack();
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    
    return back()->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

}
