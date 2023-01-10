<?php

namespace App\Http\Controllers\Admin;
use App\Models\Admin;
use App\Models\UserType;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserTypeController extends Controller
{
    const VIEW_PATH = 'admin.user_type.';
    public function __construct()
    {
  
    }
  
    public function index()
    {
      $this->authorize('read',UserType::class);
      //return $authUser = Auth::guard('admin')->user()->load(['userType']);
      //return $authUser->userType->default_role;
      //return Admin::DEFAULT_ROLE_LIST[1];
      $user_types = UserType::utwd()->orderBy('sort','asc')->get();
      return view(self::VIEW_PATH . 'index',compact('user_types'));
    }
  
    public function create()
    {
      $this->authorize('create',App\UserType::class);
      return view(self::VIEW_PATH . 'add_edit');
    }
  
    public function store(Request $request)
    {
      $this->authorize('create',App\UserType::class);
      //return $request->all();
      $this->validate($request, [
          //'name' => 'required|min:1|max:128',
          'title_en' => 'required|min:1|max:128',
          'title_bn' => 'required|min:1|max:128',
      ]);

      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);

      try {
        $user_type = DB::table('user_types')->orderBy('id','DESC')->first();
        $data = $request->except('_token');
        $authUser = Auth::guard('admin')->user()->id;
        array_walk_recursive($data, function (&$val) {
            $val = trim($val);
            $val = is_string($val) && $val === '' ? null : $val;
        });
    
        $data['code'] = $user_type->code + 1;
        $data['created_by'] = $authUser;
        UserType::create($data);
      } catch (\Throwable $exception) {
        return back()->with([
          //'error' => __('admin.common.error'),
          'error' => $exception->getMessage(),
          'alert-type' => 'error'
        ]);
      }
      return redirect()->route('admin.user_type')->with([
                      'message' => __('admin.common.success'),
                      'alert-type' => 'success'
                  ]);
    }
  
    public function edit(Request $request, $id)
    {
      $this->authorize('update',App\UserType::class);

      //validation
        $arr_id = [];
        $results = UserType::utwd()->get();
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

      $user_type = UserType::where(['id'=>$id])->first();
      if ( is_null($user_type) == true) {
        return back()->with([
          'error' => __('admin.common.error'),
          'alert-type' => 'error'
        ]);
      }
      return view(self::VIEW_PATH . 'add_edit', compact('user_type'));
    }
  
    public function update(Request $request, $id)
    {
      $this->authorize('update',App\UserType::class);
      //dd($request->all());
      $this->validate($request, [
        //'name' => 'required|min:1|max:128',
        'title_en' => 'required|min:1|max:128',
        'title_bn' => 'required|min:1|max:128',
      ]);

      try {
        $user_type = UserType::where(['id'=>$id])->first();
        if ( is_null($user_type) == true) {
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

        $authUser = Auth::guard('admin')->user()->load(['userType']);
        if (Admin::DEFAULT_ROLE_LIST[1] != $authUser->userType->default_role) {
          // return back()->with([
          //   'error' => __('admin.common.error'),
          //   'alert-type' => 'error'
          // ]);

          $data['default_role'] = $user_type->default_role;
        }


        UserType::find($id)->update($data);
      } catch (\Throwable $exception) {
        return back()->with([
          //'error' => __('admin.common.error'),
          'error' => $exception->getMessage(),
          'alert-type' => 'error'
        ]);
      }

      return redirect()->route('admin.user_type')->with([
        'message' => __('admin.common.success'),
        'alert-type' => 'success'
      ]);
    }
  
    public function delete(Request $request, $id, $sid=0)
    {    
      $this->authorize('delete',App\UserType::class);

      //validation
      $arr_id = [];
      $results = UserType::utwd()->get();
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

      $user_type = UserType::find($id);
      if ( is_null($user_type) == true) {
        return back()->with([
          'error' => __('admin.common.error'),
          'alert-type' => 'error'
        ]);
      }

      if ($user_type->id <= Admin::BO) {
        return back()->with([
          'error' => __('admin.common.error'),
          'alert-type' => 'error'
        ]);
      }

      try {
        if ($sid==false) {
          $user_type->status = false;
          $user_type->save();
        } else {
          if ($user_type->id <= Admin::BO) {
            return back()->with([
              'error' => __('admin.common.error'),
              'alert-type' => 'error'
            ]);
          }
          $user_type->delete();
        }
      } catch (\Throwable $exception) {
        return back()->with([
          //'error' => __('admin.common.error'),
          'error' => $exception->getMessage(),
          'alert-type' => 'error'
        ]);
      }
      return redirect()->route('admin.user_type')->with([
        'message' => __('admin.common.success'),
        'alert-type' => 'success'
      ]);
    }
  }
