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
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ForestBeatController extends Controller
{
  const VIEW_PATH = 'admin.purchase.';
  public function __construct()
  {
  }

  public function index()
  {
    
    $this->authorize('read', ForestBeat::class);
    if (Auth::guard('api')->user()->userType->default_role <= Admin::DEFAULT_ROLE_LIST[5]) {
      $forest_beats = ForestBeat::lwd()->get();
    } else {
      $forest_beats = ForestBeat::where('id',$authUser->forest_beat_id)->get();
    }
    
    return response()->json(["data"=>$forest_beats]);
  }
}
