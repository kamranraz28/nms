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
use App\Models\ReportNine;
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
use Illuminate\Contracts\Session\Session as SessionSession;

use DOMPDF;
use MPDF;

class ReportNineController extends Controller
{
  const VIEW_PATH = 'admin.report_nine.';
  public function __construct()
  {
    date_default_timezone_set('Asia/Dhaka');
  }

  public function index(Request $request)
  {
    $this->authorize('read',ReportNine::class);
    
    //Session::forget(['from_date','to_date','from_date_pre','to_date_pre','forest_division_id','budget_id','stock_type_id','forest_range_id','financial_year']);
    
    $report_nines = [];
    $footer_report_nines = [];
    $forest_district_data = [];
    $parameters = [];
    $table_header = [];
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
      
      $parameters['budget'] = Budget::find($budget_id);
      $parameters['stock_type'] = StockType::find($stock_type_id);

      $parameters['forest_division_en'] = $forest_division->title_en;
      $parameters['forest_division_bn'] = $forest_division->title_bn;

      $parameters['from_date_pre_view'] = date("M/Y",strtotime($from_date_pre . "0 days"));
      $parameters['budget_year_view'] = date("Y",strtotime($from_date)) . '-' . date("Y",strtotime($from_date . "1 years"));
      $parameters['till_date_view'] = date("d M/Y",strtotime($to_date));
      $parameters['to_date_views'] = date("M/Y",strtotime($to_date));
      $parameters['title_date_view'] = date("M,Y",strtotime($from_date));

      $parameters['from_date_view'] = date("d/m/Y",strtotime($from_date));
      $parameters['to_date_view'] = date("d/m/Y",strtotime($to_date));
      // For display
      //dd($parameters);
      
      // Nedd to dynamic
      $from_date_pre = date("Y-m-d",strtotime("2015-01-01"));
      //dd($parameters);

      $opening_stock = [];
      $current_sale = [];
      $closing_stock = [];
      // For table header data

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

      
      // for forest district
        $pre_stock_arr = [];
        $current_total_stock_in_arr = [];
        $current_total_stock_out_arr = [];
        $current_total_stock_arr = [];
      // for forest district
      
      #beat loop
      foreach ($forest_beats as $bkey => $forest_beat) {
        $forest_beat_id = $forest_beat->id;
        //$forest_beats = ForestBeat::lwd()->where(['forest_beat_id'=>$forest_beat_id])->get();
        //range code
        #First Step
        if ($budget_id == 'all' &&  $stock_type_id == 'all') {
          #code for opening stock
            $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',

                DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                WHERE t2.category_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ))  as stock"))
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $opening_stock = [];
            foreach ($categories as $key => $category) {
              array_push($opening_stock,$category['stock']);
            }
          #code for opening stock
          #code for current sale
            $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',
                DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' )  as stock_out")
                )
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $current_sale = [];
            foreach ($categories as $key => $category) {
              array_push($current_sale,$category['stock_out']);
            }
          #code for current sale
          #code for closing stock
            $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',
                  DB::raw("(((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                  WHERE t2.category_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                  (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) - (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' ))  as closing_stock"),
                )
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $closing_stock = [];
            foreach ($categories as $key => $category) {
              array_push($closing_stock,$category['closing_stock']);
            }
          #code for closing stock
          
          $report_nines[] = [
            'district_en' => @$forest_beat->district->title_en,
            'district_bn' => @$forest_beat->district->title_bn,
            'upazila_en' => @$forest_beat->upazila->title_en,
            'upazila_bn' => @$forest_beat->upazila->title_bn,
            'forest_beat_id' => $forest_beat_id,
            'forest_beat_en' => $forest_beat->title_en,
            'forest_beat_bn' => $forest_beat->title_bn,
            'opening_stock' => $opening_stock,
            'current_sale' => $current_sale,
            'closing_stock' => $closing_stock,
          ];
        }else if($budget_id != 'all' &&  $stock_type_id != 'all'){
          #code for opening stock
          $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',

                DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                WHERE t2.category_id = t1.id and t2.approved = 1 and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ))  as stock"))
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $opening_stock = [];
            foreach ($categories as $key => $category) {
              array_push($opening_stock,$category['stock']);
            }
          #code for opening stock
          #code for current sale
            $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',
                DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' )  as stock_out")
                )
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $current_sale = [];
            foreach ($categories as $key => $category) {
              array_push($current_sale,$category['stock_out']);
            }
          #code for current sale
          #code for closing stock
            $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',
                  DB::raw("(((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                  WHERE t2.category_id = t1.id and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                  (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.category_id = t1.id and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) - (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.category_id = t1.id and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' ))  as closing_stock"),
                )
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $closing_stock = [];
            foreach ($categories as $key => $category) {
              array_push($closing_stock,$category['closing_stock']);
            }
          #code for closing stock
          
          $report_nines[] = [
            'district_en' => @$forest_beat->district->title_en,
            'district_bn' => @$forest_beat->district->title_bn,
            'upazila_en' => @$forest_beat->upazila->title_en,
            'upazila_bn' => @$forest_beat->upazila->title_bn,
            'forest_beat_id' => $forest_beat_id,
            'forest_beat_en' => $forest_beat->title_en,
            'forest_beat_bn' => $forest_beat->title_bn,
            'opening_stock' => $opening_stock,
            'current_sale' => $current_sale,
            'closing_stock' => $closing_stock,
          ];
        }else if($budget_id == 'all' &&  $stock_type_id != 'all'){
          #code for opening stock
          $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',

                DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                WHERE t2.category_id = t1.id and t2.approved = 1 and t2.stock_type_id = {$stock_type_id} and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ))  as stock"))
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $opening_stock = [];
            foreach ($categories as $key => $category) {
              array_push($opening_stock,$category['stock']);
            }
          #code for opening stock
          #code for current sale
            $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',
                DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' )  as stock_out")
                )
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $current_sale = [];
            foreach ($categories as $key => $category) {
              array_push($current_sale,$category['stock_out']);
            }
          #code for current sale
          #code for closing stock
            $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',
                  DB::raw("(((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                  WHERE t2.category_id = t1.id and t2.stock_type_id = {$stock_type_id} and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                  (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.category_id = t1.id and t2.stock_type_id = {$stock_type_id} and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) - (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.category_id = t1.id and t2.stock_type_id = {$stock_type_id} and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' ))  as closing_stock"),
                )
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $closing_stock = [];
            foreach ($categories as $key => $category) {
              array_push($closing_stock,$category['closing_stock']);
            }
          #code for closing stock
          
          $report_nines[] = [
            'district_en' => @$forest_beat->district->title_en,
            'district_bn' => @$forest_beat->district->title_bn,
            'upazila_en' => @$forest_beat->upazila->title_en,
            'upazila_bn' => @$forest_beat->upazila->title_bn,
            'forest_beat_id' => $forest_beat_id,
            'forest_beat_en' => $forest_beat->title_en,
            'forest_beat_bn' => $forest_beat->title_bn,
            'opening_stock' => $opening_stock,
            'current_sale' => $current_sale,
            'closing_stock' => $closing_stock,
          ];
        }else if($budget_id != 'all' &&  $stock_type_id == 'all'){
          #code for opening stock
          $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',

                DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                WHERE t2.category_id = t1.id and t2.approved = 1 and t2.budget_id = {$budget_id} and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ))  as stock"))
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $opening_stock = [];
            foreach ($categories as $key => $category) {
              array_push($opening_stock,$category['stock']);
            }
          #code for opening stock
          #code for current sale
            $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',
                DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' )  as stock_out")
                )
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $current_sale = [];
            foreach ($categories as $key => $category) {
              array_push($current_sale,$category['stock_out']);
            }
          #code for current sale
          #code for closing stock
            $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',
                  DB::raw("(((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                  WHERE t2.category_id = t1.id and t2.budget_id = {$budget_id} and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                  (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.category_id = t1.id and t2.budget_id = {$budget_id} and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) - (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.category_id = t1.id and t2.budget_id = {$budget_id} and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' ))  as closing_stock"),
                )
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $closing_stock = [];
            foreach ($categories as $key => $category) {
              array_push($closing_stock,$category['closing_stock']);
            }
          #code for closing stock
          
          $report_nines[] = [
            'district_en' => @$forest_beat->district->title_en,
            'district_bn' => @$forest_beat->district->title_bn,
            'upazila_en' => @$forest_beat->upazila->title_en,
            'upazila_bn' => @$forest_beat->upazila->title_bn,
            'forest_beat_id' => $forest_beat_id,
            'forest_beat_en' => $forest_beat->title_en,
            'forest_beat_bn' => $forest_beat->title_bn,
            'opening_stock' => $opening_stock,
            'current_sale' => $current_sale,
            'closing_stock' => $closing_stock,
          ];
        }
        #First Step
      }
      #beat loop
      $forest_beat_id = Session::get('forest_beat_id');
      //dd($forest_beat_id);
      if ($forest_beat_id == 'all') {
        $authUser = Auth::guard('admin')->user()->load(['userType']);
        if ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
          $forest_beat_id = $authUser->forest_beat_id;
          #division loop
          #First Step
          if ($budget_id == 'all' &&  $stock_type_id == 'all') {
            #code for opening stock
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',

                  DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                  WHERE t2.category_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}') as stock_in"),

                  DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) as stock_out"),

                  DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                  WHERE t2.category_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                  (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) as stock"))
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $opening_stock = [];
              foreach ($categories as $key => $category) {
                array_push($opening_stock,$category['stock']);
              }
            #code for opening stock
            #code for current sale
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                  DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' ) as stock_out")
                  )
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $current_sale = [];
              foreach ($categories as $key => $category) {
                array_push($current_sale,$category['stock_out']);
              }
            #code for current sale
            #code for closing stock
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                    DB::raw("(((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                    WHERE t2.category_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                    (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                    WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) - (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                    WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' )) as closing_stock"),
                  )
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $closing_stock = [];
              foreach ($categories as $key => $category) {
                array_push($closing_stock,$category['closing_stock']);
              }
            #code for closing stock
            
            $footer_report_nines[] = [
              'opening_stock' => $opening_stock,
              'current_sale' => $current_sale,
              'closing_stock' => $closing_stock,
            ];
          }else if ($budget_id != 'all' &&  $stock_type_id != 'all') {
            #code for opening stock
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                
                  DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                  WHERE t2.category_id = t1.id and t2.approved = 1 and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                  (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) as stock"))
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $opening_stock = [];
              foreach ($categories as $key => $category) {
                array_push($opening_stock,$category['stock']);
              }
            #code for opening stock
            #code for current sale
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                  DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' ) as stock_out")
                  )
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $current_sale = [];
              foreach ($categories as $key => $category) {
                array_push($current_sale,$category['stock_out']);
              }
            #code for current sale
            #code for closing stock
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                    DB::raw("(((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                    WHERE t2.category_id = t1.id and t2.approved = 1 and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                    (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                    WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) - (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                    WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' )) as closing_stock"),
                  )
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $closing_stock = [];
              foreach ($categories as $key => $category) {
                array_push($closing_stock,$category['closing_stock']);
              }
            #code for closing stock
            
            $footer_report_nines[] = [
              'opening_stock' => $opening_stock,
              'current_sale' => $current_sale,
              'closing_stock' => $closing_stock,
            ];
          }else if ($budget_id == 'all' &&  $stock_type_id != 'all') {
            #code for opening stock
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                
                  DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                  WHERE t2.category_id = t1.id and t2.approved = 1 and t2.stock_type_id = {$stock_type_id} and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                  (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) as stock"))
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $opening_stock = [];
              foreach ($categories as $key => $category) {
                array_push($opening_stock,$category['stock']);
              }
            #code for opening stock
            #code for current sale
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                  DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' ) as stock_out")
                  )
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $current_sale = [];
              foreach ($categories as $key => $category) {
                array_push($current_sale,$category['stock_out']);
              }
            #code for current sale
            #code for closing stock
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                    DB::raw("(((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                    WHERE t2.category_id = t1.id and t2.approved = 1 and t2.stock_type_id = {$stock_type_id} and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                    (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                    WHERE t2.approved = 1 and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) - (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                    WHERE t2.approved = 1 and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' )) as closing_stock"),
                  )
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $closing_stock = [];
              foreach ($categories as $key => $category) {
                array_push($closing_stock,$category['closing_stock']);
              }
            #code for closing stock
            
            $footer_report_nines[] = [
              'opening_stock' => $opening_stock,
              'current_sale' => $current_sale,
              'closing_stock' => $closing_stock,
            ];
          }else if ($budget_id != 'all' &&  $stock_type_id == 'all') {
            #code for opening stock
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                
                  DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                  WHERE t2.category_id = t1.id and t2.approved = 1 and t2.budget_id = {$budget_id} and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                  (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) as stock"))
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $opening_stock = [];
              foreach ($categories as $key => $category) {
                array_push($opening_stock,$category['stock']);
              }
            #code for opening stock
            #code for current sale
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                  DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' ) as stock_out")
                  )
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $current_sale = [];
              foreach ($categories as $key => $category) {
                array_push($current_sale,$category['stock_out']);
              }
            #code for current sale
            #code for closing stock
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                    DB::raw("(((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                    WHERE t2.category_id = t1.id and t2.approved = 1 and t2.budget_id = {$budget_id} and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                    (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                    WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) - (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                    WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' )) as closing_stock"),
                  )
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $closing_stock = [];
              foreach ($categories as $key => $category) {
                array_push($closing_stock,$category['closing_stock']);
              }
            #code for closing stock
            
            $footer_report_nines[] = [
              'opening_stock' => $opening_stock,
              'current_sale' => $current_sale,
              'closing_stock' => $closing_stock,
            ];
          }
          #First Step
          #division loop
        }else{
          #division loop
          #First Step
          if ($budget_id == 'all' &&  $stock_type_id == 'all') {
            #code for opening stock
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',

                  DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                  WHERE t2.category_id = t1.id and t2.approved = 1 and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}')  as stock_in"),

                  DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )  as stock_out"),

                  DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                  WHERE t2.category_id = t1.id and t2.approved = 1 and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                  (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ))  as stock"))
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $opening_stock = [];
              foreach ($categories as $key => $category) {
                array_push($opening_stock,$category['stock']);
              }
            #code for opening stock
            #code for current sale
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                  DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' )  as stock_out")
                  )
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $current_sale = [];
              foreach ($categories as $key => $category) {
                array_push($current_sale,$category['stock_out']);
              }
            #code for current sale
            #code for closing stock
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                    DB::raw("(((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                    WHERE t2.category_id = t1.id and t2.approved = 1 and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                    (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                    WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) - (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                    WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' ))  as closing_stock"),
                  )
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $closing_stock = [];
              foreach ($categories as $key => $category) {
                array_push($closing_stock,$category['closing_stock']);
              }
            #code for closing stock
            
            $footer_report_nines[] = [
              'opening_stock' => $opening_stock,
              'current_sale' => $current_sale,
              'closing_stock' => $closing_stock,
            ];
          }else if ($budget_id != 'all' &&  $stock_type_id != 'all') {
            #code for opening stock
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                
                  DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                  WHERE t2.category_id = t1.id and t2.approved = 1 and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                  (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ))  as stock"))
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $opening_stock = [];
              foreach ($categories as $key => $category) {
                array_push($opening_stock,$category['stock']);
              }
            #code for opening stock
            #code for current sale
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                  DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' )  as stock_out")
                  )
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $current_sale = [];
              foreach ($categories as $key => $category) {
                array_push($current_sale,$category['stock_out']);
              }
            #code for current sale
            #code for closing stock
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                    DB::raw("(((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                    WHERE t2.category_id = t1.id and t2.approved = 1 and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                    (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                    WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) - (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                    WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' ))  as closing_stock"),
                  )
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $closing_stock = [];
              foreach ($categories as $key => $category) {
                array_push($closing_stock,$category['closing_stock']);
              }
            #code for closing stock
            
            $footer_report_nines[] = [
              'opening_stock' => $opening_stock,
              'current_sale' => $current_sale,
              'closing_stock' => $closing_stock,
            ];
          }else if ($budget_id == 'all' &&  $stock_type_id != 'all') {
            #code for opening stock
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                
                  DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                  WHERE t2.category_id = t1.id and t2.approved = 1 and t2.stock_type_id = {$stock_type_id} and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                  (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ))  as stock"))
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $opening_stock = [];
              foreach ($categories as $key => $category) {
                array_push($opening_stock,$category['stock']);
              }
            #code for opening stock
            #code for current sale
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                  DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' )  as stock_out")
                  )
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $current_sale = [];
              foreach ($categories as $key => $category) {
                array_push($current_sale,$category['stock_out']);
              }
            #code for current sale
            #code for closing stock
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                    DB::raw("(((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                    WHERE t2.category_id = t1.id and t2.approved = 1 and t2.stock_type_id = {$stock_type_id} and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                    (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                    WHERE t2.approved = 1 and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) - (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                    WHERE t2.approved = 1 and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' ))  as closing_stock"),
                  )
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $closing_stock = [];
              foreach ($categories as $key => $category) {
                array_push($closing_stock,$category['closing_stock']);
              }
            #code for closing stock
            
            $footer_report_nines[] = [
              'opening_stock' => $opening_stock,
              'current_sale' => $current_sale,
              'closing_stock' => $closing_stock,
            ];
          }else if ($budget_id != 'all' &&  $stock_type_id == 'all') {
            #code for opening stock
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                
                  DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                  WHERE t2.category_id = t1.id and t2.approved = 1 and t2.budget_id = {$budget_id} and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                  (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.category_id = t1.id and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ))  as stock"))
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $opening_stock = [];
              foreach ($categories as $key => $category) {
                array_push($opening_stock,$category['stock']);
              }
            #code for opening stock
            #code for current sale
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                  DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.category_id = t1.id and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' )  as stock_out")
                  )
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $current_sale = [];
              foreach ($categories as $key => $category) {
                array_push($current_sale,$category['stock_out']);
              }
            #code for current sale
            #code for closing stock
              $query = DB::table('categories as t1')
                ->select('t1.id','t1.title_en','t1.title_bn',
                    DB::raw("(((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                    WHERE t2.category_id = t1.id and t2.approved = 1 and t2.budget_id = {$budget_id} and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                    (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                    WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.category_id = t1.id and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) - (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                    WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.category_id = t1.id and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' ))  as closing_stock"),
                  )
          
                ->where('t1.last',1)
                ->get();
              $categories = json_decode(json_encode($query), True);
            
              $closing_stock = [];
              foreach ($categories as $key => $category) {
                array_push($closing_stock,$category['closing_stock']);
              }
            #code for closing stock
            
            $footer_report_nines[] = [
              'opening_stock' => $opening_stock,
              'current_sale' => $current_sale,
              'closing_stock' => $closing_stock,
            ];
          }
          #First Step
          #division loop
        }
        
      } else {
        #division loop
        #First Step
        if ($budget_id == 'all' &&  $stock_type_id == 'all') {
          #code for opening stock
            $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',

                DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                WHERE t2.category_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}') as stock_in"),

                DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) as stock_out"),

                DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                WHERE t2.category_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) as stock"))
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $opening_stock = [];
            foreach ($categories as $key => $category) {
              array_push($opening_stock,$category['stock']);
            }
          #code for opening stock
          #code for current sale
            $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',
                DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' ) as stock_out")
                )
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $current_sale = [];
            foreach ($categories as $key => $category) {
              array_push($current_sale,$category['stock_out']);
            }
          #code for current sale
          #code for closing stock
            $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',
                  DB::raw("(((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                  WHERE t2.category_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                  (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) - (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' )) as closing_stock"),
                )
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $closing_stock = [];
            foreach ($categories as $key => $category) {
              array_push($closing_stock,$category['closing_stock']);
            }
          #code for closing stock
          
          $footer_report_nines[] = [
            'opening_stock' => $opening_stock,
            'current_sale' => $current_sale,
            'closing_stock' => $closing_stock,
          ];
        }else if ($budget_id != 'all' &&  $stock_type_id != 'all') {
          #code for opening stock
            $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',
              
                DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                WHERE t2.category_id = t1.id and t2.approved = 1 and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) as stock"))
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $opening_stock = [];
            foreach ($categories as $key => $category) {
              array_push($opening_stock,$category['stock']);
            }
          #code for opening stock
          #code for current sale
            $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',
                DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' ) as stock_out")
                )
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $current_sale = [];
            foreach ($categories as $key => $category) {
              array_push($current_sale,$category['stock_out']);
            }
          #code for current sale
          #code for closing stock
            $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',
                  DB::raw("(((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                  WHERE t2.category_id = t1.id and t2.approved = 1 and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                  (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) - (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' )) as closing_stock"),
                )
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $closing_stock = [];
            foreach ($categories as $key => $category) {
              array_push($closing_stock,$category['closing_stock']);
            }
          #code for closing stock
          
          $footer_report_nines[] = [
            'opening_stock' => $opening_stock,
            'current_sale' => $current_sale,
            'closing_stock' => $closing_stock,
          ];
        }else if ($budget_id == 'all' &&  $stock_type_id != 'all') {
          #code for opening stock
            $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',
              
                DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                WHERE t2.category_id = t1.id and t2.approved = 1 and t2.stock_type_id = {$stock_type_id} and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) as stock"))
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $opening_stock = [];
            foreach ($categories as $key => $category) {
              array_push($opening_stock,$category['stock']);
            }
          #code for opening stock
          #code for current sale
            $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',
                DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' ) as stock_out")
                )
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $current_sale = [];
            foreach ($categories as $key => $category) {
              array_push($current_sale,$category['stock_out']);
            }
          #code for current sale
          #code for closing stock
            $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',
                  DB::raw("(((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                  WHERE t2.category_id = t1.id and t2.approved = 1 and t2.stock_type_id = {$stock_type_id} and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                  (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) - (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.stock_type_id = {$stock_type_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' )) as closing_stock"),
                )
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $closing_stock = [];
            foreach ($categories as $key => $category) {
              array_push($closing_stock,$category['closing_stock']);
            }
          #code for closing stock
          
          $footer_report_nines[] = [
            'opening_stock' => $opening_stock,
            'current_sale' => $current_sale,
            'closing_stock' => $closing_stock,
          ];
        }else if ($budget_id != 'all' &&  $stock_type_id == 'all') {
          #code for opening stock
            $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',
              
                DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                WHERE t2.category_id = t1.id and t2.approved = 1 and t2.budget_id = {$budget_id} and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) as stock"))
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $opening_stock = [];
            foreach ($categories as $key => $category) {
              array_push($opening_stock,$category['stock']);
            }
          #code for opening stock
          #code for current sale
            $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',
                DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' ) as stock_out")
                )
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $current_sale = [];
            foreach ($categories as $key => $category) {
              array_push($current_sale,$category['stock_out']);
            }
          #code for current sale
          #code for closing stock
            $query = DB::table('categories as t1')
              ->select('t1.id','t1.title_en','t1.title_bn',
                  DB::raw("(((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                  WHERE t2.category_id = t1.id and t2.approved = 1 and t2.budget_id = {$budget_id} and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' ) - 
                  (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date_pre}' )) - (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                  WHERE t2.approved = 1 and t2.budget_id = {$budget_id} and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' )) as closing_stock"),
                )
        
              ->where('t1.last',1)
              ->get();
            $categories = json_decode(json_encode($query), True);
          
            $closing_stock = [];
            foreach ($categories as $key => $category) {
              array_push($closing_stock,$category['closing_stock']);
            }
          #code for closing stock
          
          $footer_report_nines[] = [
            'opening_stock' => $opening_stock,
            'current_sale' => $current_sale,
            'closing_stock' => $closing_stock,
          ];
        }
        #First Step
        #division loop
      }
      //dd($footer_report_nines);
    }

    
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

    // For table header data
    $categories = Category::where('last',1)->get();

    Session::put(['report_nines'=>$report_nines, 'footer_report_nines'=>$footer_report_nines, 'parameters'=>$parameters]);
    Session::put(['dreport_nines'=>$report_nines, 'dfooter_report_nines'=>$footer_report_nines, 'dparameters'=>$parameters]);
    
    return view(self::VIEW_PATH . 'index',compact('report_nines','parameters','forest_divisions','forest_ranges','forest_beats','budgets','forest_district_data','financial_years','stock_types','categories','footer_report_nines'));
  }

  public function store(Request $request)
  {
    $this->authorize('create',App\ReportNine::class);
    
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
    $to_date = date("Y-m-d",strtotime($from . "1 months"));
    $to = date("Y-m-d",strtotime($to_date . "-1 days"));
    

    $from_date = date("Y-m-d",strtotime($from));
    $to_date = date("Y-m-d",strtotime($to));

    $from_date_pre = date("Y-m-d",strtotime($from . "-1 months"));
    $to_date_pre = date("Y-m-d",strtotime($to . "-1 months"));

    Session::forget(['from_date','to_date','from_date_pre','to_date_pre','forest_division_id','budget_id','stock_type_id','forest_beat_id','financial_year']);
    Session::put(['from_date'=>$from_date,'to_date'=>$to_date, 'from_date_pre'=>$from_date_pre,'to_date_pre'=>$to_date_pre, 
    'forest_division_id'=>$request->forest_division_id,'budget_id'=>$request->budget_id,'stock_type_id'=>$request->stock_type_id,
    'forest_beat_id'=>$request->forest_beat_id,'financial_year_id'=>$request->financial_year_id]);
    //dd(Session::all());

    return redirect()->route('admin.report_nine');

  }

  public function print()
  {
    $this->authorize('print',App\ReportNine::class);
    $report_nines = [];
    $footer_report_nines = [];
    $forest_district_data = [];
    @$report_nines = Session::get('report_nines');
    @$footer_report_nines = Session::get('footer_report_nines');
    @$parameters = Session::get('parameters');
    $categories = Category::where('last',1)->get();
    //return $report_nines;
    if (Session::get('report_nines')) {
      //$pdf = DOMPDF::loadView(self::VIEW_PATH . 'print', compact('report_nines','parameters','categories','footer_report_nines','forest_district_data'))->setPaper('a4', 'landscape')->setWarnings(false);
      //$pdf = MPDF::loadView(self::VIEW_PATH . 'print', compact('report_nines','parameters','categories','footer_report_nines','forest_district_data'));
      //return $pdf->download(__('admin.report_nine.print') .'.pdf');
      return view(self::VIEW_PATH . 'print', compact('report_nines','parameters','categories','footer_report_nines','forest_district_data'));
    }else{
      return redirect()->route('admin.report_nine');
    }
    
  }

  public function download()
  {
    $this->authorize('download',App\ReportNine::class);
    $report_nines = [];
    $footer_report_nines = [];
    $forest_district_data = [];
    @$report_nines = Session::get('dreport_nines');
    @$footer_report_nines = Session::get('dfooter_report_nines');
    @$parameters = Session::get('dparameters');
    $categories = Category::where('last',1)->get();
    //return $report_nines;
    if (Session::get('dreport_nines')) {

      //$pdf = MPDF::loadView(self::VIEW_PATH . 'download', compact('report_nines','parameters','categories','footer_report_nines','forest_district_data'))->setOptions(['dpi' => 150, 'defaultFont' => 'Siyam Rupali'])->setPaper('a4', 'landscape')->setWarnings(false)->save('myfile.pdf');
      $pdf = MPDF::loadView(self::VIEW_PATH . 'download', compact('report_nines','parameters','categories','footer_report_nines','forest_district_data'));
      
      return $pdf->download(__('admin.report_nine.view') .'.pdf');
      //return view(self::VIEW_PATH . 'download', compact('report_nines','parameters','categories','footer_report_nines','forest_district_data'));
    }else{
      return redirect()->route('admin.report_nine');
    }
    
  }



}