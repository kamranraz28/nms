<?php

namespace App\Http\Controllers\Admin;
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

  public function index()
  {
    $this->authorize('read',Product::class);
    $products = Product::get();
    return view(self::VIEW_PATH . 'index',compact('products'));
  }

  public function create()
  {
    $this->authorize('create',App\Product::class);
    $sizes = Size::get();
    $colors = Color::get();
    $ages = Age::get();
    $units = Unit::get();
    $categories = Category::where('last',1)->get();
    return view(self::VIEW_PATH . 'add_edit',compact('sizes','colors','ages','units','categories'));
  }

  public function store(Request $request)
  {
    $this->authorize('create',App\Product::class);
    //return $request->all();
    $this->validate($request, [
        //'name' => 'required|min:1|max:128',
        'title_en' => 'required|min:1|max:128',
        'title_bn' => 'required|min:1|max:128',
        //'percent' => 'required',
        'saleable' => 'required',
        'unit_id' => 'required',
        'category_id' => 'required',
        'category_id' => 'required',
    ]);

    try {
      $product = DB::table('products')->orderBy('id','DESC')->first();
      $data = $request->except('_token');
      $authUser = Auth::guard('admin')->user()->id;
      array_walk_recursive($data, function (&$val) {
          $val = trim($val);
          $val = is_string($val) && $val === '' ? null : $val;
      });

      if ($product) {
        $data['code'] = $product->code + 1;
      } else {
        $data['code'] = 1;
      }
      
      $data['created_by'] = $authUser;

      if ($request->hasFile('thumb')) {
        $thumb = $request->file('thumb');
        $randNumber = rand(1, 999);
        $name = 'thumb-' . $authUser . '-' . time() . $randNumber . '.' . $thumb->getClientOriginalExtension();
        $year = date('Y/');
        $month = date('F/');
        $destinationPath = storage_path('app/public/product/' . $year . $month);
        $thumb->move($destinationPath, $name);
        $filePath = 'product/' . $year . $month;
        $data['thumb'] = $filePath . '' . $name;
      }

      @$data['scientific_bn'] = (@$data['scientific_en']) ? gtrans_backend(@$data['scientific_en']) : null ;

      Product::create($data);
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }

    return redirect()->route('admin.product')->with([
                    'message' => __('admin.common.success'),
                    'alert-type' => 'success'
                ]);
  }

  public function edit(Request $request, $id)
  {
    $this->authorize('update',App\Product::class);
    $product = Product::where(['id'=>$id])->first();
    if ( is_null($product) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    $sizes = Size::get();
    $colors = Color::get();
    $ages = Age::get();
    $units = Unit::get();
    $categories = Category::where('last',1)->get();
    return view(self::VIEW_PATH . 'add_edit', compact('product','sizes','colors','units','categories','ages'));
  }

  public function update(Request $request, $id)
  {
    $this->authorize('update',App\Product::class);
    //dd($request->all());
    $this->validate($request, [
      //'name' => 'required|min:1|max:128',
      'title_en' => 'required|min:1|max:128',
      'title_bn' => 'required|min:1|max:128',
      //'percent' => 'required',
      'saleable' => 'required',
      'unit_id' => 'required',
      'category_id' => 'required',
      'category_id' => 'required',
  ]);

    try {
      $product = Product::where(['id'=>$id])->first();
      if ( is_null($product) == true) {
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

      if ($request->hasFile('thumb')) {
        @unlink(storage_path('/app/public/' . $product->thumb));
        $thumb = $request->file('thumb');
        $randNumber = rand(1, 999);
        $name = 'thumb-' . $authUser . '-' . time() . $randNumber . '.' . $thumb->getClientOriginalExtension();
        $year = date('Y/');
        $month = date('F/');
        $destinationPath = storage_path('app/public/product/' . $year . $month);
        $thumb->move($destinationPath, $name);
        $filePath = 'product/' . $year . $month;
        $data['thumb'] = $filePath . '' . $name;
      }

      @$data['scientific_bn'] = (@$data['scientific_en']) ? gtrans_backend(@$data['scientific_en']) : null ;

      Product::find($id)->update($data);
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    return redirect()->route('admin.product')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

  public function delete(Request $request, $id, $sid = 0)
  {    
    $this->authorize('delete',App\Product::class);
    $product = Product::find($id);
    if ( is_null($product) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    try {
      if ($sid==false) {
        $product->status = false;
        $product->save();
      } else {
        @unlink(storage_path('/app/public/' . $product->thumb));
        $product->delete();
      }
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    
    return redirect()->route('admin.product')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

}
