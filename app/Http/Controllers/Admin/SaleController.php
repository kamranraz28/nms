<?php

namespace App\Http\Controllers\Admin;
use App\Models\Sale;
use App\Models\Budget;
use App\Models\Unit;
use App\Models\User;
use App\Models\Admin;
use App\Models\Nursery;
use App\Models\Product;
use App\Models\Category;
use App\Models\Division;
use App\Models\PriceType;
use App\Models\StockType;
use App\Models\ForestBeat;
use App\Models\SaleDetail;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Models\ForestDivision;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
  const VIEW_PATH = 'admin.sale.';
  public function __construct()
  {

  }

  public function index()
  {
    $this->authorize('read',Sale::class);
    $sales = Sale::with('saleDetail','nursery','approvedBy','division','district','upazila','stockType','forestBeat')->lwd()->orderBy('id','desc')->paginate(100);
    
    return view(self::VIEW_PATH . 'index',compact('sales'));
  }

  public function create()
  {
    $this->authorize('create',App\Sale::class);
    
    $nurseries = Nursery::lwd()->get();
    $stock_types = StockType::get();
    $price_types = PriceType::get();
    $categories = Category::get()->where('last',true);
    $sales = Sale::with('saleDetail')->get();
    $users = User::get();

    $authUser = Auth::guard('admin')->user()->load(['userType']);
    $default_role = $authUser->userType->default_role;
    if (Auth::guard('admin')->user()->userType->default_role <= Admin::DEFAULT_ROLE_LIST[5]) {
      $forest_beats = ForestBeat::lwd()->get();
    } else {
      $forest_beats = ForestBeat::where('id',$authUser->forest_beat_id)->get();
    }
    $forest_division = ForestDivision::find($authUser->forest_division_id);

    $budgets = Budget::get();

    

    return view(self::VIEW_PATH . 'add_edit',compact('categories','stock_types','sales','nurseries','price_types','users','forest_beats','forest_division','budgets'));
  }

  public function print(Request $request, $id)
  {
    $this->authorize('print',App\Sale::class);

    $sale = Sale::with('saleDetail','nursery','approvedBy','division','district','upazila','forestBeat')->lwd()->find($id);
    $sale_details = SaleDetail::with('product','unit','category','stockType','priceType','forestBeat')->where(['sale_id' => $sale->id])->get();
    return view(self::VIEW_PATH . 'print',compact('sale','sale_details'));
  }

  public function view(Request $request, $id)
  {
    $this->authorize('view',App\Sale::class);

    $sale = Sale::with('saleDetail','nursery','approvedBy','division','district','upazila','forestBeat')->lwd()->find($id);
    $sale_details = SaleDetail::with('product','unit','category','stockType','priceType','forestBeat')->where(['sale_id' => $sale->id])->get();
    return view(self::VIEW_PATH . 'view',compact('sale','sale_details'));
  }

  public function approval(Request $request, $id)
  {
    $this->authorize('approval',App\Sale::class);
    $sale = Sale::find($id);
    if ( is_null($sale) == true) {
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
        if ($sale->app_status == 1) {
          $auth_id = Auth::guard('admin')->user()->id;
          DB::update('update sale_details set app_status = 2, approved_by = ? where sale_id = ?', [$auth_id,$id]);
          $sale->approved_by = $auth_id;
          $sale->app_status = 2;
          $sale->save();
        }elseif($sale->app_status == 2){
          $auth_id = Auth::guard('admin')->user()->id;
          DB::update('update sale_details set app_status = 1, approved_by = ? where sale_id = ?', [$auth_id,$id]);
          $sale->approved_by = $auth_id;
          $sale->app_status = 1;
          $sale->save();
        } else {
          return back()->with([
            'error' => __('admin.common.error_eligible_msg'),
            'alert-type' => 'error'
          ]);
        }
        //code          
      } elseif($authUser->userType->id == Admin::ACF){
        //code    
        if ($sale->app_status == 2) {
          $auth_id = Auth::guard('admin')->user()->id;
          DB::update('update sale_details set app_status = 3, approved_by = ? where sale_id = ?', [$auth_id,$id]);
          $sale->approved_by = $auth_id;
          $sale->app_status = 3;
          $sale->save();
        }elseif($sale->app_status == 3){
          $auth_id = Auth::guard('admin')->user()->id;
          DB::update('update sale_details set app_status = 2, approved_by = ? where sale_id = ?', [$auth_id,$id]);
          $sale->approved_by = $auth_id;
          $sale->app_status = 2;
          $sale->save();
        } else {
          return back()->with([
            'error' => __('admin.common.error_eligible_msg'),
            'alert-type' => 'error'
          ]);
        }
        //code  
      } elseif($authUser->userType->id == Admin::DFO){
        //code    
        if ($sale->app_status == 3) {
          $auth_id = Auth::guard('admin')->user()->id;
          DB::update('update sale_details set approved = 1, app_status = 4, approved_by = ? where sale_id = ?', [$auth_id,$id]);
          $sale->approved_by = $auth_id;
          $sale->app_status = 4;
          $sale->approved = true; // final approve
          $sale->save();
        }elseif($sale->app_status == 4){
          $auth_id = Auth::guard('admin')->user()->id;
          DB::update('update sale_details set approved = 0, app_status = 3, approved_by = ? where sale_id = ?', [$auth_id,$id]);
          $sale->approved_by = $auth_id;
          $sale->app_status = 3;
          $sale->approved = false; // final approve
          $sale->save();
        } else {
          return back()->with([
            'error' => __('admin.common.error_eligible_msg'),
            'alert-type' => 'error'
          ]);
        }
        //code  
      }elseif($authUser->userType->id == Admin::MASTER){
        //code    
        if ($sale->app_status == 1 || $sale->app_status == 2 || $sale->app_status == 3) {
          $auth_id = Auth::guard('admin')->user()->id;
          DB::update('update sale_details set approved = 1, app_status = 4, approved_by = ? where sale_id = ?', [$auth_id,$id]);
          $sale->approved_by = $auth_id;
          $sale->app_status = 4;
          $sale->approved = true; // final approve
          $sale->save();
        }elseif($sale->app_status == 4){
          $auth_id = Auth::guard('admin')->user()->id;
          DB::update('update sale_details set approved = 0, app_status = 3, approved_by = ? where sale_id = ?', [$auth_id,$id]);
          $sale->approved_by = $auth_id;
          $sale->app_status = 3;
          $sale->approved = false; // final approve
          $sale->save();
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
    
    return redirect()->route('admin.sale')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);

  }

  public function store(Request $request)
  {
    $this->authorize('create',App\Sale::class);
    

    $this->validate($request, [
      'stock_type_id' => 'required',
      'budget_id' => 'required',
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

    //return $request->all();
    $vat = SiteSetting::where(['status'=>1])->first()->vat;
    $total = 0;
    foreach ($request['arraydata'] as  $key => $arraydata){
      $total += $arraydata['price'] * $arraydata['quantity'];
    }

    $percent = ($request->percent) ? true : false ;
    $discount = $request->discount;
    $discount_amount = 0;
    if($percent){
      $discount_amount = ($discount*$total)/100;
    }else{
      $discount_amount = $discount;
    }
    $vat_amount = ($vat*$total)/100;
    $total_amount = ($total + $vat_amount) - $discount_amount;

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
      
      $sale = DB::table('sales')->orderBy('id','DESC')->first();
      $data = $request->except('_token');
      $authUser = Auth::guard('admin')->user()->id;

      $year = date_format(date_create($request->vch_date),"Y");

      array_walk_recursive($data, function (&$val) {
          $val = trim($val);
          $val = is_string($val) && $val === '' ? null : $val;
      });

      if ($sale) {
        $data['code'] = $sale->code + 1;
      } else {
        $data['code'] = 1;
      }

      $data['total_amount'] = $total_amount;
      $data['vat_amount'] = $vat_amount;
      $data['discount_amount'] = $discount_amount;


      $data['vch_date'] = date_format(date_create($request->vch_date),"Y-m-d");
      $data['created_by'] = $authUser;

      


      $forest_beat = ForestBeat::find($data['forest_beat_id']);
      $data['forest_state_id'] = $forest_beat->forest_state_id;
      $data['forest_division_id'] = $forest_beat->forest_division_id;
      $data['forest_range_id'] = $forest_beat->forest_range_id;
      
      $data['division_id'] = @$forest_beat->division_id;
      $data['district_id'] = @$forest_beat->district_id;
      $data['upazila_id'] = @$forest_beat->upazila_id;

      $data['percent'] = $percent;
      $data['total'] = $total;
      $data['year'] = $year;
      $data['web'] = true;

      $sale = Sale::create($data);
      $data['sale_id'] = $sale->id;

      

      foreach ($data['arraydata'] as  $key => $arraydata) {
        $product_id = $arraydata['product_id'];
        $product = Product::find($product_id);
        $arraydata['sale_id'] = $sale->id;
        $arraydata['stock_type_id'] = $data['stock_type_id'];
        $arraydata['budget_id'] = $data['budget_id'];
        $arraydata['forest_beat_id'] = $data['forest_beat_id'];
        $arraydata['forest_state_id'] = $data['forest_state_id'];
        $arraydata['forest_division_id'] = $data['forest_division_id'];
        $arraydata['forest_range_id'] = $data['forest_range_id'];

        $arraydata['division_id'] = @$data['division_id'];
        $arraydata['district_id'] = @$data['district_id'];
        $arraydata['upazila_id'] = @$data['upazila_id'];


        $arraydata['free'] = $data['free'];
        $arraydata['year'] = $year;
        $arraydata['code'] = $data['code'];
        $arraydata['total'] = $arraydata['price']*$arraydata['quantity'];
        $arraydata['unit_id'] = $product->unit_id;
        $arraydata['color_id'] = $product->color_id;
        $arraydata['age_id'] = $product->age_id;
        $arraydata['size_id'] = $product->size_id;
        $arraydata['vch_date'] = $data['vch_date'];
        $arraydata['user_id'] = $data['user_id'];
        $arraydata['created_by'] = $authUser;
        $arraydata['web'] = true;
        SaleDetail::create($arraydata);
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
    
    return redirect()->route('admin.sale.print',[$data['sale_id']])->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);

    return redirect()->route('admin.sale')->with([
                    'message' => __('admin.common.success'),
                    'alert-type' => 'success'
                ]);
  }

  public function edit(Request $request, $id)
  {
    $this->authorize('update',App\Sale::class);
    
    //validation
    $arr_id = [];
    $results = Sale::lwd()->orderBy('id','desc')->get();
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
    
    $sale = Sale::with('saleDetail')->where(['id'=>$id])->first();
    $sale_details = SaleDetail::with('product','unit')->where(['sale_id' => $sale->id])->get();

    $division = Division::find($sale->division_id);
    $forest_division = ForestDivision::find($sale->forest_division_id);

    //dd($forest_division);

    $nurseries = Nursery::lwd()->get();
    $stock_types = StockType::get();
    $categories = Category::get()->where('last',true);

    $products = Product::get();
    $units = Unit::get();

    $price_types = PriceType::get();
    $users = User::get();
    //return $user = User::find($sale->user_id);

    if ( is_null($sale) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    $authUser = Auth::guard('admin')->user()->load(['userType']);
    $default_role = $authUser->userType->default_role;
    if (Auth::guard('admin')->user()->userType->default_role <= Admin::DEFAULT_ROLE_LIST[5]) {
      $forest_beats = ForestBeat::lwd()->get();
    } else {
      $forest_beats = ForestBeat::where('id',$authUser->forest_beat_id)->get();
    }

    $budgets = Budget::get();

    return view(self::VIEW_PATH . 'add_edit', compact('sale','sale_details','stock_types','categories','nurseries','products','units','price_types','division','users','forest_beats','forest_division','budgets'));
  }

  public function update(Request $request, $id)
  {
    $this->authorize('update',App\Sale::class);
    //dd($request->all());
    $this->validate($request, [
      'stock_type_id' => 'required',
      'budget_id' => 'required',
      'vch_date' => 'required',
    ]);

    

    //validation
    $sale = Sale::find($id);
    $saleedit = Sale::find($id);
    $authUser = Auth::guard('admin')->user()->load(['userType']);
    if($authUser->userType->id == Admin::BO){
      if ($sale->app_status > 1) {
        return back()->with([
          'error' => __('admin.common.error_eligible_msg'),
          'alert-type' => 'error'
        ]);
      }
      
    } elseif($authUser->userType->id == Admin::RO){
    //code    
      if ($sale->app_status > 2) {
        return back()->with([
          'error' => __('admin.common.error_eligible_msg'),
          'alert-type' => 'error'
        ]);
      }
    //code          
    } elseif($authUser->userType->id == Admin::ACF){
    //code    
      if ($sale->app_status > 3) {
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

    $vat = SiteSetting::where(['status'=>1])->first()->vat;
    $total = 0;
    foreach ($request['arraydata'] as  $key => $arraydata){
      $total += $arraydata['price'] * $arraydata['quantity'];
    }

    $percent = ($request->percent) ? true : false ;
    $discount = $request->discount;
    $discount_amount = 0;
    if($percent){
      $discount_amount = ($discount*$total)/100;
    }else{
      $discount_amount = $discount;
    }
    $vat_amount = ($vat*$total)/100;
    $total_amount = ($total + $vat_amount) - $discount_amount;


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
      $sale = Sale::orderBy('id','DESC')->first();
      $data = $request->except('_token');
      $authUser = Auth::guard('admin')->user()->id;

      $year = date_format(date_create($request->vch_date),"Y");

      array_walk_recursive($data, function (&$val) {
          $val = trim($val);
          $val = is_string($val) && $val === '' ? null : $val;
      });

      // if ($sale) {
      //   $data['code'] = $sale->code + 1;
      // } else {
      //   $data['code'] = 1;
      // }

      $data['total_amount'] = $total_amount;
      $data['vat_amount'] = $vat_amount;
      $data['discount_amount'] = $discount_amount;

      $sale = Sale::find($id);
      $data['vch_date'] = date_format(date_create($request->vch_date),"Y-m-d");
      $data['updated_by'] = $authUser;

      $forest_beat = ForestBeat::find($data['forest_beat_id']);
      $data['forest_state_id'] = $forest_beat->forest_state_id;
      $data['forest_division_id'] = $forest_beat->forest_division_id;
      $data['forest_range_id'] = $forest_beat->forest_range_id;

      $data['division_id'] = @$forest_beat->division_id;
      $data['district_id'] = @$forest_beat->district_id;
      $data['upazila_id'] = @$forest_beat->upazila_id;


      $data['total'] = $total;
      $data['year'] = $year;
      $data['web'] = true;

      $sale->update($data);
      $data['sale_id'] = $sale->id;


      
      foreach ($data['arraydata'] as  $key => $arraydata) {
        if(@$arraydata['id']){

          $product_id = $arraydata['product_id'];
          $product = Product::find($product_id);
          $arraydata['sale_id'] = $sale->id;
          $arraydata['stock_type_id'] = $data['stock_type_id'];
          $arraydata['budget_id'] = $data['budget_id'];
          $arraydata['forest_beat_id'] = $data['forest_beat_id'];
          $arraydata['forest_state_id'] = $data['forest_state_id'];
          $arraydata['forest_division_id'] = $data['forest_division_id'];
          $arraydata['forest_range_id'] = $data['forest_range_id'];

          $arraydata['division_id'] = @$data['division_id'];
          $arraydata['district_id'] = @$data['district_id'];
          $arraydata['upazila_id'] = @$data['upazila_id'];

          $arraydata['code'] = $saleedit->code;
          $arraydata['free'] = $data['free'];
          $arraydata['year'] = $year;

          $arraydata['unit_id'] = $product->unit_id;
          $arraydata['color_id'] = $product->color_id;
          $arraydata['age_id'] = $product->age_id;
          $arraydata['size_id'] = $product->size_id;
          $arraydata['vch_date'] = $data['vch_date'];
          $arraydata['user_id'] = $data['user_id'];
          $arraydata['total'] = $arraydata['price']*$arraydata['quantity'];
          $arraydata['updated_by'] = $authUser;
          $arraydata['web'] = true;
          $sale_detail = SaleDetail::find($arraydata['id']);
          $sale_detail->update($arraydata);
        }else{
          $product_id = $arraydata['product_id'];
          $product = Product::find($product_id);
          $arraydata['sale_id'] = $sale->id;
          $arraydata['stock_type_id'] = $data['stock_type_id'];
          $arraydata['budget_id'] = $data['budget_id'];
          $arraydata['forest_beat_id'] = $data['forest_beat_id'];
          $arraydata['forest_state_id'] = $data['forest_state_id'];
          $arraydata['forest_division_id'] = $data['forest_division_id'];
          $arraydata['forest_range_id'] = $data['forest_range_id'];

          $arraydata['division_id'] = @$data['division_id'];
          $arraydata['district_id'] = @$data['district_id'];
          $arraydata['upazila_id'] = @$data['upazila_id'];

          $arraydata['code'] = $saleedit->code;
          $arraydata['year'] = $year;

          $arraydata['unit_id'] = $product->unit_id;
          $arraydata['color_id'] = $product->color_id;
          $arraydata['age_id'] = $product->age_id;
          $arraydata['size_id'] = $product->size_id;
          $arraydata['vch_date'] = $data['vch_date'];
          $arraydata['user_id'] = $data['user_id'];
          $arraydata['total'] = $arraydata['price']*$arraydata['quantity'];
          $arraydata['created_by'] = $authUser;
          $arraydata['updated_by'] = $authUser;
          $arraydata['web'] = true;
          SaleDetail::create($arraydata);
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
    

    return redirect()->route('admin.sale')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

  public function delete(Request $request, $id, $sid = 0)
  {    
    $this->authorize('delete',App\Sale::class);

    //validation
    $arr_id = [];
    $results = Sale::lwd()->orderBy('id','desc')->get();
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
    $sale = Sale::find($id);
    $authUser = Auth::guard('admin')->user()->load(['userType']);
    if($authUser->userType->id == Admin::BO){
      if ($sale->app_status > 1) {
        return back()->with([
          'error' => __('admin.common.error_eligible_msg'),
          'alert-type' => 'error'
        ]);
      }
      
    } elseif($authUser->userType->id == Admin::RO){
    //code    
      if ($sale->app_status > 2) {
        return back()->with([
          'error' => __('admin.common.error_eligible_msg'),
          'alert-type' => 'error'
        ]);
      }
    //code          
    } elseif($authUser->userType->id == Admin::ACF){
    //code    
      if ($sale->app_status > 3) {
        return back()->with([
          'error' => __('admin.common.error_eligible_msg'),
          'alert-type' => 'error'
        ]);
      }
    //code 
    }
    //validation


    $sale = Sale::find($id);
    if ( is_null($sale) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    try {
      DB::beginTransaction();
      if ($sid==false) {
        $sale->status = false;
        DB::update('update sale_details set status = 0 where sale_id = ?', [$id]);
        $sale->save();
      } else {
        DB::delete('delete from sale_details where sale_id = ?', [$id]);
        $sale->delete();
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
    
    return redirect()->route('admin.sale')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

  public function deletesaleDetails(Request $request, $id)
  {    
    $this->authorize('delete_sale_details',App\Sale::class);
    $sale_detail = SaleDetail::find($id);
    if ( is_null($sale_detail) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    //validation
    $arr_id = [];
    $results = Sale::lwd()->where('id',$sale_detail->sale_id)->orderBy('id','desc')->get();
    foreach ($results as $key => $result) {
      $arr_id[] = $result->id;
    }
    if (!in_array($sale_detail->sale_id, $arr_id))
    {
      return redirect()->route('admin.dashboard')->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }
    //validation

    //validation
    $sale = Sale::find($sale_detail->sale_id);
    $authUser = Auth::guard('admin')->user()->load(['userType']);
    if($authUser->userType->id == Admin::BO){
      if ($sale->app_status > 1) {
        return back()->with([
          'error' => __('admin.common.error_eligible_msg'),
          'alert-type' => 'error'
        ]);
      }
      
    } elseif($authUser->userType->id == Admin::RO){
    //code    
      if ($sale->app_status > 2) {
        return back()->with([
          'error' => __('admin.common.error_eligible_msg'),
          'alert-type' => 'error'
        ]);
      }
    //code          
    } elseif($authUser->userType->id == Admin::ACF){
    //code    
      if ($sale->app_status > 3) {
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
      $sale_detail->delete();
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
