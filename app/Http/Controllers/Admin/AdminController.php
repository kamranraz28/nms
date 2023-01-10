<?php

namespace App\Http\Controllers\Admin;
use App\Models\Role;
use App\Models\Admin;
use App\Models\State;
use App\Models\Status;
use App\Models\Upazila;
use App\Models\District;
use App\Models\Division;


use App\Models\UserType;
use Barryvdh\DomPDF\PDF;
use App\Models\ForestBeat;
use App\Models\ForestRange;
use App\Models\ForestState;
use Illuminate\Support\Str;
use App\Exports\UsersExport;

use Illuminate\Http\Request;
use App\Exports\AdminsExport;
use App\Models\ForestDivision;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Helper\EnglishToBanglaDate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use Rakibhstu\Banglanumber\NumberToBangla;

class AdminController extends Controller
{
  
  const VIEW_PATH = 'admin.admin.';
  public $numto;
  public function __construct()
  {
    $this->numto = new NumberToBangla();
  }

  public function index(Request $request, NumberToBangla $numberToBangla)
  {
    $admins = Admin::with('role','userType','division','district','upazila')->where('user_type_id','<',Admin::RO)->lutwd()->get();
    return view(self::VIEW_PATH . 'index',compact('admins'));
  }

  public function datatable(Request $request)
  {
    if ($request->ajax()) {
      //$authAdmin = Auth::user();
      $authAdmin = Auth::guard('admin')->user();

      $admins = Admin::with('role','userType')->latest()->take(100)->get();

      //dd($admins);

      return DataTables::of($admins)
      ->addIndexColumn()
      ->editColumn('created_at', function (Admin $admin) use ($authAdmin) {
        //return date('d-m-Y', strtotime($admin->created_at));
        return $retVal = (app()->getLocale() == 'en') ? date('d-m-Y', strtotime($admin->created_at)) : EnglishToBanglaDate::dateFormatEnglishToBangla(date('d-m-Y', strtotime($admin->created_at))) ;
      })
      ->editColumn('status', function (Admin $admin) use ($authAdmin) {
        return __('admin.status.'.Status::LIST[$admin->status]);
      })
      ->addColumn('thumb', function (Admin $admin) use ($authAdmin) {
          if (!empty($admin->thumb)) {
              return "<a style=\"margin-left: 30%;\" target=\"_blank\" href='".asset('storage/'.$admin->thumb)."'><i class=\"fa fa-eye fa-2x\" aria-hidden=\"true\"></i></a>";
          }else{
              return "<a style=\"margin-left: 30%;\"><i class=\"fa fa-eye-slash fa-2x\" aria-hidden=\"true\"></i> </a>";
          }
      })
      ->addColumn('action', function (Admin $admin) use ($authAdmin) {
        $str = '<div>';
        $str .= '<a href="'.route('admin.admin.edit', $admin->id) .'" class="btn btn-xs btn-primary"><i class="fas fa-edit"></i></a>';
        $str .= '<a href="'.route('admin.admin.delete', $admin->id) .'" class="delete btn btn-xs btn-danger"><i class="fas fa-trash-alt"></i></a>';
        $str .= '</div>';
        return $str;
      })
      ->rawColumns(['thumb','action'])
      ->make(true);
    }
    
  }

  public function create()
  {
    //$this->authorize('create',Admin::class);
    $states = State::get();
    $divisions = Division::get();
    $districts = District::get();
    $upazilas = Upazila::get();
    $user_types = UserType::utwd()->get();
    $roles = Role::utwd()->get();

    $forest_states = ForestState::get();
    $forest_divisions = ForestDivision::get();
    $forest_ranges = ForestRange::get();
    $forest_beats = ForestBeat::get();


    return view(self::VIEW_PATH . 'add_edit', compact('user_types','roles','divisions','districts','upazilas',
    'states','forest_states','forest_divisions','forest_ranges','forest_beats'));
  }

  public function store(Request $request)
  {
    
    //$this->authorize('create',Admin::class);
    $this->validate($request, [
      'thumb' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:20000',
      //'thumb' => 'required',
      'status' => 'required',
      //'name' => 'required|min:1|max:128',
      'title_en' => 'required|min:1|max:128',
      'title_bn' => 'required|min:1|max:128',
      //'username' => 'required|unique:admins|min:1|max:128',
      'email' => 'required|email|unique:admins|min:1|max:128',
      'password' => 'required|confirmed|min:6',
      //'forest_division_id' => 'required',
    ]);

    //User type wise validation
    $validator = Admin::Utwv($request);
    if ($validator->fails()) {
      return back()->withErrors($validator)->withInput();
    }
    //User type wise validation

    $authUser = Auth::guard('admin')->user()->load(['userType']);
    if (Admin::DEFAULT_ROLE_LIST[1] != $authUser->userType->default_role) {
      if ($request->user_type == Admin::DEFAULT_ROLE_LIST[1]) {
        return back()->with([
          'error' => __('admin.common.error'),
          'alert-type' => 'error'
        ]);
      }
    }

    //dd($request->all());

    try {
      $data = $request->except('_token');
      $authAdmin = Auth::guard('admin')->user()->id;
      array_walk_recursive($data, function (&$val) {
          $val = trim($val);
          $val = is_string($val) && $val === '' ? null : $val;
      });

      $admin = DB::table('admins')->orderBy('id','DESC')->first();

      if ($admin) {
        $data['code'] = $admin->code + 1;
      } else {
        $data['code'] = 1;
      }


      $data['remember_token'] = Str::random(10);
      $data['email_verified_at'] = now();
      $data['created_by'] = $authAdmin;
      $data['password'] = Hash::make($request->password);
      if ($request->hasFile('thumb')) {
        $thumb = $request->file('thumb');
        $randNumber = rand(1, 999);
        $name = 'thumb-' . $authAdmin . '-' . time() . $randNumber . '.' . $thumb->getClientOriginalExtension();
        $year = date('Y/');
        $month = date('F/');
        $destinationPath = storage_path('app/public/admin/' . $year . $month);
        $thumb->move($destinationPath, $name);
        $filePath = 'admin/' . $year . $month;
        $data['thumb'] = $filePath . '' . $name;
      }

      $data['username'] = $data['email'];

      // //state
      // $state = Division::select('id','state_id')->where(['id'=>$request->division_id])->first();
      // $data['state_id'] = $state->state_id;
      // //state

      Admin::create($data);
      //Cache::forget('locWiseAuthUserInfo');
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }

    return redirect()->route('admin.admin')->with([
                    'message' => __('admin.common.success'),
                    'alert-type' => 'success'
                ]);
  }

  public function edit(Request $request, $id)
  {
    //$this->authorize('update',Admin::class);
    //validation
    $arr_id = [];
    $results = Admin::lutwd()->orderBy('id','desc')->get();
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
    
    
    $admin = DB::table('admins')->where(['id'=>$id])->first();
    $states = State::get();
    $divisions = Division::get();
    $districts = District::where('division_id',$admin->division_id)->get();
    $upazilas = Upazila::where('district_id',$admin->district_id)->get();

    $user_types = UserType::utwd()->get();
    $roles = Role::utwd()->get();
    //return $admin;
    
    $forest_states = ForestState::get();
    $forest_divisions = ForestDivision::get();
    $forest_ranges = ForestRange::where('forest_division_id',$admin->forest_division_id)->get();
    $forest_beats = ForestBeat::where('forest_range_id',$admin->forest_range_id)->get();

    return view(self::VIEW_PATH . 'add_edit', compact('admin','user_types','roles','divisions','districts',
    'upazilas','states','forest_states','forest_divisions','forest_beats','forest_ranges'));
  }

  public function update(Request $request, $id)
  {
    //$this->authorize('update',Admin::class);
    
    $this->validate($request, [
      'thumb' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:20000',
      //'thumb' => 'required',
      'status' => 'required',
      //'name' => 'required|min:1|max:128',
      'title_en' => 'required|min:1|max:128',
      'title_bn' => 'required|min:1|max:128',
      //'username' => 'required|unique:admins|min:1|max:128',
      'email' => ['required', Rule::unique('admins')->ignore($id)],
      //'password' => 'required|confirmed|min:6',
      //'forest_division_id' => 'required',
    ]);
    //dd($request->all());

    //User type wise validation
    $validator = Admin::Utwv($request);
    if ($validator->fails()) {
      return back()->withErrors($validator)->withInput();
    }
    //User type wise validation

    $authUser = Auth::guard('admin')->user()->load(['userType']);
    if (Admin::DEFAULT_ROLE_LIST[1] != $authUser->userType->default_role) {
      if ($request->user_type == Admin::DEFAULT_ROLE_LIST[1]) {
        return back()->with([
          'error' => __('admin.common.error'),
          'alert-type' => 'error'
        ]);
      }
    }


    try {

      $admin = Admin::where(['id'=>$id])->first();
      if ( is_null($admin) == true) {
        return back()->with([
          'error' => __('admin.common.error'),
          'alert-type' => 'error'
        ]);
      }
      $data = $request->except('_token');
      
      $authAdmin = Auth::guard('admin')->user()->id;
  
      array_walk_recursive($data, function (&$val) {
          $val = trim($val);
          $val = is_string($val) && $val === '' ? null : $val;
      });
  
      $data['updated_by'] = $authAdmin;
  
      if ($request->hasFile('thumb')) {
        @unlink(storage_path('/app/public/' . $admin->thumb));
        $thumb = $request->file('thumb');
        $randNumber = rand(1, 999);
        $name = 'thumb-' . $authAdmin . '-' . time() . $randNumber . '.' . $thumb->getClientOriginalExtension();
        $year = date('Y/');
        $month = date('F/');
        $destinationPath = storage_path('app/public/admin/' . $year . $month);
        $thumb->move($destinationPath, $name);
        $filePath = 'admin/' . $year . $month;
        $data['thumb'] = $filePath . '' . $name;
      }
  
      if ($authAdmin == $id) {
        $data['status'] = true;
      }

      // //state
      // $state = Division::select('id','state_id')->where(['id'=>$request->division_id])->first();
      // $data['state_id'] = $state->state_id;
      // //state
  
      Admin::find($id)->update($data);
      //Cache::forget('locWiseAuthUserInfo');
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }


    return redirect()->route('admin.admin')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);

  }

  public function delete(Request $request,$id,$sid=0)
  {
    //$this->authorize('delete',Admin::class);
    //validation
    $arr_id = [];
    $results = Admin::lutwd()->orderBy('id','desc')->get();
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
    $admin = Admin::find($id);
    if ( is_null($admin) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    $authAdmin = Auth::guard('admin')->user()->id;

    if ($id == $authAdmin) {
      return back()->with([
        'error' => __('admin.common.error'),
        //'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }

    try {
      if ($sid==false) {
        $admin->status = false;
        $admin->save();
      } else {
        @unlink(storage_path('/app/public/' . $admin->thumb));
        $admin->delete();
      }
      //Cache::forget('locWiseAuthUserInfo');
      
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    return redirect()->route('admin.admin')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

  
  public function export() 
  {
    return Excel::download(new AdminsExport, 'admins.xlsx');
  }

  public function pdf() {
    // retreive all records from db
    //$data = Employee::all();

    $admins = Admin::all();

    // share data to view
    //view()->share('employee',$data);
    $pdf = PDF::loadView(self::VIEW_PATH . 'pdf', compact('admins'))->setOptions(['defaultFont' => 'sans-serif']);

    // download PDF file with download method
    return $pdf->download('pdf_file.pdf');
  }


}
