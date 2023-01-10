<?php

namespace App\Http\Controllers\Admin;
use App\Models\Content;
use App\Models\Version;
use Illuminate\Http\Request;

use App\Models\ContentCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;


class ContentController extends Controller
{
  const VIEW_PATH = 'admin.content.';
  public function __construct()
  {

  }

  public function index()
  {
    $this->authorize('read',Content::class);
    $contents = Content::with('version','contentCategory')->where(['status'=>1])->orderBy('sort','asc')->get();
    //dd($versions);
    return view(self::VIEW_PATH . 'index',compact('contents'));
  }

  public function create()
  {
    $this->authorize('create',Content::class);
    $versions = Version::where(['status'=>1])->orderBy('sort','asc')->pluck('name','id');
    $contentCategories = ContentCategory::where(['status'=>1,'last'=>1])->orderBy('sort','asc')->pluck('name','id');
    return view(self::VIEW_PATH . 'add_edit',compact('versions','contentCategories'));
  }

  public function store(Request $request)
  {
    $this->authorize('create',Content::class);
    $this->validate($request, [
        'thumb' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:20000',
        //'thumb' => 'required',
        'sort' => 'required',
        //'version_id' => 'required',
        'content_category_id' => 'required',
        'name' => 'required|min:1|max:128',
        'sub_title' => 'required|min:1|max:128',
        'slug' => 'required|min:1|max:128|unique:contents',
    ]);

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
      $destinationPath = storage_path('app/public/content/' . $year . $month);
      $thumb->move($destinationPath, $name);
      $filePath = 'content/' . $year . $month;
      $data['thumb'] = $filePath . '' . $name;
    }

    Content::create($data);
    Cache::forget('content_categories');

    return redirect()->route('admin.content')->with([
                    'message' => __('admin.common.success'),
                    'alert-type' => 'success'
                ]);
  }

  public function edit(Request $request, $id)
  {
    $this->authorize('update',Content::class);
    $content = Content::with('version','contentCategory')->where(['id'=>$id])->first();
    $versions = Version::where(['status'=>1])->orderBy('sort','asc')->pluck('name','id');
    $contentCategories = ContentCategory::where(['status'=>1,'last'=>1])->orderBy('sort','asc')->pluck('name','id');
    return view(self::VIEW_PATH . 'add_edit', compact('content','versions','contentCategories'));
  }

  public function update(Request $request, $id)
  {
    $this->authorize('update',Content::class);
    //dd($request->all());
    $this->validate($request, [
      'thumb' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:20000',
      //'thumb' => 'required',
      'sort' => 'required',
      //'version_id' => 'required',
      'content_category_id' => 'required',
      'name' => 'required|min:1|max:128',
      'sub_title' => 'required|min:1|max:128',
      'slug' => 'required|min:1|max:128|unique:contents,slug,'.$id,
    ]);

    $content = Content::where(['id'=>$id])->first();
    if ( is_null($content) == true) {
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
      @unlink(storage_path('/app/public/' . $content->thumb));
      $thumb = $request->file('thumb');
      $randNumber = rand(1, 999);
      $name = 'thumb-' . $authUser . '-' . time() . $randNumber . '.' . $thumb->getClientOriginalExtension();
      $year = date('Y/');
      $month = date('F/');
      $destinationPath = storage_path('app/public/content/' . $year . $month);
      $thumb->move($destinationPath, $name);
      $filePath = 'content/' . $year . $month;
      $data['thumb'] = $filePath . '' . $name;
    }

    Content::find($id)->update($data);
    Cache::forget('content_categories');

    return redirect()->route('admin.content')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);

  }

  public function delete(Request $request, $id)
  {
    $this->authorize('delete',Content::class);
    $content = Content::find($id);
    if ( is_null($content) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    @unlink(storage_path('/app/public/' . $content->thumb));
    $content->delete();
    Cache::forget('content_categories');

    return redirect()->route('admin.content')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

  public function bodyDetails(Request $request, $id)
  {
    $this->authorize('delete',Content::class);
    $content = Content::find($id);
    return view(self::VIEW_PATH . 'view',compact('content'));
  }


  public function imageUpload(Request $request)
  {
    //dd($request);
    $authUser = Auth::guard('admin')->user()->id;
    if ($request->hasFile('file')) {
      //@unlink(storage_path('/app/public/' . $content->file));
      $file = $request->file('file');
      $randNumber = rand(1, 999);
      $name = 'file-' . $authUser . '-' . time() . $randNumber . '.' . $file->getClientOriginalExtension();
      $year = date('Y/');
      $month = date('F/');
      $destinationPath = storage_path('app/public/tinymce/' . $year . $month);
      $file->move($destinationPath, $name);
      $filePath = 'tinymce/' . $year . $month;
      $data['location'] = $filePath . '' . $name;

      return $data;
    }
  }


}
