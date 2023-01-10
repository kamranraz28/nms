<?php

namespace App\Http\Controllers\Admin;
use App\Models\Role;
use App\Models\Admin;
use App\Models\Status;
use App\Models\Nursery;
use App\Models\Upazila;
use App\Models\District;
use App\Models\Division;

use Faker\Generator as Faker;

use App\Models\UserType;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Str;
use App\Exports\UsersExport;
//use App\Exports\NurserysExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helper\EnglishToBanglaDate;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class NurseryController extends Controller
{
  
  const VIEW_PATH = 'admin.nursery.';
  public function __construct()
  {
  }

  public function index(Request $request)
  {
    
    $nurseries = Nursery::lwd()->with('admin','division','district','upazila')->get();
    return view(self::VIEW_PATH . 'index',compact('nurseries'));
  }

  public function create()
  {
    //$this->authorize('create',Nursery::class);
    $admins = Admin::with('userType')->lutwbod()->get();
    return view(self::VIEW_PATH . 'add_edit', compact('admins'));
  }

  public function store(Request $request, Faker $faker)
  {
    
    //$this->authorize('create',Nursery::class);
    $this->validate($request, [
      'thumb' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:20000',
      //'thumb' => 'required',
      'status' => 'required',
      //'name' => 'required|min:1|max:128',
      'title_en' => 'required|min:1|max:128',
      'title_bn' => 'required|min:1|max:128',
      //'username' => 'required|unique:nurseries|min:1|max:128',
      //'email' => 'required|email|unique:nurseries|min:1|max:128',
      //'password' => 'required|confirmed|min:6',
    ]);



      



    try {
      $data = $request->except('_token');
      $authNursery = Auth::guard('admin')->user()->id;
      array_walk_recursive($data, function (&$val) {
          $val = trim($val);
          $val = is_string($val) && $val === '' ? null : $val;
      });

      $nursery = DB::table('nurseries')->orderBy('id','DESC')->first();
      if ($nursery) {
        $data['code'] = $nursery->code + 1;
      } else {
        $data['code'] = 1;
      }

      // locations 
      $admin = Admin::findOrFail($request->admin_id);
      $data['state_id'] = $admin->state_id;
      $data['division_id'] = $admin->division_id;
      $data['district_id'] = $admin->district_id;
      $data['upazila_id'] = $admin->upazila_id;
      // locations 



      $data['remember_token'] = Str::random(10);
      $data['email_verified_at'] = now();
      $data['created_by'] = $authNursery;
      $data['password'] = Hash::make($request->password);
      if ($request->hasFile('thumb')) {
        $thumb = $request->file('thumb');
        $randNumber = rand(1, 999);
        $name = 'thumb-' . $authNursery . '-' . time() . $randNumber . '.' . $thumb->getClientOriginalExtension();
        $year = date('Y/');
        $month = date('F/');
        $destinationPath = storage_path('app/public/nursery/' . $year . $month);
        $thumb->move($destinationPath, $name);
        $filePath = 'nursery/' . $year . $month;
        $data['thumb'] = $filePath . '' . $name;
      }

      $data['email'] = $faker->email;
      $data['password'] = Hash::make('password');
      Nursery::create($data);
      
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }

    return redirect()->route('admin.nursery')->with([
                    'message' => __('admin.common.success'),
                    'alert-type' => 'success'
                ]);
  }

  public function edit(Request $request, $id)
  {
    //$this->authorize('update',Nursery::class);
    $nursery = DB::table('nurseries')->where(['id'=>$id])->first();
    $admins = Admin::with('userType')->lutwd()->get();
    //return $nursery;
    return view(self::VIEW_PATH . 'add_edit', compact('nursery','admins'));
  }

  public function update(Request $request, $id)
  {
    //$this->authorize('update',Nursery::class);
    
    $this->validate($request, [
      'thumb' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:20000',
      //'thumb' => 'required',
      'status' => 'required',
      //'name' => 'required|min:1|max:128',
      'title_en' => 'required|min:1|max:128',
      'title_bn' => 'required|min:1|max:128',
      //'username' => 'required|unique:nurseries|min:1|max:128',
      //'email' => 'required|email|unique:nurseries|min:1|max:128',
      //'password' => 'required|confirmed|min:6',
    ]);
    //dd($request->all());

    try {

      $nursery = Nursery::where(['id'=>$id])->first();
      if ( is_null($nursery) == true) {
        return back()->with([
          'error' => __('admin.common.error'),
          'alert-type' => 'error'
        ]);
      }
      $data = $request->except('_token');
      
      $authNursery = Auth::guard('admin')->user()->id;
  
      array_walk_recursive($data, function (&$val) {
          $val = trim($val);
          $val = is_string($val) && $val === '' ? null : $val;
      });
  
      $data['updated_by'] = $authNursery;
  
      if ($request->hasFile('thumb')) {
        @unlink(storage_path('/app/public/' . $nursery->thumb));
        $thumb = $request->file('thumb');
        $randNumber = rand(1, 999);
        $name = 'thumb-' . $authNursery . '-' . time() . $randNumber . '.' . $thumb->getClientOriginalExtension();
        $year = date('Y/');
        $month = date('F/');
        $destinationPath = storage_path('app/public/nursery/' . $year . $month);
        $thumb->move($destinationPath, $name);
        $filePath = 'nursery/' . $year . $month;
        $data['thumb'] = $filePath . '' . $name;
      }
  
      if ($authNursery == $id) {
        $data['status'] = true;
      }

      // locations 
      $admin = Admin::findOrFail($request->admin_id);
      $data['state_id'] = $admin->state_id;
      $data['division_id'] = $admin->division_id;
      $data['district_id'] = $admin->district_id;
      $data['upazila_id'] = $admin->upazila_id;
      // locations 
  
      Nursery::find($id)->update($data);
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }


    return redirect()->route('admin.nursery')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);

  }

  public function delete(Request $request,$id,$sid=0)
  {
    //$this->authorize('delete',Nursery::class);

    $nursery = Nursery::find($id);
    if ( is_null($nursery) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    // $authNursery = Auth::guard('admin')->user()->id;

    // if ($id == $authNursery) {
    //   return back()->with([
    //     'error' => __('admin.common.error'),
    //     //'error' => $exception->getMessage(),
    //     'alert-type' => 'error'
    //   ]);
    // }

    try {
      if ($sid==false) {
        $nursery->status = false;
        $nursery->save();
      } else {
        @unlink(storage_path('/app/public/' . $nursery->thumb));
        $nursery->delete();
      }
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    return redirect()->route('admin.nursery')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

  
  public function export() 
  {
    //return Excel::download(new NurserysExport, 'nurseries.xlsx');
  }

  public function pdf() {
    // retreive all records from db
    //$data = Employee::all();

    $nurseries = Nursery::all();

    // share data to view
    //view()->share('employee',$data);
    //$pdf = PDF::loadView(self::VIEW_PATH . 'pdf', compact('nurseries'))->setOptions(['defaultFont' => 'sans-serif']);

    // download PDF file with download method
    //return $pdf->download('pdf_file.pdf');
  }


}
