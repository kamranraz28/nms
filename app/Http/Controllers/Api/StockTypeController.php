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

class StockTypeController extends Controller
{
  public function __construct()
  {
  }

  public function index()
  {
    $stock_types = StockType::get();
    // $price_types = PriceType::get();
    // $budgets = Budget::get();
    // return view(self::VIEW_PATH . 'index',compact('purchases'));
    return response()->json([
      "data"=>$stock_types,
      "per_page"=> count($stock_types),
      "totalPages"=>1,
      "current_page"=>1
    ]);
  }

}