<?php

namespace App\Http\Controllers\Api;
use App\Models\Age;
use App\Models\Size;

use App\Models\Unit;
use App\Models\Color;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
  const VIEW_PATH = 'admin.product.';
  public function __construct()
  {

  }

  public function categoryIndex()
  {
    $categories = Category::where('last',1)->get();
    return response()->json([
      "data"=>$categories,
      "size"=> count($categories),
      "totalPages"=>1,
      "pageNumber"=>1
    ]);
  }

  public function productIndex($id=0){
    $products = [];
    if ($id > 0) {
      $products = Product::with('size','color','age','unit','category')->where('category_id',$id)->get();
    }
    return response()->json([
      "data"=>$products,
      "size"=> count($products),
      "totalPages"=>1,
      "pageNumber"=>1
    ]);
  }

}