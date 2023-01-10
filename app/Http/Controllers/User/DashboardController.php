<?php

namespace App\Http\Controllers\User;
use App\Models\Post;
use App\Models\Role;
use App\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Auth;

use App\Models\Content;
use App\Models\ContentCategory;
use App\Models\Version;
use DB;
class DashboardController extends Controller
{

    public function index()
    {

      //return "User Dashboard";
      
      /*if (Gate::allows('isSuperAdmin')) {
        dd('Super Admin allowed');
      }elseif (Gate::allows('isAdmin')) {
        dd('Admin allowed');
      }elseif (Gate::allows('isUser')) {
        dd('User allowed');
      }else {
        dd('You are not Admin');
      }*/


      // $this->authorize('update',App\Post::class);
      
      // $user = Auth::user();
      // $post = Post::first();
      // $role = Role::first();

      // if ($user->can('delete', app('App\Models\Post'))) {
      //   return "true";
      // }else{
      //   return "false";
      // }

      
      $content = Content::where(['status'=>1,'content_category_id'=> DB::raw('(select id from content_categories where position = "body" limit 1)')])->orderBy('sort','asc')->first();
        //dd($content);
      return view('app-home',compact('content'));
    }

}
