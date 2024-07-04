<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sale;
use App\Models\Admin;
use App\Models\Nursery;
use App\Models\Purchase;
use App\Models\StockType;
use App\Models\ForestBeat;
use App\Models\ForestRange;
use App\Models\ForestState;
use Illuminate\Http\Request;
use App\Models\FinancialYear;
use App\Models\ForestDivision;
use App\Helper\NumberToBanglaWord;
use App\Models\Lang as ModelsLang;
use Illuminate\Support\Facades\DB;
use App\Helper\EnglishToBanglaDate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\File;

class DashboardController extends Controller
{
    const VIEW_PATH = 'admin.dashboard.';

    public function __construct()
    {

    }
     
    

    public function index()
    {
      //$langs = File::getRequire(base_path().'/resources/lang/bn/admin.php');
      //dd($langs);

        // $langs = Lang::get('admin');
        // foreach ($langs as $key => $value) {
        //     $data['page'] = $key;
        //     foreach ($value as $key1 => $value1) {
        //     DB::table('langs')->where('key', $key1)->update(['lang_2' => $value1]);
        //     }
        // }
      
      $yearl_total_stock = [];
      $financial_years = FinancialYear::get();
      // dd($financial_years);

      $financial_year = FinancialYear::latest()->first();
      // dd($financial_year);

      $yearl_total_stock = self::yearl_total_stock($financial_year->id);
      //dd($yearl_total_stock);

      $yearly_stock_category_wise_datas = self::yearly_stock_category_wise(date("Y-m-d"));
      
     $montlhy_sales_category_wise_datas = self::monthly_sales_category_wise();
      
    $yearly_stock_purpose_wise_datas = self::yearly_stock_purpose_wise(date("Y-m-d"));
      $yearly_stock_seedlink_wise_datas = self::yearly_stock_seedlink_wise(date("Y-m-d"),$limit = 5);

      $app_status_data = self::app_status();

     return view(self::VIEW_PATH . 'index', compact('financial_years','yearl_total_stock','yearly_stock_category_wise_datas','yearly_stock_purpose_wise_datas','yearly_stock_seedlink_wise_datas','montlhy_sales_category_wise_datas','app_status_data'));
    }


    public function yearl_total_stock($id){
      $financial_year = FinancialYear::findOrFail($id);
      $from = $financial_year->year .'-'.'07'.'-'.'01';
      $to = $financial_year->year + 1 .'-'.'06'.'-'.'30';

      $from_date = date("Y-m-d",strtotime($from));
      $to_date = date("Y-m-d",strtotime($to));

      $from_date_pre1 = date("Y-m-d",strtotime($from . "-1 years"));
      $to_date_pre = date("Y-m-d",strtotime($to . "-1 years"));

      // Nedd to dynamic
      $from_date_pre = date("Y-m-d",strtotime("2015-01-01"));

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

      foreach ($forest_beats as $key => $forest_beat) {
        $forest_beat_id = $forest_beat->id;
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
        #code


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
      }

      $forest_district_data = [
        'id'=>$id,
        'pre_stock_arr_total' => (app()->getLocale() == 'en') ? array_sum($pre_stock_arr) : NumberToBanglaWord::engToBn(array_sum($pre_stock_arr)),
        'current_total_stock_in_arr' => (app()->getLocale() == 'en') ? array_sum($current_total_stock_in_arr) : NumberToBanglaWord::engToBn(array_sum($current_total_stock_in_arr)),
        'current_total_stock_out_arr' => (app()->getLocale() == 'en') ? array_sum($current_total_stock_out_arr) : NumberToBanglaWord::engToBn(array_sum($current_total_stock_out_arr)),
        'current_total_stock_arr' => (app()->getLocale() == 'en') ? array_sum($current_total_stock_arr) : NumberToBanglaWord::engToBn(array_sum($current_total_stock_arr)),

        'from_date' => (app()->getLocale() == 'en') ? date('d/m/Y', strtotime($from_date)) : EnglishToBanglaDate::dateFormatEnglishToBangla(date('d/m/Y', strtotime($from_date))),
        'to_date' => (app()->getLocale() == 'en') ? date('d/m/Y', strtotime($to_date)) : EnglishToBanglaDate::dateFormatEnglishToBangla(date('d/m/Y', strtotime($to_date))),
        'from_date_pre' => (app()->getLocale() == 'en') ? date('d/m/Y', strtotime($from_date_pre)) : EnglishToBanglaDate::dateFormatEnglishToBangla(date('d/m/Y', strtotime($from_date_pre))),
        'to_date_pre' => (app()->getLocale() == 'en') ? date('d/m/Y', strtotime($to_date_pre)) : EnglishToBanglaDate::dateFormatEnglishToBangla(date('d/m/Y', strtotime($to_date_pre))),
      ];

      return $forest_district_data;
    }


    public function yearly_stock_category_and_role_wise($date){

      // Nedd to dynamic
      $from_date_pre = date("Y-m-d",strtotime("2015-01-01"));
      $to_date = date("Y-m-d",strtotime($date));

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
      
      
      $datas = [];
      
      # For cat - 1
      $stock_1 = [];
      $stock_arr_1 = [];
      foreach ($forest_beats as $key => $forest_beat) {
        $forest_beat_id = $forest_beat->id;
        #code for opening stock
        $query = DB::table('categories as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.category_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))
  
        ->where(['t1.last'=>1, 't1.id'=>1])
        ->first();
        $category = json_decode(json_encode($query), True);
        //
        
        $stock_1[] = $category['stock'];
        $stock_arr_1 = [
          'id' => $category['id'],
          'title_en' => $category['title_en'],
          'title_bn' => $category['title_bn'],
          'stock' => array_sum($stock_1),
        ];
        #code for opening stock
      }
      # For cat - 1
      # For cat - 2
      $stock_2 = [];
      $stock_arr_2 = [];
      foreach ($forest_beats as $key => $forest_beat) {
        $forest_beat_id = $forest_beat->id;
        #code for opening stock
        $query = DB::table('categories as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.category_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))
  
        ->where(['t1.last'=>1, 't1.id'=>2])
        ->first();
        $category = json_decode(json_encode($query), True);
        //
        
        $stock_2[] = $category['stock'];
        $stock_arr_2 = [
          'id' => $category['id'],
          'title_en' => $category['title_en'],
          'title_bn' => $category['title_bn'],
          'stock' => array_sum($stock_2),
        ];
        #code for opening stock
      }
      # For cat - 2
      # For cat - 3
      $stock_3 = [];
      $stock_arr_3 = [];
      foreach ($forest_beats as $key => $forest_beat) {
        $forest_beat_id = $forest_beat->id;
        #code for opening stock
        $query = DB::table('categories as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.category_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))
  
        ->where(['t1.last'=>1, 't1.id'=>3])
        ->first();
        $category = json_decode(json_encode($query), True);
        //
        
        $stock_3[] = $category['stock'];
        $stock_arr_3 = [
          'id' => $category['id'],
          'title_en' => $category['title_en'],
          'title_bn' => $category['title_bn'],
          'stock' => array_sum($stock_3),
        ];
        #code for opening stock
      }
      # For cat - 3
      # For cat - 4
      $stock_4 = [];
      $stock_arr_4 = [];
      foreach ($forest_beats as $key => $forest_beat) {
        $forest_beat_id = $forest_beat->id;
        #code for opening stock
        $query = DB::table('categories as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.category_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))
  
        ->where(['t1.last'=>1, 't1.id'=>4])
        ->first();
        $category = json_decode(json_encode($query), True);
        //
        
        $stock_4[] = $category['stock'];
        $stock_arr_4 = [
          'id' => $category['id'],
          'title_en' => $category['title_en'],
          'title_bn' => $category['title_bn'],
          'stock' => array_sum($stock_4),
        ];
        #code for opening stock
      }
      # For cat - 4
      # For cat - 5
      $stock_5 = [];
      $stock_arr_5 = [];
      foreach ($forest_beats as $key => $forest_beat) {
        $forest_beat_id = $forest_beat->id;
        #code for opening stock
        $query = DB::table('categories as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.category_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.category_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))
  
        ->where(['t1.last'=>1, 't1.id'=>5])
        ->first();
        $category = json_decode(json_encode($query), True);
        //
        
        $stock_5[] = $category['stock'];
        $stock_arr_5 = [
          'id' => $category['id'],
          'title_en' => $category['title_en'],
          'title_bn' => $category['title_bn'],
          'stock' => array_sum($stock_5),
        ];
        #code for opening stock
      }
      # For cat - 5

      $datas[] = $stock_arr_1;
      $datas[] = $stock_arr_2;
      $datas[] = $stock_arr_3;
      $datas[] = $stock_arr_4;
      $datas[] = $stock_arr_5;


      return $datas;

    }

    public function yearly_stock_category_wise($date){
      
      // Nedd to dynamic
      $from_date_pre = date("Y-m-d",strtotime("2015-01-01"));
      $to_date = date("Y-m-d",strtotime($date));

      $authUser = Auth::guard('admin')->user()->load(['userType']);
      if ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
        $forest_beat_id = $authUser->forest_beat_id;

        #code for opening stock
        $query = DB::table('categories as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.category_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and t2.category_id = t1.id and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.last'=>1, 't1.status'=>1])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock
        
      }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
        $forest_range_id = $authUser->forest_range_id;
        #code for opening stock
        $query = DB::table('categories as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.category_id = t1.id and t2.approved = 1 and t2.forest_range_id = {$forest_range_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_range_id = {$forest_range_id} and t2.category_id = t1.id and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.last'=>1, 't1.status'=>1])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock

      }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
        $forest_division_id = $authUser->forest_division_id;
        #code for opening stock
        $query = DB::table('categories as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.category_id = t1.id and t2.approved = 1 and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_division_id = {$forest_division_id} and t2.category_id = t1.id and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.last'=>1, 't1.status'=>1])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock
      }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
        $forest_state_id = $authUser->forest_state_id;
        #code for opening stock
        $query = DB::table('categories as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.category_id = t1.id and t2.approved = 1 and t2.forest_state_id = {$forest_state_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_state_id = {$forest_state_id} and t2.category_id = t1.id and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.last'=>1, 't1.status'=>1])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock
      }else{
        #code for opening stock
        $query = DB::table('categories as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.category_id = t1.id and t2.approved = 1 and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.category_id = t1.id and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.last'=>1, 't1.status'=>1])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock
      }
      
      $datas = [];
      foreach ($categories as $key => $category) {
        $stock = [];
        $stock[] = $category['stock'];
        $datas[] = [
          'id' => $category['id'],
          'title_en' => $category['title_en'],
          'title_bn' => $category['title_bn'],
          'stock' => array_sum($stock),
        ];
      }

      return $datas;

    }
    
   public function monthly_sales_category_wise(){
      
      // Nedd to dynamic
    $from_date_pre = date("Y-m-01");
      $to_date = date("Y-m-t");

      $authUser = Auth::guard('admin')->user()->load(['userType']);
      if ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
        $forest_beat_id = $authUser->forest_beat_id;

        #code for opening stock
        $query = DB::table('categories as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and t2.category_id = t1.id and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.last'=>1, 't1.status'=>1])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock
        
      }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
        $forest_range_id = $authUser->forest_range_id;
        #code for opening stock
        $query = DB::table('categories as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_range_id = {$forest_range_id} and t2.category_id = t1.id and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.last'=>1, 't1.status'=>1])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock

      }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
        $forest_division_id = $authUser->forest_division_id;
        #code for opening stock
        $query = DB::table('categories as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_division_id = {$forest_division_id} and t2.category_id = t1.id and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.last'=>1, 't1.status'=>1])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock
      }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
        $forest_state_id = $authUser->forest_state_id;
        #code for opening stock
        $query = DB::table('categories as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_state_id = {$forest_state_id} and t2.category_id = t1.id and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.last'=>1, 't1.status'=>1])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock
      }else{
        #code for opening stock
        $query = DB::table('categories as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.category_id = t1.id and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.last'=>1, 't1.status'=>1])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock
      }
      
      $datas = [];
      foreach ($categories as $key => $category) {
        $stock = [];
        $stock[] = $category['stock'];
        $datas[] = [
          'id' => $category['id'],
          'title_en' => $category['title_en'],
          'title_bn' => $category['title_bn'],
          'stock' => array_sum($stock),
        ];
      }

      return $datas;

    } 
    
    
    
    
    public function yearly_stock_purpose_wise($date){
      
      // Nedd to dynamic
      $from_date_pre = date("Y-m-d",strtotime("2015-01-01"));
      $to_date = date("Y-m-d",strtotime($date));

      $authUser = Auth::guard('admin')->user()->load(['userType']);
      if ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
        $forest_beat_id = $authUser->forest_beat_id;

        #code for opening stock
        $query = DB::table('stock_types as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.stock_type_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and t2.stock_type_id= t1.id and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.status'=>1])
        ->get();
        $stock_types = json_decode(json_encode($query), True);
        #code for opening stock
        
      }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
        $forest_range_id = $authUser->forest_range_id;
        #code for opening stock
        $query = DB::table('stock_types as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.stock_type_id = t1.id and t2.approved = 1 and t2.forest_range_id = {$forest_range_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_range_id = {$forest_range_id} and t2.stock_type_id = t1.id and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.status'=>1])
        ->get();
        $stock_types = json_decode(json_encode($query), True);
        #code for opening stock

      }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
        $forest_division_id = $authUser->forest_division_id;
        #code for opening stock
        $query = DB::table('stock_types as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.stock_type_id = t1.id and t2.approved = 1 and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_division_id = {$forest_division_id} and t2.stock_type_id = t1.id and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.status'=>1])
        ->get();
        $stock_types = json_decode(json_encode($query), True);
        #code for opening stock
      }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
        $forest_state_id = $authUser->forest_state_id;
        #code for opening stock
        $query = DB::table('stock_types as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.stock_type_id = t1.id and t2.approved = 1 and t2.forest_state_id = {$forest_state_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_state_id = {$forest_state_id} and t2.stock_type_id = t1.id and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.status'=>1])
        ->get();
        $stock_types = json_decode(json_encode($query), True);
        #code for opening stock
      }else{
        #code for opening stock
        $query = DB::table('stock_types as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.stock_type_id = t1.id and t2.approved = 1 and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.stock_type_id = t1.id and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.status'=>1])
        ->get();
        $stock_types = json_decode(json_encode($query), True);
        #code for opening stock
      }
      
      $datas = [];
      foreach ($stock_types as $key => $stock_type) {
        $stock = [];
        $stock[] = $stock_type['stock'];
        $datas[] = [
          'id' => $stock_type['id'],
          'title_en' => $stock_type['title_en'],
          'title_bn' => $stock_type['title_bn'],
          'stock' => array_sum($stock),
        ];
      }

      return $datas;

    }
    
    
    
    
    


    public function yearly_stock_seedlink_wise($date, $limit){
      
      // Nedd to dynamic
      $from_date_pre = date("Y-m-d",strtotime("2015-01-01"));
      $to_date = date("Y-m-d",strtotime($date));

      $authUser = Auth::guard('admin')->user()->load(['userType']);
      if ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
        $forest_beat_id = $authUser->forest_beat_id;

        #code for opening stock
        $query = DB::table('products as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',
        't3.title_en as category_en', 't3.title_bn as category_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.product_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and t2.product_id = t1.id and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))
        
        ->leftjoin('categories as t3', 't1.category_id', '=', 't3.id')
        ->where(['t1.status'=>1])
        ->take($limit)
        ->orderBy('t1.id','asc')
        ->get();
        $products = json_decode(json_encode($query), True);
        #code for opening stock
        
      }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
        $forest_range_id = $authUser->forest_range_id;
        #code for opening stock
        $query = DB::table('products as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',
        't3.title_en as category_en', 't3.title_bn as category_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.product_id = t1.id and t2.approved = 1 and t2.forest_range_id = {$forest_range_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_range_id = {$forest_range_id} and t2.product_id = t1.id and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))
        
        ->leftjoin('categories as t3', 't1.category_id', '=', 't3.id')
        ->where(['t1.status'=>1])
        ->take($limit)
        ->orderBy('t1.id','asc')
        ->get();
        $products = json_decode(json_encode($query), True);
        #code for opening stock

      }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
        $forest_division_id = $authUser->forest_division_id;
        #code for opening stock
        $query = DB::table('products as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',
        't3.title_en as category_en', 't3.title_bn as category_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.product_id = t1.id and t2.approved = 1 and t2.forest_division_id = {$forest_division_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_division_id = {$forest_division_id} and t2.product_id = t1.id and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))
        
        ->leftjoin('categories as t3', 't1.category_id', '=', 't3.id')
        ->where(['t1.status'=>1])
        ->take($limit)
        ->orderBy('t1.id','asc')
        ->get();
        $products = json_decode(json_encode($query), True);
        #code for opening stock
      }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
        $forest_state_id = $authUser->forest_state_id;
        #code for opening stock
        $query = DB::table('products as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',
        't3.title_en as category_en', 't3.title_bn as category_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.product_id = t1.id and t2.approved = 1 and t2.forest_state_id = {$forest_state_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_state_id = {$forest_state_id} and t2.product_id = t1.id and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))
        
        ->leftjoin('categories as t3', 't1.category_id', '=', 't3.id')
        ->where(['t1.status'=>1])
        ->take($limit)
        ->orderBy('t1.id','asc')
        ->get();
        $products = json_decode(json_encode($query), True);
        #code for opening stock
      }else{
        #code for opening stock
        $query = DB::table('products as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',
        't3.title_en as category_en', 't3.title_bn as category_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.product_id = t1.id and t2.approved = 1 and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.product_id = t1.id and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))
        
        ->leftjoin('categories as t3', 't1.category_id', '=', 't3.id')
        ->where(['t1.status'=>1])
        ->take($limit)
        ->orderBy('t1.id','asc')
        ->get();
        $products = json_decode(json_encode($query), True);
        #code for opening stock
      }
      
      $datas = [];
      foreach ($products as $key => $product) {
        $stock = [];
        $stock[] = $product['stock'];
        $datas[] = [
          'id' => $product['id'],
          'title_en' => $product['title_en'],
          'title_bn' => $product['title_bn'],
          'category_en' => $product['category_en'],
          'category_bn' => $product['category_bn'],
          'stock' => array_sum($stock),
        ];
      }
      #code for opening stock

      return $datas;

    }
    
    
    
     public function yearly_stock_category_forestdivision_wise($date, $limit, $id){
      
      // Nedd to dynamic
      $from_date_pre = date("Y-m-d",strtotime("2015-01-01"));
      $to_date = date("Y-m-d",strtotime($date));

      $authUser = Auth::guard('admin')->user()->load(['userType']);
      if ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
        $forest_beat_id = $authUser->forest_beat_id;

        #code for opening stock
        $query = DB::table('forest_divisions as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.forest_division_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.status'=>1])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock
        
      }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
        $forest_range_id = $authUser->forest_range_id;
        #code for opening stock
     $query = DB::table('forest_divisions as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

       DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.forest_division_id = t1.id and t2.approved = 1 and t2.forest_range_id = {$forest_range_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_range_id = {$forest_range_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.status'=>1])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock

      }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
        $forest_division_id = $authUser->forest_division_id;
        #code for opening stock
        $query = DB::table('forest_divisions as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

       DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE  t2.approved = 1 and t2.forest_division_id = {$forest_division_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_beat_id = {$forest_state_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock")  )
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock
      }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
        $forest_state_id = $authUser->forest_state_id;
        #code for opening stock
         $query = DB::table('forest_divisions as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

       DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.forest_division_id = t1.id and t2.approved = 1 and t2.forest_state_id = {$forest_state_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_state_id = {$forest_state_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.status'=>1])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock
      }else{
        #code for opening stock
        $query = DB::table('forest_divisions as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

       DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.forest_division_id = t1.id and t2.approved = 1  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.status'=>1])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock
      }
      
      $datas = [];
      foreach ($categories as $key => $category) {
        $stock = [];
        $stock[] = $category['stock'];
        $datas[] = [
          'id' => $category['id'],
          'title_en' => $category['title_en'],
          'title_bn' => $category['title_bn'],
          'stock' => array_sum($stock),
        ];
      }

      return $datas;

    }



    public function yearly_stock_category_range_wise($date, $limit, $id1,$id){
      
      // Nedd to dynamic
      $from_date_pre = date("Y-m-d",strtotime("2015-01-01"));
      $to_date = date("Y-m-d",strtotime($date));

      $authUser = Auth::guard('admin')->user()->load(['userType']);
      if ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
        $forest_beat_id = $authUser->forest_beat_id;

        #code for opening stock
        $query = DB::table('forest_ranges as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.forest_range_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.status'=>1])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock
        
      }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
        $forest_range_id = $authUser->forest_range_id;
        #code for opening stock
     $query = DB::table('forest_divisions as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

       DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.forest_division_id = t1.id and t2.approved = 1 and t2.forest_range_id = {$forest_range_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_range_id = {$forest_range_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.status'=>1])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock

      }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
        $forest_division_id = $authUser->forest_division_id;
        #code for opening stock
        $query = DB::table('forest_divisions as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

       DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE  t2.approved = 1 and t2.forest_division_id = {$forest_division_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_beat_id = {$forest_state_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock")  )
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock
      }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
        $forest_state_id = $authUser->forest_state_id;
        #code for opening stock
         $query = DB::table('forest_divisions as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

       DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.forest_division_id = t1.id and t2.approved = 1 and t2.forest_state_id = {$forest_state_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_state_id = {$forest_state_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.status'=>1])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock
      }else{
        #code for opening stock
        $query = DB::table('forest_ranges as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

       DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.forest_range_id = t1.id and t2.approved = 1  and t2.category_id = {$id1} and t2.forest_division_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_range_id = t1.id  and t2.category_id = {$id1} and t2.forest_division_id = {$id}   and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.status'=>1])
        ->where(['t1.forest_division_id'=>$id])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock
      }
      
      $datas = [];
      foreach ($categories as $key => $category) {
        $stock = [];
        $stock[] = $category['stock'];
        $datas[] = [
          'id' => $category['id'],
          'title_en' => $category['title_en'],
          'title_bn' => $category['title_bn'],
          'stock' => array_sum($stock),
        ];
      }

      return $datas;

    }

    public function yearly_stock_category_bit_wise($date, $limit, $id1,$id){
      
      // Nedd to dynamic
      $from_date_pre = date("Y-m-d",strtotime("2015-01-01"));
      $to_date = date("Y-m-d",strtotime($date));

      $authUser = Auth::guard('admin')->user()->load(['userType']);
      if ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
        $forest_beat_id = $authUser->forest_beat_id;

        #code for opening stock
        $query = DB::table('forest_ranges as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

          DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.forest_range_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.status'=>1])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock
        
      }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
        $forest_range_id = $authUser->forest_range_id;
        #code for opening stock
     $query = DB::table('forest_divisions as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

       DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.forest_division_id = t1.id and t2.approved = 1 and t2.forest_range_id = {$forest_range_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_range_id = {$forest_range_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.status'=>1])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock

      }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
        $forest_division_id = $authUser->forest_division_id;
        #code for opening stock
        $query = DB::table('forest_divisions as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

       DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE  t2.approved = 1 and t2.forest_division_id = {$forest_division_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_beat_id = {$forest_state_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock")  )
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock
      }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
        $forest_state_id = $authUser->forest_state_id;
        #code for opening stock
         $query = DB::table('forest_divisions as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

       DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.forest_division_id = t1.id and t2.approved = 1 and t2.forest_state_id = {$forest_state_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_state_id = {$forest_state_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.status'=>1])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock
      }else{
        #code for opening stock
        $query = DB::table('forest_beats as t1')
        ->select('t1.id','t1.title_en','t1.title_bn',

       DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
          WHERE t2.forest_beat_id = t1.id and t2.approved = 1  and t2.category_id = {$id1} and t2.forest_range_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
          (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
          WHERE t2.approved = 1 and t2.forest_beat_id = t1.id  and t2.category_id = {$id1} and t2.forest_range_id = {$id}   and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

        ->where(['t1.status'=>1])
        ->where(['t1.forest_range_id'=>$id])
        ->get();
        $categories = json_decode(json_encode($query), True);
        #code for opening stock
      }
      
      $datas = [];
      foreach ($categories as $key => $category) {
        $stock = [];
        $stock[] = $category['stock'];
        $datas[] = [
          'id' => $category['id'],
          'title_en' => $category['title_en'],
          'title_bn' => $category['title_bn'],
          'stock' => array_sum($stock),
        ];
      }

      return $datas;

    }



//Seedling wise Dashboard Stock Report

//Seedling wise Dashboard Stock Report (Forest Division)

public function yearly_stock_seedling_forestdivision_wise($date, $limit, $id){
      
  // Nedd to dynamic
  $from_date_pre = date("Y-m-d",strtotime("2015-01-01"));
  $to_date = date("Y-m-d",strtotime($date));

  $authUser = Auth::guard('admin')->user()->load(['userType']);
  if ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
    $forest_beat_id = $authUser->forest_beat_id;

    #code for opening stock
    $query = DB::table('forest_divisions as t1')
    ->select('t1.id','t1.title_en','t1.title_bn',

      DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
      WHERE t2.forest_division_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
      (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
      WHERE t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

    ->where(['t1.status'=>1])
    ->get();
    $categories = json_decode(json_encode($query), True);
    #code for opening stock
    
  }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
    $forest_range_id = $authUser->forest_range_id;
    #code for opening stock
 $query = DB::table('forest_divisions as t1')
    ->select('t1.id','t1.title_en','t1.title_bn',

   DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
      WHERE t2.forest_division_id = t1.id and t2.approved = 1 and t2.forest_range_id = {$forest_range_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
      (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
      WHERE t2.approved = 1 and t2.forest_range_id = {$forest_range_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

    ->where(['t1.status'=>1])
    ->get();
    $categories = json_decode(json_encode($query), True);
    #code for opening stock

  }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
    $forest_division_id = $authUser->forest_division_id;
    #code for opening stock
    $query = DB::table('forest_divisions as t1')
    ->select('t1.id','t1.title_en','t1.title_bn',

   DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
      WHERE  t2.approved = 1 and t2.forest_division_id = {$forest_division_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
      (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
      WHERE t2.approved = 1 and t2.forest_beat_id = {$forest_state_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock")  )
    ->get();
    $categories = json_decode(json_encode($query), True);
    #code for opening stock
  }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
    $forest_state_id = $authUser->forest_state_id;
    #code for opening stock
     $query = DB::table('forest_divisions as t1')
    ->select('t1.id','t1.title_en','t1.title_bn',

   DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
      WHERE t2.forest_division_id = t1.id and t2.approved = 1 and t2.forest_state_id = {$forest_state_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
      (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
      WHERE t2.approved = 1 and t2.forest_state_id = {$forest_state_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

    ->where(['t1.status'=>1])
    ->get();
    $categories = json_decode(json_encode($query), True);
    #code for opening stock
  }else{
    #code for opening stock
    $query = DB::table('forest_divisions as t1')
    ->select('t1.id','t1.title_en','t1.title_bn',

   DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
      WHERE t2.forest_division_id = t1.id and t2.approved = 1  and t2.product_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
      (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
      WHERE t2.approved = 1 and t2.forest_division_id = t1.id  and t2.product_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

    ->where(['t1.status'=>1])
    ->get();
    $categories = json_decode(json_encode($query), True);
    #code for opening stock
  }
  
  $datas = [];
  foreach ($categories as $key => $category) {
    $stock = [];
    $stock[] = $category['stock'];
    $datas[] = [
      'id' => $category['id'],
      'title_en' => $category['title_en'],
      'title_bn' => $category['title_bn'],
      'stock' => array_sum($stock),
    ];
  }

  return $datas;

}

public function yearly_stock_seedling_range_wise($date, $limit, $id1,$id){
      
  // Nedd to dynamic
  $from_date_pre = date("Y-m-d",strtotime("2015-01-01"));
  $to_date = date("Y-m-d",strtotime($date));

  $authUser = Auth::guard('admin')->user()->load(['userType']);
  if ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
    $forest_beat_id = $authUser->forest_beat_id;

    #code for opening stock
    $query = DB::table('forest_ranges as t1')
    ->select('t1.id','t1.title_en','t1.title_bn',

      DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
      WHERE t2.forest_range_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
      (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
      WHERE t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

    ->where(['t1.status'=>1])
    ->get();
    $categories = json_decode(json_encode($query), True);
    #code for opening stock
    
  }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
    $forest_range_id = $authUser->forest_range_id;
    #code for opening stock
 $query = DB::table('forest_divisions as t1')
    ->select('t1.id','t1.title_en','t1.title_bn',

   DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
      WHERE t2.forest_division_id = t1.id and t2.approved = 1 and t2.forest_range_id = {$forest_range_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
      (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
      WHERE t2.approved = 1 and t2.forest_range_id = {$forest_range_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

    ->where(['t1.status'=>1])
    ->get();
    $categories = json_decode(json_encode($query), True);
    #code for opening stock

  }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
    $forest_division_id = $authUser->forest_division_id;
    #code for opening stock
    $query = DB::table('forest_divisions as t1')
    ->select('t1.id','t1.title_en','t1.title_bn',

   DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
      WHERE  t2.approved = 1 and t2.forest_division_id = {$forest_division_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
      (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
      WHERE t2.approved = 1 and t2.forest_beat_id = {$forest_state_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock")  )
    ->get();
    $categories = json_decode(json_encode($query), True);
    #code for opening stock
  }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
    $forest_state_id = $authUser->forest_state_id;
    #code for opening stock
     $query = DB::table('forest_divisions as t1')
    ->select('t1.id','t1.title_en','t1.title_bn',

   DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
      WHERE t2.forest_division_id = t1.id and t2.approved = 1 and t2.forest_state_id = {$forest_state_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
      (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
      WHERE t2.approved = 1 and t2.forest_state_id = {$forest_state_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

    ->where(['t1.status'=>1])
    ->get();
    $categories = json_decode(json_encode($query), True);
    #code for opening stock
  }else{
    #code for opening stock
    $query = DB::table('forest_ranges as t1')
    ->select('t1.id','t1.title_en','t1.title_bn',

   DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
      WHERE t2.forest_range_id = t1.id and t2.approved = 1  and t2.product_id = {$id1} and t2.forest_division_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
      (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
      WHERE t2.approved = 1 and t2.forest_range_id = t1.id  and t2.product_id = {$id1} and t2.forest_division_id = {$id}   and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

    ->where(['t1.status'=>1])
    ->where(['t1.forest_division_id'=>$id])
    ->get();
    $categories = json_decode(json_encode($query), True);
    #code for opening stock
  }
  
  $datas = [];
  foreach ($categories as $key => $category) {
    $stock = [];
    $stock[] = $category['stock'];
    $datas[] = [
      'id' => $category['id'],
      'title_en' => $category['title_en'],
      'title_bn' => $category['title_bn'],
      'stock' => array_sum($stock),
    ];
  }

  return $datas;

}



public function yearly_stock_seedling_bit_wise($date, $limit, $id1,$id){
      
  // Nedd to dynamic
  $from_date_pre = date("Y-m-d",strtotime("2015-01-01"));
  $to_date = date("Y-m-d",strtotime($date));

  $authUser = Auth::guard('admin')->user()->load(['userType']);
  if ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
    $forest_beat_id = $authUser->forest_beat_id;

    #code for opening stock
    $query = DB::table('forest_ranges as t1')
    ->select('t1.id','t1.title_en','t1.title_bn',

      DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
      WHERE t2.forest_range_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
      (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
      WHERE t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

    ->where(['t1.status'=>1])
    ->get();
    $categories = json_decode(json_encode($query), True);
    #code for opening stock
    
  }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
    $forest_range_id = $authUser->forest_range_id;
    #code for opening stock
 $query = DB::table('forest_divisions as t1')
    ->select('t1.id','t1.title_en','t1.title_bn',

   DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
      WHERE t2.forest_division_id = t1.id and t2.approved = 1 and t2.forest_range_id = {$forest_range_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
      (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
      WHERE t2.approved = 1 and t2.forest_range_id = {$forest_range_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

    ->where(['t1.status'=>1])
    ->get();
    $categories = json_decode(json_encode($query), True);
    #code for opening stock

  }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
    $forest_division_id = $authUser->forest_division_id;
    #code for opening stock
    $query = DB::table('forest_divisions as t1')
    ->select('t1.id','t1.title_en','t1.title_bn',

   DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
      WHERE  t2.approved = 1 and t2.forest_division_id = {$forest_division_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
      (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
      WHERE t2.approved = 1 and t2.forest_beat_id = {$forest_state_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock")  )
    ->get();
    $categories = json_decode(json_encode($query), True);
    #code for opening stock
  }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
    $forest_state_id = $authUser->forest_state_id;
    #code for opening stock
     $query = DB::table('forest_divisions as t1')
    ->select('t1.id','t1.title_en','t1.title_bn',

   DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
      WHERE t2.forest_division_id = t1.id and t2.approved = 1 and t2.forest_state_id = {$forest_state_id} and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
      (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
      WHERE t2.approved = 1 and t2.forest_state_id = {$forest_state_id} and t2.forest_division_id = t1.id  and t2.category_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock"))

    ->where(['t1.status'=>1])
    ->get();
    $categories = json_decode(json_encode($query), True);
    #code for opening stock
  }else{
    #code for opening stock
    $query = DB::table('forest_beats as t1')
    ->select('t1.id','t1.title_en','t1.title_bn',

   DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
      WHERE t2.forest_beat_id = t1.id and t2.approved = 1  and t2.product_id = {$id1} and t2.forest_range_id = {$id}  and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ) - 
      (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
      WHERE t2.approved = 1 and t2.forest_beat_id = t1.id  and t2.product_id = {$id1} and t2.forest_range_id = {$id}   and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date_pre}' AND '{$to_date}' ))  as stock ")
    
      )


    ->where(['t1.status'=>1])
    ->where(['t1.forest_range_id'=>$id])
    ->get();
    $categories = json_decode(json_encode($query), True);
    #code for opening stock


  }


  
  $datas = [];
  foreach ($categories as $key => $category) {
    $stock = [];
    $stock[] = $category['stock'];
    $datas[] = [
      'id' => $category['id'],
      'title_en' => $category['title_en'],
      'title_bn' => $category['title_bn'],
      'stock' => array_sum($stock),
    ];
  }

  return $datas;

}






    public function view(){

      $yearly_stock_seedlink_wise_datas = self::yearly_stock_seedlink_wise(date("Y-m-d"),$limit = 10000);
      return view(self::VIEW_PATH . 'view', compact('yearly_stock_seedlink_wise_datas'));
    
    }
    
     public function viewcategory($id ){

      $query = DB::table('categories as t1')
      ->select('t1.id','t1.title_en','t1.title_bn')
      ->where(['t1.id'=>$id])
      ->get();
   
      $categories = json_decode(json_encode($query), True);
      
      foreach ($categories as $key => $category) {
   
        $cats[] = [
          'id' => $category['id'],
          'title_en' => $category['title_en'],
          'title_bn' => $category['title_bn'],
        ];
      }
    
   //return $cats;

      $yearly_stock_category_forestdivision_wise = self::yearly_stock_category_forestdivision_wise(date("Y-m-d"),$limit = 10000, $id);
      return view(self::VIEW_PATH . 'view-category', compact('id','cats','yearly_stock_category_forestdivision_wise'));
    
    }
     
    public function viewcategoryrange($id1, $id){

      $query = DB::table('categories as t1')
      ->select('t1.id','t1.title_en','t1.title_bn')
      ->where(['t1.id'=>$id1])
      ->get();
   
      $categories = json_decode(json_encode($query), True);
      
      foreach ($categories as $key => $category) {
   
        $cats[] = [
          'id' => $category['id'],
          'title_en' => $category['title_en'],
          'title_bn' => $category['title_bn'],
        ];
      }

      $yearly_stock_category_range_wise = self::yearly_stock_category_range_wise(date("Y-m-d"),$limit = 10000, $id1, $id );
      return view(self::VIEW_PATH . 'view-category-range', compact('id1','id','cats','yearly_stock_category_range_wise'));
    
    }
 
    public function viewcategorybit($id1, $id){

      $query = DB::table('categories as t1')
      ->select('t1.id','t1.title_en','t1.title_bn')
      ->where(['t1.id'=>$id1])
      ->get();
   
      $categories = json_decode(json_encode($query), True);
      
      foreach ($categories as $key => $category) {
   
        $cats[] = [
          'id' => $category['id'],
          'title_en' => $category['title_en'],
          'title_bn' => $category['title_bn'],
        ];
      }

      $yearly_stock_category_bit_wise = self::yearly_stock_category_bit_wise(date("Y-m-d"),$limit = 10000, $id1, $id );
      return view(self::VIEW_PATH . 'view-category-bit', compact('cats', 'yearly_stock_category_bit_wise'));
    
    }


     
    public function viewseedling($id){

      $query = DB::table('products as t1')
      ->select('t1.id','t1.title_en','t1.title_bn')
      ->where(['t1.id'=>$id])
      ->get();
   
      $categories = json_decode(json_encode($query), True);
      
      foreach ($categories as $key => $category) {
   
        $cats[] = [
          'id' => $category['id'],
          'title_en' => $category['title_en'],
          'title_bn' => $category['title_bn'],
        ];
      }

      $yearly_stock_seedling_forestdivision_wise = self::yearly_stock_seedling_forestdivision_wise(date("Y-m-d"),$limit = 10000, $id);
      return view(self::VIEW_PATH . 'view-seedling', compact('id','cats','yearly_stock_seedling_forestdivision_wise'));
    
    }

    public function viewseedlingrange($id1, $id){

      $query = DB::table('products as t1')
      ->select('t1.id','t1.title_en','t1.title_bn')
      ->where(['t1.id'=>$id1])
      ->get();
   
      $categories = json_decode(json_encode($query), True);
      
      foreach ($categories as $key => $category) {
   
        $cats[] = [
          'id' => $category['id'],
          'title_en' => $category['title_en'],
          'title_bn' => $category['title_bn'],
        ];
      }


      $yearly_stock_seedling_range_wise = self::yearly_stock_seedling_range_wise(date("Y-m-d"),$limit = 10000, $id1, $id );
      return view(self::VIEW_PATH . 'view-seedling-range', compact('id1','id','cats','yearly_stock_seedling_range_wise'));
    
    }

    public function viewseedlingbit($id1, $id){

      $query = DB::table('products as t1')
      ->select('t1.id','t1.title_en','t1.title_bn')
      ->where(['t1.id'=>$id1])
      ->get();
   
      $categories = json_decode(json_encode($query), True);
      
      foreach ($categories as $key => $category) {
   
        $cats[] = [
          'id' => $category['id'],
          'title_en' => $category['title_en'],
          'title_bn' => $category['title_bn'],
        ];
      }

      $yearly_stock_seedling_bit_wise = self::yearly_stock_seedling_bit_wise(date("Y-m-d"),$limit = 10000, $id1, $id );
      return view(self::VIEW_PATH . 'view-seedling-bit', compact('cats','yearly_stock_seedling_bit_wise'));
    
    }


    public function app_status(){
      $data = [];
      $data['range_purchase'] = Purchase::lwd()->where(['app_status'=>1])->count();
      $data['range_sale'] = Sale::lwd()->where(['app_status'=>1])->count();

      $data['acf_purchase'] = Purchase::lwd()->where(['app_status'=>2])->count();
      $data['acf_sale'] = Sale::lwd()->where(['app_status'=>2])->count();

      $data['dfo_purchase'] = Purchase::lwd()->where(['app_status'=>3])->count();
      $data['dfo_sale'] = Sale::lwd()->where(['app_status'=>3])->count();

      return $data;
      
    }

    public function stock_type(){
      // Stock Type
      $stockTypes = [];
      $query = StockType::get();
      $stock_types = $query->toArray();
      foreach ($stock_types as $key => $stock_type) {
        $query = DB::table('sale_details as t1')
        ->select(
          DB::raw('(CASE WHEN SUM(t1.quantity) IS NULL THEN 0 ELSE SUM(t1.quantity) END) as quantity')
        )
        ->where(['t1.stock_type_id' => $stock_type['id']])
        ->first();
        $stockTypes[$stock_type['id']] = $query->quantity;
      }
      
    }







    

}
