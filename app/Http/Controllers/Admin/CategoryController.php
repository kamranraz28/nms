<?php

namespace App\Http\Controllers\Admin;
use App\Models\Category;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
  const VIEW_PATH = 'admin.category.';
  public function __construct()
  {

  }

  public function index()
  {
    $this->authorize('read',Category::class);
    $categories = Category::get();
    return view(self::VIEW_PATH . 'index',compact('categories'));
  }

  public function create()
  {
    $this->authorize('create',App\Category::class);
    $categories = Category::get();
    return view(self::VIEW_PATH . 'add_edit',compact('categories'));
  }

  public function store(Request $request)
  {
    $this->authorize('create',App\Category::class);
    //return $request->all();
    $this->validate($request, [
        //'name' => 'required|min:1|max:128',
        'title_en' => 'required|min:1|max:128',
        'title_bn' => 'required|min:1|max:128',
    ]);

    try {
      $category = DB::table('categories')->orderBy('id','DESC')->first();

      $data = $request->except('_token');
      $authUser = Auth::guard('admin')->user()->id;
      array_walk_recursive($data, function (&$val) {
          $val = trim($val);
          $val = is_string($val) && $val === '' ? null : $val;
      });

      if ($category) {
        $data['code'] = $category->code + 1;
      } else {
        $data['code'] = 1;
      }
      
      $data['created_by'] = $authUser;

      if ($data['parent_id']) {
        $category = Category::find($data['parent_id']);
        $category->last = false;
        $category->save();
        $data1['last'] = false;
        Category::find($data['parent_id'])->update($data1);
      }
      Category::create($data);
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }

    return redirect()->route('admin.category')->with([
                    'message' => __('admin.common.success'),
                    'alert-type' => 'success'
                ]);
  }

  public function edit(Request $request, $id)
  {
    $this->authorize('update',App\Category::class);
    $category = Category::where(['id'=>$id])->first();
    $categories = Category::where('id','!=',$id)->get();
    

    if ( is_null($category) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }
    return view(self::VIEW_PATH . 'add_edit', compact('category','categories'));
  }

  public function update(Request $request, $id)
  {
    $this->authorize('update',App\Category::class);
    //dd($request->all());
    $this->validate($request, [
      //'name' => 'required|min:1|max:128',
      'title_en' => 'required|min:1|max:128',
      'title_bn' => 'required|min:1|max:128',
    ]);

    try {
      $category = Category::where(['id'=>$id])->first();
      if ( is_null($category) == true) {
        return back()->with([
          'error' => __('admin.common.error'),
          'alert-type' => 'error'
        ]);
      }

      $parent = Category::where(['id'=>$request->parent_id])->first();
      if (@$parent->parent_id == $id) {
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
      if ($data['parent_id']) {
        $category = Category::find($data['parent_id']);
        $category->last = false;
        $category->save();
        $data['last'] = true;
        Category::find($id)->update($data);
      }else{
        Category::find($id)->update($data);
      }
  
      $categories = Category::get();
  
      foreach ($categories as $key => $category) {
        $id = $category->id;
        $parent_id = $category->parent_id;
        $count = Category::where('parent_id',$id)->count();
        if($count == 0 ){
          $category = Category::find($id);
          $category->last = true;
          $category->save();
        }
      }
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    return redirect()->route('admin.category')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

  public function delete(Request $request, $id, $sid = 0)
  {    
    $this->authorize('delete',App\Category::class);
    $category = Category::find($id);
    if ( is_null($category) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    try {
      if ($sid==false) {
        $category->status = false;
        $category->save();
      } else {
        if ($category->parent_id) {
          $parent_category = Category::find($category->parent_id);
          $parent_category->last = true;
          $parent_category->save();
        }
        $category->delete();
      }
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    
    return redirect()->route('admin.category')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

}
