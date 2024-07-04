<?php

namespace App\Http\Controllers\Api;

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
use App\Models\Sale;
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
    $this->authorize('read', Purchase::class);
    $purchases = Purchase::with('purchaseDetail', 'nursery', 'approvedBy', 'division', 'district', 'upazila', 'stockType', 'budget', 'forestBeat')->lwd()->orderBy('id', 'desc')->paginate(100);
    // return view(self::VIEW_PATH . 'index',compact('purchases'));
    return response()->json($purchases);
  }

  public function view(Request $request, $id)
  {
    $this->authorize('view', App\Purchase::class);

    $purchase = Purchase::with('purchaseDetail', 'stockType', 'nursery', 'approvedBy', 'division', 'district', 'upazila', 'budget', 'forestBeat')->lwd()->find($id);
    $purchase_details = PurchaseDetail::with('product', 'unit', 'category', 'stockType', 'priceType', 'forestBeat')->where(['purchase_id' => $purchase->id])->get()->toArray();
    $purchase = $purchase->toArray();
    $purchase['purchase_details'] = $purchase_details;
    return response()->json($purchase);
  }

  public function store(Request $request)
  {
    $this->authorize('create', App\Purchase::class);

    $validator = Validator::make($request->all(), [
      'stock_type_id' => 'required',
      'vch_date' => 'required',
    ]);
    if ($validator->fails()) {
      $message = "Please provide required fields";
      return response()->json(["message" => $message], 400);
    }
    foreach ($request['arraydata'] as  $key => $arraydata) {
      if (!isset($arraydata['product_id'])) {
        $message = 'পণ্য আইডি নির্বাচন করুন';
        return response()->json(["message" => $message], 400);
      } elseif (!isset($arraydata['quantity'])) {
        $message = 'পরিমাণ নির্বাচন করুন';
        return response()->json(["message" => $message], 400);
      }
    }



    $authUser = Auth::guard('api')->user()->load(['userType']);
    $default_role = $authUser->userType->default_role;
    file_put_contents('php://stderr', json_encode($authUser));
    file_put_contents('php://stderr', print_r(Auth::guard('api')->user()->userType->default_role, TRUE));
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

      $purchase = DB::table('purchases')->orderBy('id', 'DESC')->first();
      $data = $request->except('_token');
      $authUser = Auth::guard('api')->user()->id;

      $year = date_format(date_create($request->vch_date), "Y");

      array_walk_recursive($data, function (&$val) {
        $val = trim($val);
        $val = is_string($val) && $val === '' ? null : $val;
      });

      if ($purchase) {
        $data['code'] = $purchase->code + 1;
      } else {
        $data['code'] = 1;
      }


      $data['vch_date'] = date_format(date_create($request->vch_date), "Y-m-d");
      $data['created_by'] = $authUser;

      $forest_beat = ForestBeat::find($data['forest_beat_id']);
      $data['forest_state_id'] = $forest_beat->forest_state_id;
      $data['forest_division_id'] = $forest_beat->forest_division_id;
      $data['forest_range_id'] = $forest_beat->forest_range_id;

      $data['division_id'] = @$forest_beat->division_id;
      $data['district_id'] = @$forest_beat->district_id;
      $data['upazila_id'] = @$forest_beat->upazila_id;
      $data['year'] = $year;
      $data['web'] = false;
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
      $message = $exception->getMessage();
      return response()->json(["message" => $message], 400);
    }

    return response()->json(["message" => __('admin.common.success')]);
  }

  public function update(Request $request, $id)
  {
    $this->authorize('update', App\Purchase::class);

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
    $purchase = Purchase::find($id);
    $purchaseedit = Purchase::find($id);
    $authUser = Auth::guard('api')->user()->load(['userType']);
    if ($authUser->userType->id == Admin::BO) {
      if ($purchase->app_status > 1) {
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
      }
    } elseif ($authUser->userType->id == Admin::RO) {
      //code    
      if ($purchase->app_status > 2) {
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
      }
      //code          
    } elseif ($authUser->userType->id == Admin::ACF) {
      //code    
      if ($purchase->app_status > 3) {
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
      }
      //code 
    }
    //validation

    foreach ($request['arraydata'] as  $key => $arraydata) {
      if (!$arraydata['product_id']) {
        $message = 'পণ্য আইডি নির্বাচন করুন';
        return response()->json(["message" => $message], 400);
      } elseif (!$arraydata['quantity']) {
        $message = 'পরিমাণ নির্বাচন করুন';
        return response()->json(["message" => $message], 400);
      }

      $cid = $arraydata['category_id'];
      $pid = $arraydata['product_id'];

      $count = Product::where(['id' => $pid, 'category_id' => $cid])->count();
      if ($count == 0) {
        $message = 'এই পণ্য বিভাগের অন্তর্গত নয়';
        return response()->json(["message" => $message], 400);
      }
    }

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
      $purchase = Purchase::orderBy('id', 'DESC')->first();
      $data = $request->except('_token');
      $authUser = Auth::guard('api')->user()->id;

      $year = date_format(date_create($request->vch_date), "Y");

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
      $data['vch_date'] = date_format(date_create($request->vch_date), "Y-m-d");
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
        if (@$arraydata['id']) {

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
        } else {
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
      $message = $exception->getMessage();
      return response()->json(["message" => $message], 400);
    }

    return response()->json(["message" => __('admin.common.success')]);
  }

  public function deletepurchaseDetails(Request $request, $id)
  {
    $this->authorize('delete_purchase_details', App\Purchase::class);
    $purchase_detail = PurchaseDetail::find($id);
    if (is_null($purchase_detail) == true) {
      return response()->json(["message" => __('admin.common.error')], 404);
    }

    //validation
    $arr_id = [];
    $results = Purchase::lwd()->where('id', $purchase_detail->purchase_id)->orderBy('id', 'desc')->get();

    //dd($results);
    foreach ($results as $key => $result) {
      $arr_id[] = $result->id;
    }
    if (!in_array($purchase_detail->purchase_id, $arr_id)) {
      return response()->json(["message" => __('admin.common.error')], 400);
    }
    //validation

    //validation
    $purchase = Purchase::find($purchase_detail->purchase_id);
    $authUser = Auth::guard('api')->user()->load(['userType']);
    if ($authUser->userType->id == Admin::BO) {
      if ($purchase->app_status > 1) {
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
      }
    } elseif ($authUser->userType->id == Admin::RO) {
      //code    
      if ($purchase->app_status > 2) {
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
      }
      //code          
    } elseif ($authUser->userType->id == Admin::ACF) {
      //code    
      if ($purchase->app_status > 3) {
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
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
      $message = $exception->getMessage();
      return response()->json(["message" => $message], 400);
    }

    return response()->json(["message" => __('admin.common.success')]);
  }

  public function delete(Request $request, $id, $sid = 0)
  {
    $this->authorize('delete', App\Purchase::class);
    //validation
    $arr_id = [];
    $results = Purchase::lwd()->orderBy('id', 'desc')->get();
    foreach ($results as $key => $result) {
      $arr_id[] = $result->id;
    }
    if (!in_array($id, $arr_id)) {
      return response()->json(["message" => __('admin.common.error')], 404);
    }
    //validation

    //validation
    $purchase = Purchase::find($id);
    $authUser = Auth::guard('api')->user()->load(['userType']);
    if ($authUser->userType->id == Admin::BO) {
      if ($purchase->app_status > 1) {
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
      }
    } elseif ($authUser->userType->id == Admin::RO) {
      //code    
      if ($purchase->app_status > 2) {
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
      }
      //code          
    } elseif ($authUser->userType->id == Admin::ACF) {
      //code    
      if ($purchase->app_status > 3) {
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
      }
      //code 
    }
    //validation
    $purchase = Purchase::find($id);
    if (is_null($purchase) == true) {
      return response()->json(["message" => __('admin.common.error')], 400);
    }

    try {
      DB::beginTransaction();
      if ($sid == false) {
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
      $message = $exception->getMessage();
      return response()->json(["message" => $message], 400);
    }

    return response()->json(["message" => __('admin.common.success')]);
  }

  public function approval(Request $request, $id)
  {
    $this->authorize('approval', App\Purchase::class);
    $purchase = Purchase::find($id);
    if (is_null($purchase) == true) {
      return response()->json(["message" => __('admin.common.error')], 404);
    }

    try {
      DB::beginTransaction();
      $authUser = Auth::guard('api')->user()->load(['userType']);
      if ($authUser->userType->id == Admin::BO) {
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
      } elseif ($authUser->userType->id == Admin::RO) {
        //code    
        if ($purchase->app_status == 1) {
          $auth_id = Auth::guard('api')->user()->id;
          DB::update('update purchase_details set app_status = 2, approved_by = ? where purchase_id = ?', [$auth_id, $id]);
          $purchase->approved_by = $auth_id;
          $purchase->app_status = 2;
          $purchase->save();
        } elseif ($purchase->app_status == 2) {
          $auth_id = Auth::guard('api')->user()->id;
          DB::update('update purchase_details set app_status = 1, approved_by = ? where purchase_id = ?', [$auth_id, $id]);
          $purchase->approved_by = $auth_id;
          $purchase->app_status = 1;
          $purchase->save();
        } else {
          return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
        }
        //code          
      } elseif ($authUser->userType->id == Admin::ACF) {
        //code    
        if ($purchase->app_status == 2) {
          $auth_id = Auth::guard('api')->user()->id;
          DB::update('update purchase_details set app_status = 3, approved_by = ? where purchase_id = ?', [$auth_id, $id]);
          $purchase->approved_by = $auth_id;
          $purchase->app_status = 3;
          $purchase->save();
        } elseif ($purchase->app_status == 3) {
          $auth_id = Auth::guard('api')->user()->id;
          DB::update('update purchase_details set app_status = 2, approved_by = ? where purchase_id = ?', [$auth_id, $id]);
          $purchase->approved_by = $auth_id;
          $purchase->app_status = 2;
          $purchase->save();
        } else {
          return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
        }
        //code  
      } elseif ($authUser->userType->id == Admin::DFO) {
        //code    
        if ($purchase->app_status == 3) {
          $auth_id = Auth::guard('api')->user()->id;
          DB::update('update purchase_details set approved = 1, app_status = 4, approved_by = ? where purchase_id = ?', [$auth_id, $id]);
          $purchase->approved_by = $auth_id;
          $purchase->app_status = 4;
          $purchase->approved = true; // final approve
          $purchase->save();
        } elseif ($purchase->app_status == 4) {
          $auth_id = Auth::guard('api')->user()->id;
          DB::update('update purchase_details set approved = 0, app_status = 3, approved_by = ? where purchase_id = ?', [$auth_id, $id]);
          $purchase->approved_by = $auth_id;
          $purchase->app_status = 3;
          $purchase->approved = false; // final approve
          $purchase->save();
        } else {
          return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
        }
        //code  
      } else {
        return response()->json(["message" => __('admin.common.error_eligible_msg')], 403);
      }
      DB::commit();
    } catch (\Throwable $exception) {
      DB::rollBack();
      $message = $exception->getMessage();
      return response()->json(["message" => $message], 400);
    }

    return response()->json(["message" => __('admin.common.success')]);
  }


  public function comment(Request $request, $id)
{
    // Perform any necessary logic here

    $info = Purchase::find($id);
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
    $purchaseInfo = Purchase::find($id);

    if (!$purchaseInfo) {
        return response()->json([
            'error' => 'Sale not found',
            'alert-type' => 'error'
        ], 404);
    }

    if ($roleId == 7) {
        $purchaseInfo->update(['dfo_comment' => '']);
    } elseif ($roleId == 8) {
        $purchaseInfo->update(['acf_comment' => '']);
    } elseif ($roleId == 9) {
        $purchaseInfo->update(['range_comment' => '']);
    } else {
        return response()->json([
            'error' => __('admin.common.error_eligible_msg'),
            'alert-type' => 'error'
        ], 400);
    }

    if (empty($purchaseInfo->dfo_comment) && empty($purchaseInfo->acf_comment) && empty($purchaseInfo->range_comment)) {
        $purchaseInfo->update(['disapprove_status' => 0]);

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
  $info= Purchase::find($id);

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