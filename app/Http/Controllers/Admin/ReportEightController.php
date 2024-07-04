<?php

namespace App\Http\Controllers\Admin;
use App\Models\Sale;
use App\Models\Admin;
use App\Models\State;
use App\Models\Budget;

use App\Models\Nursery;
use App\Models\Product;
use App\Models\Upazila;
use App\Models\Category;
use App\Models\District;
use App\Models\Division;
use App\Models\StockType;
use App\Models\ForestBeat;
use App\Models\ReportEight;
use App\Models\SaleDetail;
use App\Models\ForestRange;
use App\Models\ForestState;
use Illuminate\Http\Request;
use App\Models\FinancialYear;
use App\Models\ForestDivision;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use DOMPDF;
use MPDF;

class ReportEightController extends Controller
{
  const VIEW_PATH = 'admin.report_eight.';
  public function __construct()
  {
    date_default_timezone_set('Asia/Dhaka');
  }

  public function index(Request $request)
  {
    $previousDistrict = null;
    $previousUpazila = null;
    $previousBeat = null;
    $this->authorize('read',ReportEight::class);
    
    //Session::forget(['from_date','to_date','from_date_pre','to_date_pre','forest_division_id','budget_id','stock_type_id','forest_range_id','financial_year']);
    
    $report_eights = [];
    $forest_district_data = [];
    $footer_report_eights = [];
    $parameters = [];
    @$budget_id = Session::get('budget_id');
    @$from_date = Session::get('from_date');
    @$to_date = Session::get('to_date');
   @$from_date_pre = Session::get('from_date_pre');
 @$to_date_pre = Session::get('to_date_pre');
 
    @$forest_division_id = Session::get('forest_division_id');
    @$stock_type_id = Session::get('stock_type_id');
    @$financial_year_id = Session::get('financial_year_id');
    @$forest_beat_id = Session::get('forest_beat_id');
    if ($from_date && $to_date) {
      $parameters['budget_id'] = $budget_id;
      $parameters['f_date'] = $from_date;
      $parameters['from_date'] = $from_date;
      $parameters['to_date'] = $to_date;
      $parameters['from_date_pre'] = $from_date_pre;
      $parameters['to_date_pre'] = $to_date_pre;
      $parameters['forest_division_id'] = $forest_division_id;
      $parameters['stock_type_id'] = $stock_type_id;
      $parameters['forest_beat_id'] = $forest_beat_id;

      
      $forest_division = ForestDivision::findOrFail($forest_division_id);
      // For display
      
      $parameters['budget_id'] = $budget_id;
      $parameters['stock_type_id'] = $stock_type_id;

      $parameters['forest_division_en'] = $forest_division->title_en;
      $parameters['forest_division_bn'] = $forest_division->title_bn;

    $parameters['from_date_pre_view'] = date("M/Y",strtotime($to_date_pre . "-1 days"));
      

      $parameters['budget_year_view'] = date("Y",strtotime($from_date)) . '-' . date("Y",strtotime($from_date . "1 years"));
      $parameters['till_date_view'] = date("d M/Y",strtotime($to_date));
      $parameters['to_date_view'] = date("d M/Y",strtotime($to_date));
      $parameters['title_date_view'] = date("M,Y",strtotime($from_date));
      // For display
      //dd($parameters);
      
      // Nedd to dynamic
      $from_date_pre = date("Y-m-d",strtotime("2015-01-01"));
      //dd($parameters);



      // for forest district
      
      $pre_stock_arr = [];
      $current_total_stock_in_arr = [];
      $current_total_stock_out_arr = [];
      $current_total_stock_arr = [];
      // for forest district

      
      $forest_division_id = $forest_division->id;
      
      if ($forest_beat_id == 'all') {
        $forest_beats = ForestBeat::lwd()->with('district','upazila')->where(['forest_division_id'=>$forest_division_id])->get();
      } else {
        $forest_beats = ForestBeat::lwd()->with('district','upazila')->where(['id'=>$forest_beat_id])->get();
      }

      //dd($forest_beats);
      // for forest district
        $pre_stock_arr = [];
        $current_total_stock_in_arr = [];
        $current_total_stock_out_arr = [];
        $current_total_stock_arr = [];
      // for forest district
      

      foreach ($forest_beats as $key => $forest_beat) {
        $forest_beat_id = $forest_beat->id;
        //$forest_beats = ForestBeat::lwd()->where(['forest_beat_id'=>$forest_beat_id])->get();
        
        //range code
        if ($budget_id == 'all' &&  $stock_type_id == 'all') {
          #code
          //purchase_pre 
          $query = DB::table('purchase_details as t1')
            ->select(
              DB::raw('(CASE WHEN SUM(t1.quantity) IS NULL THEN 0 ELSE SUM(t1.quantity) END) as stock_in_pre')
            )
            ->where(['t1.forest_beat_id'=>$forest_beat_id,'t1.approved'=>true])
            ->whereBetween(DB::raw("DATE_FORMAT(t1.vch_date,'%Y-%m-%d')"), [$from_date_pre, $to_date_pre])
            ->first();
          $purchaseQueryPre = json_decode(json_encode($query), True);
          //exit();
          //purchase_pre 
          
          //sale_pre 
          $query = DB::table('sale_details as t1')
            ->select(
              DB::raw('(CASE WHEN SUM(t1.quantity) IS NULL THEN 0 ELSE SUM(t1.quantity) END) as stock_out_pre')
            )
            ->where(['t1.forest_beat_id'=>$forest_beat_id,'t1.approved'=>true])
            ->whereBetween(DB::raw("DATE_FORMAT(t1.vch_date,'%Y-%m-%d')"), [$from_date_pre, $to_date_pre])
            ->first();
          $saleQueryPre = json_decode(json_encode($query), True);
          //sale_pre 
          
          //purchase 
          $query = DB::table('purchase_details as t1')
            ->select(
              DB::raw('(CASE WHEN SUM(t1.quantity) IS NULL THEN 0 ELSE SUM(t1.quantity) END) as stock_in')
            )
            ->where(['t1.forest_beat_id'=>$forest_beat_id,'t1.approved'=>true])
            ->whereBetween(DB::raw("DATE_FORMAT(t1.vch_date,'%Y-%m-%d')"), [$from_date, $to_date])
            ->first();
          $purchaseQuery = json_decode(json_encode($query), True);
          //purchase 
          
          //sale 
          $query = DB::table('sale_details as t1')
            ->select(
              DB::raw('(CASE WHEN SUM(t1.quantity) IS NULL THEN 0 ELSE SUM(t1.quantity) END) as stock_out')
            )
            ->where(['t1.forest_beat_id'=>$forest_beat_id,'t1.approved'=>true])
            ->whereBetween(DB::raw("DATE_FORMAT(t1.vch_date,'%Y-%m-%d')"), [$from_date, $to_date])
            ->first();
          $saleQuery = json_decode(json_encode($query), True);

          
        //sale 
        } else if($budget_id != 'all' &&  $stock_type_id != 'all') {
          # code...
          //purchase_pre 
          $query = DB::table('purchase_details as t1')
            ->select(
              DB::raw('(CASE WHEN SUM(t1.quantity) IS NULL THEN 0 ELSE SUM(t1.quantity) END) as stock_in_pre')
            )
            ->where(['t1.budget_id'=>$budget_id, 't1.forest_beat_id'=>$forest_beat_id,'t1.stock_type_id'=>$stock_type_id,'t1.approved'=>true])
            ->whereBetween(DB::raw("DATE_FORMAT(t1.vch_date,'%Y-%m-%d')"), [$from_date_pre, $to_date_pre])
            ->first();
          $purchaseQueryPre = json_decode(json_encode($query), True);
          //purchase_pre 
          
          //sale_pre 
          $query = DB::table('sale_details as t1')
            ->select(
              DB::raw('(CASE WHEN SUM(t1.quantity) IS NULL THEN 0 ELSE SUM(t1.quantity) END) as stock_out_pre')
            )
            ->where(['t1.budget_id'=>$budget_id, 't1.forest_beat_id'=>$forest_beat_id,'t1.stock_type_id'=>$stock_type_id,'t1.approved'=>true])
            ->whereBetween(DB::raw("DATE_FORMAT(t1.vch_date,'%Y-%m-%d')"), [$from_date_pre, $to_date_pre])
            ->first();
          $saleQueryPre = json_decode(json_encode($query), True);
          //sale_pre 
          
          //purchase 
          $query = DB::table('purchase_details as t1')
            ->select(
              DB::raw('(CASE WHEN SUM(t1.quantity) IS NULL THEN 0 ELSE SUM(t1.quantity) END) as stock_in')
            )
            ->where(['t1.budget_id'=>$budget_id, 't1.forest_beat_id'=>$forest_beat_id,'t1.stock_type_id'=>$stock_type_id,'t1.approved'=>true])
            ->whereBetween(DB::raw("DATE_FORMAT(t1.vch_date,'%Y-%m-%d')"), [$from_date, $to_date])
            ->first();
          $purchaseQuery = json_decode(json_encode($query), True);
          //purchase 
          
          //sale 
          $query = DB::table('sale_details as t1')
            ->select(
              DB::raw('(CASE WHEN SUM(t1.quantity) IS NULL THEN 0 ELSE SUM(t1.quantity) END) as stock_out')
            )
            ->where(['t1.budget_id'=>$budget_id, 't1.forest_beat_id'=>$forest_beat_id,'t1.stock_type_id'=>$stock_type_id,'t1.approved'=>true])
            ->whereBetween(DB::raw("DATE_FORMAT(t1.vch_date,'%Y-%m-%d')"), [$from_date, $to_date])
            ->first();
          $saleQuery = json_decode(json_encode($query), True);
          //sale 
        } else if($budget_id = 'all' &&  $stock_type_id != 'all') {
          # code...
          //purchase_pre 
          $query = DB::table('purchase_details as t1')
            ->select(
              DB::raw('(CASE WHEN SUM(t1.quantity) IS NULL THEN 0 ELSE SUM(t1.quantity) END) as stock_in_pre')
            )
            ->where(['t1.forest_beat_id'=>$forest_beat_id,'t1.stock_type_id'=>$stock_type_id,'t1.approved'=>true])
            ->whereBetween(DB::raw("DATE_FORMAT(t1.vch_date,'%Y-%m-%d')"), [$from_date_pre, $to_date_pre])
            ->first();
          $purchaseQueryPre = json_decode(json_encode($query), True);
          //purchase_pre 
          
          //sale_pre 
          $query = DB::table('sale_details as t1')
            ->select(
              DB::raw('(CASE WHEN SUM(t1.quantity) IS NULL THEN 0 ELSE SUM(t1.quantity) END) as stock_out_pre')
            )
            ->where(['t1.forest_beat_id'=>$forest_beat_id,'t1.stock_type_id'=>$stock_type_id,'t1.approved'=>true])
            ->whereBetween(DB::raw("DATE_FORMAT(t1.vch_date,'%Y-%m-%d')"), [$from_date_pre, $to_date_pre])
            ->first();
          $saleQueryPre = json_decode(json_encode($query), True);
          //sale_pre 
          
          //purchase 
          $query = DB::table('purchase_details as t1')
            ->select(
              DB::raw('(CASE WHEN SUM(t1.quantity) IS NULL THEN 0 ELSE SUM(t1.quantity) END) as stock_in')
            )
            ->where(['t1.forest_beat_id'=>$forest_beat_id,'t1.stock_type_id'=>$stock_type_id,'t1.approved'=>true])
            ->whereBetween(DB::raw("DATE_FORMAT(t1.vch_date,'%Y-%m-%d')"), [$from_date, $to_date])
            ->first();
          $purchaseQuery = json_decode(json_encode($query), True);
          //purchase 
          
          //sale 
          $query = DB::table('sale_details as t1')
            ->select(
              DB::raw('(CASE WHEN SUM(t1.quantity) IS NULL THEN 0 ELSE SUM(t1.quantity) END) as stock_out')
            )
            ->where(['t1.forest_beat_id'=>$forest_beat_id,'t1.stock_type_id'=>$stock_type_id,'t1.approved'=>true])
            ->whereBetween(DB::raw("DATE_FORMAT(t1.vch_date,'%Y-%m-%d')"), [$from_date, $to_date])
            ->first();
          $saleQuery = json_decode(json_encode($query), True);
          //sale 
        }  else if($budget_id != 'all' &&  $stock_type_id = 'all') {
          # code...
          //purchase_pre 
          $query = DB::table('purchase_details as t1')
            ->select(
              DB::raw('(CASE WHEN SUM(t1.quantity) IS NULL THEN 0 ELSE SUM(t1.quantity) END) as stock_in_pre')
            )
            ->where(['t1.budget_id'=>$budget_id, 't1.forest_beat_id'=>$forest_beat_id,'t1.approved'=>true])
            ->whereBetween(DB::raw("DATE_FORMAT(t1.vch_date,'%Y-%m-%d')"), [$from_date_pre, $to_date_pre])
            ->first();
          $purchaseQueryPre = json_decode(json_encode($query), True);
          //purchase_pre 
          
          //sale_pre 
          $query = DB::table('sale_details as t1')
            ->select(
              DB::raw('(CASE WHEN SUM(t1.quantity) IS NULL THEN 0 ELSE SUM(t1.quantity) END) as stock_out_pre')
            )
            ->where(['t1.budget_id'=>$budget_id, 't1.forest_beat_id'=>$forest_beat_id,'t1.approved'=>true])
            ->whereBetween(DB::raw("DATE_FORMAT(t1.vch_date,'%Y-%m-%d')"), [$from_date_pre, $to_date_pre])
            ->first();
          $saleQueryPre = json_decode(json_encode($query), True);
          //sale_pre 
          
          //purchase 
          $query = DB::table('purchase_details as t1')
            ->select(
              DB::raw('(CASE WHEN SUM(t1.quantity) IS NULL THEN 0 ELSE SUM(t1.quantity) END) as stock_in')
            )
            ->where(['t1.budget_id'=>$budget_id, 't1.forest_beat_id'=>$forest_beat_id,'t1.approved'=>true])
            ->whereBetween(DB::raw("DATE_FORMAT(t1.vch_date,'%Y-%m-%d')"), [$from_date, $to_date])
            ->first();
          $purchaseQuery = json_decode(json_encode($query), True);
          //purchase 
          
          //sale 
          $query = DB::table('sale_details as t1')
            ->select(
              DB::raw('(CASE WHEN SUM(t1.quantity) IS NULL THEN 0 ELSE SUM(t1.quantity) END) as stock_out')
            )
            ->where(['t1.budget_id'=>$budget_id, 't1.forest_beat_id'=>$forest_beat_id,'t1.approved'=>true])
            ->whereBetween(DB::raw("DATE_FORMAT(t1.vch_date,'%Y-%m-%d')"), [$from_date, $to_date])
            ->first();
          $saleQuery = json_decode(json_encode($query), True);
          //sale 
        }
        //dd($queryresults);
        $pre_stock = (int)$purchaseQueryPre['stock_in_pre'] - (int)$saleQueryPre['stock_out_pre'];
        $current_total_stock_in = (int)$purchaseQuery['stock_in'];
        $current_total_stock_out = (int)$saleQuery['stock_out'];
        $current_total_stock = ($pre_stock + $current_total_stock_in) - $current_total_stock_out;

        // for forest district
          $pre_stock_arr[] = $pre_stock;
          $current_total_stock_in_arr[] = $current_total_stock_in;
          $current_total_stock_out_arr[] = $current_total_stock_out;
          $current_total_stock_arr[] = $current_total_stock;
        // for forest district
        
        $report_eights[] = [
          'forest_division_id' => $forest_division_id,
          'forest_division_en' => $forest_division->title_en,
          'forest_division_bn' => $forest_division->title_bn,
          'district_en' => @$forest_beat->district->title_en,
          'district_bn' => @$forest_beat->district->title_bn,
          'upazila_en' => @$forest_beat->upazila->title_en,
          'upazila_bn' => @$forest_beat->upazila->title_bn,
          'forest_beat_id' => $forest_beat_id,
          'forest_beat_en' => $forest_beat->title_en,
          'forest_beat_bn' => $forest_beat->title_bn,
          'pre_stock' => $pre_stock,
          'current_total_stock_in' => $current_total_stock_in,
          'current_total_stock_out' => $current_total_stock_out,
          'current_total_stock' => $current_total_stock,
        ];
        //range code

        
      }

      $forest_district_data[] = [
        'forest_division_id' => $forest_division_id,
        'forest_division_en' => $forest_division->title_en,
        'forest_division_bn' => $forest_division->title_bn,
        'pre_stock_arr_total' => array_sum($pre_stock_arr),
        'current_total_stock_in_arr' => array_sum($current_total_stock_in_arr),
        'current_total_stock_out_arr' => array_sum($current_total_stock_out_arr),
        'current_total_stock_arr' => array_sum($current_total_stock_arr),
      ];
      //dd($report_eights);
    }

    Session::put(['report_eights'=>$report_eights, 'footer_report_eights'=>$footer_report_eights, 'parameters'=>$parameters]);
    Session::put(['dreport_eights'=>$report_eights, 'dfooter_report_eights'=>$footer_report_eights, 'dparameters'=>$parameters]);

    $authUser = Auth::guard('admin')->user()->load(['userType']);
    if ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
      $forest_division_id = $authUser->forest_division_id;
      $forest_range_id = $authUser->forest_range_id;
      $forest_beat_id = $authUser->forest_beat_id;

      $forest_divisions = ForestDivision::where(['id'=>$forest_division_id])->get();
      $forest_ranges = ForestRange::where(['id'=>$forest_range_id])->get();
      $forest_beats = ForestBeat::where(['id'=>$forest_beat_id])->get();
      
    }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
      $forest_division_id = $authUser->forest_division_id;
      $forest_range_id = $authUser->forest_range_id;

      $forest_divisions = ForestDivision::where(['id'=>$forest_division_id])->get();
      $forest_ranges = ForestRange::where(['id'=>$forest_range_id])->get();
      $forest_beats = ForestBeat::lwd()->get();

    }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
      $forest_division_id = $authUser->forest_division_id;
      $forest_divisions = ForestDivision::where(['id'=>$forest_division_id])->get();
      $forest_ranges = ForestRange::lwd()->get();
      $forest_beats = ForestBeat::lwd()->get();
    }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
      $forest_state_id = $authUser->forest_state_id;
      $forest_state = ForestState::where(['id'=>$forest_state_id])->first();
      $forest_divisions = ForestDivision::where(['forest_state_id'=>$forest_state->id])->get();
      $forest_ranges = ForestRange::lwd()->get();
      $forest_beats = ForestBeat::lwd()->get();
    }else{
      $forest_divisions = ForestDivision::lwd()->get();
      $forest_ranges = ForestRange::lwd()->get();
      $forest_beats = ForestBeat::lwd()->get();
      //dd($forest_divisions);
    }
    
    $budgets = Budget::get();
    $financial_years = FinancialYear::get();
    $stock_types = StockType::get();

    return view(self::VIEW_PATH . 'index',compact('report_eights','previousDistrict','previousBeat','previousUpazila','parameters','forest_divisions','forest_ranges','forest_beats','budgets','forest_district_data','financial_years','stock_types'));
  }

  public function store(Request $request)
  {
    $this->authorize('create',App\ReportEight::class);
    
    //dd($request->all());
    $this->validate($request, [
      'stock_type_id' => 'required',
      'budget_id' => 'required',
      'forest_division_id' => 'required',
      'forest_beat_id' => 'required',
      'f_date' => 'required',
    ]);
    
    $month = date("Y-m",strtotime($request->f_date));
   $from = $month.'-'.'01';
   
 
    $to_date = date("Y-m-t",strtotime($from));
  
   // $to = date("Y-m-d",strtotime($to_date . "-1 days"));
    

    $from_date = date("Y-m-d",strtotime($from));
   // $to_date = date("Y-m-d",strtotime($to));

    $from_date_pre = date("Y-m-d",strtotime($from . "-1 months"));
    $to_date_pre = date("Y-m-t",strtotime($from. "-1 months"));
    //exit();

    Session::forget(['from_date','to_date','from_date_pre','to_date_pre','forest_division_id','budget_id','stock_type_id','forest_beat_id','financial_year']);
    Session::put(['from_date'=>$from_date,'to_date'=>$to_date, 'from_date_pre'=>$from_date_pre,'to_date_pre'=>$to_date_pre, 
    'forest_division_id'=>$request->forest_division_id,'budget_id'=>$request->budget_id,'stock_type_id'=>$request->stock_type_id,
    'forest_beat_id'=>$request->forest_beat_id,'financial_year_id'=>$request->financial_year_id]);
    //dd(Session::all());

    return redirect()->route('admin.report_eight');

  }

  public function print(Request $request, $id)
  {
    $this->authorize('print',App\ReportEight::class);
    
    return view(self::VIEW_PATH . 'print', compact('report_eight'));
  }

  public function download()
  {
    $previousDistrict= null;
    $previousUpazila= null;
    $previousBeat= null;
    $previousState= null;
    $previousDivision= null;
    $previousRange = null;
    $previousBeat = null;
    $previousCategory = null;
    $previousProduct = null;
  
    $this->authorize('print',App\ReportEight::class);

    $report_eights = [];
    $footer_report_eights = [];
    $forest_district_data = [];
    @$report_eights = Session::get('dreport_eights');
    @$footer_report_eights = Session::get('dfooter_report_eights');
    @$parameters = Session::get('dparameters');
    $categories = Category::where('last',1)->get();
    //return $report_eights;

    // dd(Session::get('dreport_eights'));


    if (Session::get('dreport_eights')) {

      $pdf = MPDF::loadView(self::VIEW_PATH . 'download', compact('previousProduct','previousCategory','previousBeat','previousRange','previousDivision','previousState','report_eights','previousDistrict','previousBeat','previousUpazila','parameters','categories','footer_report_eights','forest_district_data'));
      
      return $pdf->download(__('admin.report_eight.view') .'.pdf');
    }else{
      return redirect()->route('admin.report_eight');
    }
  }



}
