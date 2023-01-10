<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Models\ContentCategory;
use App\Http\Controllers\Controller;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;


class ContentCategoryController extends Controller
{
  const VIEW_PATH = 'admin.content_category.';
  public function __construct()
  {

  }

  public function index()
  {
    $this->authorize('read',ContentCategory::class);
    $categories = ContentCategory::with('contentCategory')->where(['status'=>1])->orderBy('sort','asc')->get();
    //$categories = ContentCategory::with('allChildren')->where(['status'=>1,'parent_id'=>null])->orderBy('sort','asc')->get();
    //dd($categories);
    return view(self::VIEW_PATH . 'index',compact('categories'));
  }

  public function create()
  {
    $this->authorize('create',ContentCategory::class);
    $contentCategories = ContentCategory::where(['status'=>1])->orderBy('sort','asc')->pluck('name','id');
    return view(self::VIEW_PATH . 'add_edit', compact('contentCategories'));
  }

  public function store(Request $request)
  {
    $this->authorize('create',ContentCategory::class);
    //return $request->all();
    //dd($request->all());
    $this->validate($request, [
        //'thumb' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:20000',
        //'thumb' => 'required',
        'sort' => 'required',
        'name' => 'required|min:1|max:128',
        'position' => 'required'
    ]);

    if ($request->position == 'body') {
      $count = ContentCategory::where(['position'=>$request->position])->count();
      if ($count > 0) {
        return back()->with([
          'error' => __('admin.common.error'),
          'alert-type' => 'error'
        ]);
      }
    }

    $data = $request->except('_token');
    $authUser = Auth::guard('admin')->user()->id;
    array_walk_recursive($data, function (&$val) {
        $val = trim($val);
        $val = is_string($val) && $val === '' ? null : $val;
    });

    $data['created_by'] = $authUser;

    if ($request->hasFile('thumb')) {
      $thumb = $request->file('thumb');
      $randNumber = rand(1, 999);
      $name = 'thumb-' . $authUser . '-' . time() . $randNumber . '.' . $thumb->getClientOriginalExtension();
      $year = date('Y/');
      $month = date('F/');
      $destinationPath = storage_path('app/public/content_category/' . $year . $month);
      $thumb->move($destinationPath, $name);
      $filePath = 'content_category/' . $year . $month;
      $data['thumb'] = $filePath . '' . $name;
    }

    if ($data['parent_id']) {
      $category = ContentCategory::find($data['parent_id']);
      $category->last = false;
      $category->save();
      $data1['last'] = false;
      ContentCategory::find($data['parent_id'])->update($data1);
    }

    ContentCategory::create($data);
    Cache::forget('content_categories');
    return redirect()->route('admin.content_category')->with([
                    'message' => __('admin.common.success'),
                    'alert-type' => 'success'
                ]);
  }

  public function edit(Request $request, $id)
  {
    $this->authorize('update',ContentCategory::class);
    $content_category = ContentCategory::where(['id'=>$id])->first();
    $contentCategories = ContentCategory::where(['status'=>1])->where('id','!=',$id)->orderBy('sort','asc')->pluck('name','id');
    //dd($id);
    //dd($contentCategories);
    return view(self::VIEW_PATH . 'add_edit', compact('content_category','contentCategories'));
  }

  public function update(Request $request, $id)
  {
    $this->authorize('update',ContentCategory::class);
    //dd($request->all());
    $this->validate($request, [
      //'thumb' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:20000',
      //'thumb' => 'required',
      'sort' => 'required',
      'name' => 'required|min:1|max:128',
      'position' => 'required'
  ]);

    $content_category = ContentCategory::where(['id'=>$id])->first();
    if ( is_null($content_category) == true) {
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
      @unlink(storage_path('/app/public/' . $content_category->thumb));
      $thumb = $request->file('thumb');
      $randNumber = rand(1, 999);
      $name = 'thumb-' . $authUser . '-' . time() . $randNumber . '.' . $thumb->getClientOriginalExtension();
      $year = date('Y/');
      $month = date('F/');
      $destinationPath = storage_path('app/public/content_category/' . $year . $month);
      $thumb->move($destinationPath, $name);
      $filePath = 'content_category/' . $year . $month;
      $data['thumb'] = $filePath . '' . $name;
    }


    if ($data['parent_id']) {
      $category = ContentCategory::find($data['parent_id']);
      $category->last = false;
      $category->save();
      $data['last'] = true;
      ContentCategory::find($id)->update($data);
    }else{
      ContentCategory::find($id)->update($data);
    }

    $categories = ContentCategory::get();

    foreach ($categories as $key => $category) {
      $id = $category->id;
      $parent_id = $category->parent_id;
      $count = ContentCategory::where('parent_id',$id)->count();
      if($count == 0 ){
        $category = ContentCategory::find($id);
        $category->last = true;
        $category->save();
      }
    }



    Cache::forget('content_categories');

    return redirect()->route('admin.content_category')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);

  }

  public function delete(Request $request, $id)
  {
    $this->authorize('delete',ContentCategory::class);
    $content_category = ContentCategory::find($id);
    if ( is_null($content_category) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    try {
      @unlink(storage_path('/app/public/' . $content_category->thumb));
      $content_category->delete();
      Cache::forget('content_categories');
    } catch (\Throwable $th) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    return redirect()->route('admin.content_category')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }


}
