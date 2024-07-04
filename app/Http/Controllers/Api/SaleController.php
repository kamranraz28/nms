<?php

namespace App\Http\Controllers\Api;
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
    $sales = Sale::with('saleDetail','nursery','approvedBy','division','district','upazila','stockType','budget', 'forestBeat')->lwd()->orderBy('id','desc')->paginate(100);
    return response()->json($sales);
  }

  public function view(Request $request, $id)
  {
    $this->authorize('view',App\Sale::class);

    $sale = Sale::with('saleDetail','stockType', 'nursery','approvedBy','division','district','upazila','budget','forestBeat')->lwd()->find($id);
    $sale_details = SaleDetail::with('product','unit','category','stockType','priceType','forestBeat')->where(['sale_id' => $sale->id])->get();
    $sale = $sale->toArray();
    $sale['sale_details'] = $sale_details;
    return response()->json($sale);
  }

  public function store(Request $request)
  {
    $this->authorize('create',App\Sale::class);
    

    $validator = Validator::make($request->all(), [
      'stock_type_id' => 'required',
      'budget_id' => 'required',
      'vch_date' => 'required',
    ]);
    if ($validator->fails()) {
      $message = "Please provide required fields";
      return response()->json(["message" => $message], 400);
    }


    foreach ($request['arraydata'] as  $key => $arraydata){
      if (!$arraydata['product_id']) {
        $message = 'পণ্য আইডি নির্বাচন করুন';
        return response()->json(["message" => $message], 400);
      }elseif(!$arraydata['quantity']){
        $message = 'পরিমাণ নির্বাচন করুন';
        return response()->json(["message" => $message], 400);
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

    $authUser = Auth::guard('api')->user()->load(['userType']);
    $default_role = $authUser->userType->default_role;
    if (Auth::guard('api')->user()->userType->default_role <= Admin::DEFAULT_ROLE_LIST[5]) {
      //Validator
      $validator = Validator::make($request->all(), [
        'forest_beat_id' => 'required',
      ]);
      if ($validator->fails()) {
        $message = "Please provide required fields";
        return response()->json(["message" => $message], 400);
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
      $authUser = Auth::guard('api')->user()->id;

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
      $message = $exception->getMessage();
      return response()->json(["message" => $message], 400);
    }
    
    return response()->json(["message" => __('admin.common.success')]);
  }

  public function update(Request $request, $id)
  {
    $this->authorize('update',App\Sale::class);
    
    $validator = Validator::make($request->all(), [
      'stock_type_id' => 'required',
      'budget_id' => 'required',
      'vch_date' => 'required',
    ]);
    if ($validator->fails()) {
      $message = "Please provide required fields";
      return response()->json(["message" => $message], 400);
    }    

    //validation
    $sale = Sale::find($id);
    $saleedit = Sale::find($id);
    $authUser = Auth::guard('api')->user()->load(['userType']);
    if($authUser->userType->id == Admin::BO){
      if ($sale->app_status > 1) {
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
      }
      
    } elseif($authUser->userType->id == Admin::RO){
    //code    
      if ($sale->app_status > 2) {
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
      }
    //code          
    } elseif($authUser->userType->id == Admin::ACF){
    //code    
      if ($sale->app_status > 3) {
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
      }
    //code 
    }
    //validation

    foreach ($request['arraydata'] as  $key => $arraydata){
      if (!$arraydata['product_id']) {
        $message = 'পণ্য আইডি নির্বাচন করুন';
        return response()->json(["message" => $message], 400);
      } elseif (!$arraydata['quantity']) {
        $message = 'পরিমাণ নির্বাচন করুন';
        return response()->json(["message" => $message], 400);
      }

      $cid = $arraydata['category_id'];
      $pid = $arraydata['product_id'];

      $count = Product::where(['id'=>$pid, 'category_id'=>$cid])->count();
      if ($count == 0) {
        $message = 'এই পণ্য বিভাগের অন্তর্গত নয়';
        return response()->json(["message" => $message], 400);
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


    $authUser = Auth::guard('api')->user()->load(['userType']);
    $default_role = $authUser->userType->default_role;
    if (Auth::guard('api')->user()->userType->default_role <= Admin::DEFAULT_ROLE_LIST[5]) {
      //Validator
      $validator = Validator::make($request->all(), [
        'forest_beat_id' => 'required',
      ]);
      if ($validator->fails()) {
        $message = "Please provide required fields";
        return response()->json(["message" => $message], 400);
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
      $authUser = Auth::guard('api')->user()->id;

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
      $message = $exception->getMessage();
      return response()->json(["message" => $message], 400);
    }
    

    return response()->json(["message" => __('admin.common.success')]);
  }

  public function deletesaleDetails(Request $request, $id)
  {    
    $this->authorize('delete_sale_details',App\Sale::class);
    $sale_detail = SaleDetail::find($id);
    if ( is_null($sale_detail) == true) {
      return response()->json(["message" => __('admin.common.error')], 404);
    }

    //validation
    $arr_id = [];
    $results = Sale::lwd()->where('id',$sale_detail->sale_id)->orderBy('id','desc')->get();
    foreach ($results as $key => $result) {
      $arr_id[] = $result->id;
    }
    if (!in_array($sale_detail->sale_id, $arr_id))
    {
      return response()->json(["message" => __('admin.common.error')], 400);
    }
    //validation

    //validation
    $sale = Sale::find($sale_detail->sale_id);
    $authUser = Auth::guard('api')->user()->load(['userType']);
    if($authUser->userType->id == Admin::BO){
      if ($sale->app_status > 1) {
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
      }
      
    } elseif($authUser->userType->id == Admin::RO){
    //code    
      if ($sale->app_status > 2) {
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
      }
    //code          
    } elseif($authUser->userType->id == Admin::ACF){
    //code    
      if ($sale->app_status > 3) {
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
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
      $message = $exception->getMessage();
      return response()->json(["message" => $message], 400);
    }
    
    return response()->json(["message" => __('admin.common.success')]);
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
      return response()->json(["message" => __('admin.common.error')], 404);
    }
    //validation

    //validation
    $sale = Sale::find($id);
    $authUser = Auth::guard('api')->user()->load(['userType']);
    if($authUser->userType->id == Admin::BO){
      if ($sale->app_status > 1) {
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
      }
      
    } elseif($authUser->userType->id == Admin::RO){
    //code    
      if ($sale->app_status > 2) {
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
      }
    //code          
    } elseif($authUser->userType->id == Admin::ACF){
    //code    
      if ($sale->app_status > 3) {
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
      }
    //code 
    }
    //validation


    $sale = Sale::find($id);
    if ( is_null($sale) == true) {
      return response()->json(["message" => __('admin.common.error')], 400);
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
      $message = $exception->getMessage();
      return response()->json(["message" => $message], 400);
    }
    
    return response()->json(["message" => __('admin.common.success')]);
  }

  public function approval(Request $request, $id)
  {
    $this->authorize('approval',App\Sale::class);
    $sale = Sale::find($id);
    if ( is_null($sale) == true) {
      return response()->json(["message" => __('admin.common.error')], 404);
    }

    try {
      DB::beginTransaction();
      $authUser = Auth::guard('api')->user()->load(['userType']);
      if($authUser->userType->id == Admin::BO){
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
      } elseif($authUser->userType->id == Admin::RO){
        //code    
        if ($sale->app_status == 1) {
          $auth_id = Auth::guard('api')->user()->id;
          DB::update('update sale_details set app_status = 2, approved_by = ? where sale_id = ?', [$auth_id,$id]);
          $sale->approved_by = $auth_id;
          $sale->app_status = 2;
          $sale->save();
        }elseif($sale->app_status == 2){
          $auth_id = Auth::guard('api')->user()->id;
          DB::update('update sale_details set app_status = 1, approved_by = ? where sale_id = ?', [$auth_id,$id]);
          $sale->approved_by = $auth_id;
          $sale->app_status = 1;
          $sale->save();
        } else {
          return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
        }
        //code          
      } elseif($authUser->userType->id == Admin::ACF){
        //code    
        if ($sale->app_status == 2) {
          $auth_id = Auth::guard('api')->user()->id;
          DB::update('update sale_details set app_status = 3, approved_by = ? where sale_id = ?', [$auth_id,$id]);
          $sale->approved_by = $auth_id;
          $sale->app_status = 3;
          $sale->save();
        }elseif($sale->app_status == 3){
          $auth_id = Auth::guard('api')->user()->id;
          DB::update('update sale_details set app_status = 2, approved_by = ? where sale_id = ?', [$auth_id,$id]);
          $sale->approved_by = $auth_id;
          $sale->app_status = 2;
          $sale->save();
        } else {
          return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
        }
        //code  
      } elseif($authUser->userType->id == Admin::DFO){
        //code    
        if ($sale->app_status == 3) {
          $auth_id = Auth::guard('api')->user()->id;
          DB::update('update sale_details set approved = 1, app_status = 4, approved_by = ? where sale_id = ?', [$auth_id,$id]);
          $sale->approved_by = $auth_id;
          $sale->app_status = 4;
          $sale->approved = true; // final approve
          $sale->save();
        }elseif($sale->app_status == 4){
          $auth_id = Auth::guard('api')->user()->id;
          DB::update('update sale_details set approved = 0, app_status = 3, approved_by = ? where sale_id = ?', [$auth_id,$id]);
          $sale->approved_by = $auth_id;
          $sale->app_status = 3;
          $sale->approved = false; // final approve
          $sale->save();
        } else {
          return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
        }
        //code  
      } else{
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
      }
      DB::commit();
    } catch (\Throwable $exception) {
      DB::rollBack();
      return response()->json(["message" => $message], 400);
    }
    
    return response()->json(["message" => __('admin.common.success')]);

  }

  public function comment(Request $request, $id)
{
    // Perform any necessary logic here

    $info = Sale::find($id);
    if (!$info) {
        return response()->json([
            'error' => 'Sale not found',
            'alert-type' => 'error'
        ], 404);
    }

    $user_id = $request->user_id;
    $comment = $request->comment;
    $UserInfo = Admin::find($user_id);

    if (!$UserInfo) {
        return response()->json([
            'error' => 'User not found',
            'alert-type' => 'error'
        ], 404);
    }

    $roleId = $UserInfo->role_id;
    $status = $info->app_status;

    if ($status != 4) {
        $updated = false;

        if ($roleId == 9 && $status == 1) {
            $info->update(['range_comment' => $comment]);
            $updated = true;
        } elseif ($roleId == 8 && $status == 2) {
            $info->update(['acf_comment' => $comment]);
            $updated = true;
        } elseif ($roleId == 7 && $status == 3) {
            $info->update(['dfo_comment' => $comment]);
            $updated = true;
        }

        if ($updated) {
            if ($info->range_comment || $info->acf_comment || $info->dfo_comment) {
                $info->update([
                    'disapprove_status' => 1,
                    'app_status' => 1,
                    'approved' => 0,
                ]);
            }

            return response()->json([
                'success' => 'Comment added successfully',
                'alert-type' => 'success',
                'request_data' => $request->all()  // return all request data
            ], 200);
        }
    }

    return response()->json([
        'error' => __('admin.common.error_eligible_msg'),
        'alert-type' => 'error'
    ], 400);
}


public function comment_reverse(Request $request, $id)
{
    $user_id = $request->user_id;
    $UserInfo = Admin::find($user_id);

    if (!$UserInfo) {
        return response()->json([
            'error' => 'User not found',
            'alert-type' => 'error'
        ], 404);
    }

    $roleId = $UserInfo->role_id;
    $saleInfo = Sale::find($id);

    if (!$saleInfo) {
        return response()->json([
            'error' => 'Sale not found',
            'alert-type' => 'error'
        ], 404);
    }

    if ($roleId == 7) {
        $saleInfo->update(['dfo_comment' => '']);
    } elseif ($roleId == 8) {
        $saleInfo->update(['acf_comment' => '']);
    } elseif ($roleId == 9) {
        $saleInfo->update(['range_comment' => '']);
    } else {
        return response()->json([
            'error' => __('admin.common.error_eligible_msg'),
            'alert-type' => 'error'
        ], 400);
    }

    if (empty($saleInfo->dfo_comment) && empty($saleInfo->acf_comment) && empty($saleInfo->range_comment)) {
        $saleInfo->update(['disapprove_status' => 0]);

        return response()->json([
            'success' => 'Comment reversed successfully',
            'alert-type' => 'success',
            'request_data' => $request->all()
        ], 200);
    } else {
        return response()->json([
            'error' => __('admin.common.error_eligible_msg'),
            'alert-type' => 'error'
        ], 400);
    }
}

public function comment_view($id)
{
  $info= Sale::find($id);

  if($info->range_comment!== null)
  {
    $commentUser = "Range Comment";
    $comment = $info->range_comment;
  }elseif($info->acf_comment!== null)
  {
    $commentUser = "ACF Comment";
    $comment = $info->acf_comment;
  }elseif($info->dfo_comment!== null)
  {
    $commentUser = "DFO Comment";
    $comment = $info->dfo_comment;
  }
  
    return response()->json([
        'success' => 'Comment showing successfully',
        'alert-type' => 'success',
        'comment-type' => $commentUser,
        'comment' => $comment
    ], 200);
}





}