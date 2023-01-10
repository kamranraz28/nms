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
use App\Models\BeatOffice;
use App\Models\ForestBeat;
use App\Models\ForestRange;
use App\Models\ForestState;

use App\Models\RangeOffice;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ForestDivision;
use Illuminate\Validation\Rule;
use App\Exports\BeatOfficesExport;
use Illuminate\Support\Facades\DB;
use App\Exports\RangeOfficesExport;
use App\Helper\EnglishToBanglaDate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use Rakibhstu\Banglanumber\NumberToBangla;

class BeatOfficeController extends Controller
{
  
  const VIEW_PATH = 'admin.beat_office.';
  public function __construct()
  {
  }

  public function index(Request $request, NumberToBangla $numberToBangla)
  {
    $beat_offices = BeatOffice::lutwbod()->with('role','userType','division','district')->get();
    return view(self::VIEW_PATH . 'index',compact('beat_offices'));
  }


  public function create()
  {
    //$this->authorize('create',BeatOffice::class);
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


    return view(self::VIEW_PATH . 'add_edit', compact('user_types','roles','divisions','districts','upazilas','states',
    'forest_states','forest_divisions','forest_ranges','forest_beats'));
  }

  public function store(Request $request)
  {
    
    //$this->authorize('create',BeatOffice::class);
    $this->validate($request, [
      'thumb' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:20000',
      //'thumb' => 'required',
      'status' => 'required',
      //'name' => 'required|min:1|max:128',
      'title_en' => 'required|min:1|max:128',
      'title_bn' => 'required|min:1|max:128',
      //'username' => 'required|unique:admin|min:1|max:128',
      'email' => 'required|email|unique:admins|min:1|max:128',
      'password' => 'required|confirmed|min:6',
      'forest_state_id' => 'required',
      'forest_division_id' => 'required',
      'forest_range_id' => 'required',
      'forest_beat_id' => 'required',
    ]);

    $authUser = Auth::guard('admin')->user()->load(['userType']);

    //dd($request->all());

    try {
      $data = $request->except('_token');
      $authBeatOffice = Auth::guard('admin')->user()->id;
      array_walk_recursive($data, function (&$val) {
          $val = trim($val);
          $val = is_string($val) && $val === '' ? null : $val;
      });

      $beat_office = DB::table('admins')->orderBy('id','DESC')->first();

      if ($beat_office) {
        $data['code'] = $beat_office->code + 1;
      } else {
        $data['code'] = 1;
      }


      $data['remember_token'] = Str::random(10);
      $data['email_verified_at'] = now();
      $data['created_by'] = $authBeatOffice;
      $data['password'] = Hash::make($request->password);
      if ($request->hasFile('thumb')) {
        $thumb = $request->file('thumb');
        $randNumber = rand(1, 999);
        $name = 'thumb-' . $authBeatOffice . '-' . time() . $randNumber . '.' . $thumb->getClientOriginalExtension();
        $year = date('Y/');
        $month = date('F/');
        $destinationPath = storage_path('app/public/beat_office/' . $year . $month);
        $thumb->move($destinationPath, $name);
        $filePath = 'beat_office/' . $year . $month;
        $data['thumb'] = $filePath . '' . $name;
      }

      $data['username'] = $data['email'];
      $data['user_type_id'] = Admin::BO;
      $data['role_id'] = Admin::BO;


      Admin::create($data);
      //Cache::forget('locWiseAuthUserInfo');
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }

    return redirect()->route('admin.beat_office')->with([
                    'message' => __('admin.common.success'),
                    'alert-type' => 'success'
                ]);
  }

  public function edit(Request $request, $id)
  {
    //$this->authorize('update',BeatOffice::class);
    
    //validation
    $arr_id = [];
    $results = BeatOffice::lutwbod()->get();
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
    
    
    $beat_office = DB::table('admins')->where(['id'=>$id])->first();
    $states = State::get();
    $divisions = Division::get();
    $districts = District::where('division_id',$beat_office->division_id)->get();
    $upazilas = Upazila::where('district_id',$beat_office->district_id)->get();

    $user_types = UserType::utwd()->get();
    $roles = Role::utwd()->get();
    //return $beat_office;

    $forest_states = ForestState::get();
    $forest_divisions = ForestDivision::get();
    $forest_ranges = ForestRange::where('forest_division_id',$beat_office->forest_division_id)->get();
    $forest_beats = ForestBeat::where('forest_range_id',$beat_office->forest_range_id)->get();
    

    return view(self::VIEW_PATH . 'add_edit', compact('beat_office','user_types','roles','divisions','districts',
    'upazilas','states','forest_states','forest_divisions','forest_ranges','forest_beats'));
  }

  public function update(Request $request, $id)
  {
    //$this->authorize('update',BeatOffice::class);
    
    $this->validate($request, [
      'thumb' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:20000',
      //'thumb' => 'required',
      'status' => 'required',
      //'name' => 'required|min:1|max:128',
      'title_en' => 'required|min:1|max:128',
      'title_bn' => 'required|min:1|max:128',
      //'username' => 'required|unique:admin|min:1|max:128',
      'email' => ['required', Rule::unique('admins')->ignore($id)],
      //'password' => 'required|confirmed|min:6',
      'forest_state_id' => 'required',
      'forest_division_id' => 'required',
      'forest_range_id' => 'required',
      'forest_beat_id' => 'required',
    ]);

    $authUser = Auth::guard('admin')->user()->load(['userType']);

    

    try {

      $beat_office = BeatOffice::where(['id'=>$id])->first();
      if ( is_null($beat_office) == true) {
        return back()->with([
          'error' => __('admin.common.error'),
          'alert-type' => 'error'
        ]);
      }
      $data = $request->except('_token');
      
      $authBeatOffice = Auth::guard('admin')->user()->id;
  
      array_walk_recursive($data, function (&$val) {
          $val = trim($val);
          $val = is_string($val) && $val === '' ? null : $val;
      });
  
      $data['updated_by'] = $authBeatOffice;
  
      if ($request->hasFile('thumb')) {
        @unlink(storage_path('/app/public/' . $beat_office->thumb));
        $thumb = $request->file('thumb');
        $randNumber = rand(1, 999);
        $name = 'thumb-' . $authBeatOffice . '-' . time() . $randNumber . '.' . $thumb->getClientOriginalExtension();
        $year = date('Y/');
        $month = date('F/');
        $destinationPath = storage_path('app/public/beat_office/' . $year . $month);
        $thumb->move($destinationPath, $name);
        $filePath = 'beat_office/' . $year . $month;
        $data['thumb'] = $filePath . '' . $name;
      }
  
      if ($authBeatOffice == $id) {
        $data['status'] = true;
      }


      $data['user_type_id'] = Admin::BO;
      $data['role_id'] = Admin::BO;
  
      Admin::find($id)->update($data);
      //Cache::forget('locWiseAuthUserInfo');
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }


    return redirect()->route('admin.beat_office')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);

  }

  public function delete(Request $request,$id,$sid=0)
  {
    //$this->authorize('delete',BeatOffice::class);
    //validation
    $arr_id = [];
    $results = BeatOffice::lutwbod()->get();
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
    $beat_office = BeatOffice::find($id);
    if ( is_null($beat_office) == true) {
      return back()->with([
        'error' => __('admin.common.error'),
        'alert-type' => 'error'
      ]);
    }

    $authBeatOffice = Auth::guard('admin')->user()->id;

    if ($id == $authBeatOffice) {
      return back()->with([
        'error' => __('admin.common.error'),
        //'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }

    try {
      if ($sid==false) {
        $beat_office->status = false;
        $beat_office->save();
      } else {
        @unlink(storage_path('/app/public/' . $beat_office->thumb));
        $beat_office->delete();
      }
      //Cache::forget('locWiseAuthUserInfo');
      
    } catch (\Throwable $exception) {
      return back()->with([
        //'error' => __('admin.common.error'),
        'error' => $exception->getMessage(),
        'alert-type' => 'error'
      ]);
    }
    return redirect()->route('admin.beat_office')->with([
      'message' => __('admin.common.success'),
      'alert-type' => 'success'
    ]);
  }

  
  public function export() 
  {
    return Excel::download(new BeatOfficesExport, 'beat_offices.xlsx');
  }

  public function pdf() {
    // retreive all records from db
    //$data = Employee::all();

    $beat_offices = BeatOffice::all();

    // share data to view
    //view()->share('employee',$data);
    $pdf = PDF::loadView(self::VIEW_PATH . 'pdf', compact('beat_offices'))->setOptions(['defaultFont' => 'sans-serif']);

    // download PDF file with download method
    return $pdf->download('pdf_file.pdf');
  }


}
