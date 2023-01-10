<?php

namespace App\Http\Controllers\Admin;
use App\Models\Sale;
use App\Models\Admin;
use App\Models\State;
use App\Models\Nursery;

use App\Models\Product;
use App\Models\Upazila;
use App\Models\Category;
use App\Models\District;
use App\Models\Division;
use App\Models\ForestBeat;
use App\Models\SaleDetail;
use App\Models\ForestRange;
use App\Models\ForestState;
use App\Models\ReportThree;
use Illuminate\Http\Request;
use App\Models\ForestDivision;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ReportThreeController extends Controller
{
  const VIEW_PATH = 'admin.report_three.';
  public function __construct()
  {

  }

  public function index(Request $request)
  {
    $this->authorize('read',ReportThree::class);

    //Session::forget(['from_date','to_date']);
    $report_threes = [];
    $parameters = [];
    @$from_date = date("Y-m-d",strtotime("2015-01-01"));
    @$to_date = Session::get('to_date');
    @$forest_state_id = Session::get('forest_state_id');
    @$forest_division_id = Session::get('forest_division_id');
    @$forest_range_id = Session::get('forest_range_id');
    @$forest_beat_id = Session::get('forest_beat_id');
    @$category_id = Session::get('category_id');
    if ($from_date && $to_date) {
      $parameters['from_date'] = $from_date;
      $parameters['to_date'] = $to_date;
      $parameters['forest_state_id'] = $forest_state_id;
      $parameters['forest_division_id'] = $forest_division_id;
      $parameters['forest_range_id'] = $forest_range_id;
      $parameters['forest_beat_id'] = $forest_beat_id;
      $parameters['category_id'] = $category_id;

      //dd($parameters);

      if ($forest_division_id == 'all') {
        $forest_beats = ForestBeat::lwd()->with('division','district','upazila','forestState','forestDivision','forestRange')->get();
      } else if($forest_state_id != 'all' and $forest_division_id == 'all'){
        $forest_beats = ForestBeat::lwd()->with('division','district','upazila','forestState','forestDivision','forestRange')->where(['forest_state_id'=>$forest_state_id])->get();
      } else if($forest_division_id != 'all' and $forest_range_id == 'all'){
        $forest_beats = ForestBeat::lwd()->with('division','district','upazila','forestState','forestDivision','forestRange')->where(['forest_division_id'=>$forest_division_id])->get();
      } else if($forest_range_id != 'all' and $forest_beat_id == 'all'){
        $forest_beats = ForestBeat::lwd()->with('division','district','upazila','forestState','forestDivision','forestRange')->where(['forest_range_id'=>$forest_range_id])->get();
      } else if($forest_beat_id != 'all'){
        //dd($forest_beat_id);
        $forest_beats = ForestBeat::lwd()->with('division','district','upazila','forestState','forestDivision','forestRange')->where(['id'=>$forest_beat_id])->get();
      } else {
        $forest_beats = ForestBeat::lwd()->with('division','district','upazila','forestState','forestDivision','forestRange')->get();
      }

      //dd($forest_beats);

      

      foreach ($forest_beats as $key => $forest_beat) {
        $forest_beat_id = $forest_beat->id;
        //dd($forest_beat_id);
        if ($category_id == 'all') {
          $query = DB::table('products as t1')
                ->select('t1.id','t1.title_en','t1.title_bn','t3.title_en as category_en', 't3.title_bn as category_bn',

                DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                WHERE t2.product_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}')  as stock_in"),

                DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.product_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' )  as stock_out"),

                DB::raw("(SELECT(CASE WHEN SUM(t2.total) IS NULL THEN 0 ELSE SUM(t2.total) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.product_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' )  as total"),

                DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                WHERE t2.product_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' ) - 
                (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.product_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' ))  as stock"))
        
                ->join('categories as t3', 't1.category_id', '=', 't3.id')
                ->get();
        } else {
          $query = DB::table('products as t1')
                ->select('t1.id','t1.title_en','t1.title_bn','t3.title_en as category_en', 't3.title_bn as category_bn',

                DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                WHERE t2.product_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}')  as stock_in"),

                DB::raw("(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.product_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' )  as stock_out"),

                DB::raw("(SELECT(CASE WHEN SUM(t2.total) IS NULL THEN 0 ELSE SUM(t2.total) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.product_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' )  as total"),

                DB::raw("((SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchase_details as t2 
                WHERE t2.product_id = t1.id and t2.approved = 1 and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' ) - 
                (SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM sale_details as t2 
                WHERE t2.approved = 1 and t2.product_id = t1.id and t2.forest_beat_id = {$forest_beat_id} and DATE_FORMAT(t2.vch_date,'%Y-%m-%d') BETWEEN '{$from_date}' AND '{$to_date}' ))  as stock"))
        
                ->join('categories as t3', 't1.category_id', '=', 't3.id')
                ->where('t1.category_id',$category_id)
                ->get();
        }

       
        
        
        $products = json_decode(json_encode($query), True);
        //dd($forest_beat);
        foreach ($products as $key => $product) {
          $report_threes[] = [
            'forest_state_en' => $forest_beat['forestState']['title_en'],
            'forest_state_bn' => $forest_beat['forestState']['title_bn'],
            'forest_division_en' => $forest_beat['forestDivision']['title_en'],
            'forest_division_bn' => $forest_beat['forestDivision']['title_bn'],
            'forest_range_en' => $forest_beat['forestRange']['title_en'],
            'forest_range_bn' => $forest_beat['forestRange']['title_bn'],
            'forest_beat_en' => $forest_beat->title_en,
            'forest_beat_bn' => $forest_beat->title_bn,
            'category_en' => $product['category_en'],
            'category_bn' => $product['category_bn'],
            'product_en' => $product['title_en'],
            'product_bn' => $product['title_bn'],
            'stock_in' => $product['stock_in'],
            'stock_out' => $product['stock_out'],
            'stock' => $product['stock'],
            'total' => $product['total'],
          ];
        }
      }

      //dd($report_threes);
    }

    $states = State::get();
    $divisions = Division::get();
    //$districts = District::get();
    //$upazilas = Upazila::get();

    $categories = Category::where('last',1)->get();

    // $forest_states = ForestState::get();
    // $forest_divisions = ForestDivision::get();
    // $forest_ranges = ForestRange::get();
    // $forest_beats = ForestBeat::get();

    $authUser = Auth::guard('admin')->user()->load(['userType']);
    if ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
      $forest_division_id = $authUser->forest_division_id;
      $forest_range_id = $authUser->forest_range_id;
      $forest_beat_id = $authUser->forest_beat_id;
      $forest_state_id = $authUser->forest_state_id;
      $forest_states = ForestState::where(['id'=>$forest_state_id])->get();
      $forest_divisions = ForestDivision::where(['id'=>$forest_division_id])->get();
      $forest_ranges = ForestRange::where(['id'=>$forest_range_id])->get();
      $forest_beats = ForestBeat::where(['id'=>$forest_beat_id])->get();
      
    }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
      $forest_division_id = $authUser->forest_division_id;
      $forest_range_id = $authUser->forest_range_id;
      $forest_state_id = $authUser->forest_state_id;
      $forest_states = ForestState::where(['id'=>$forest_state_id])->get();
      $forest_divisions = ForestDivision::where(['id'=>$forest_division_id])->get();
      $forest_ranges = ForestRange::where(['id'=>$forest_range_id])->get();
      $forest_beats = ForestBeat::lwd()->get();

    }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
      $forest_division_id = $authUser->forest_division_id;
      $forest_state_id = $authUser->forest_state_id;
      $forest_states = ForestState::where(['id'=>$forest_state_id])->get();
      $forest_divisions = ForestDivision::where(['id'=>$forest_division_id])->get();
      $forest_ranges = ForestRange::lwd()->get();
      $forest_beats = ForestBeat::lwd()->get();
    }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
      $forest_state_id = $authUser->forest_state_id;
      $forest_states = ForestState::where(['id'=>$forest_state_id])->get();
      $forest_state = ForestState::where(['id'=>$forest_state_id])->first();
      $forest_divisions = ForestDivision::where(['forest_state_id'=>$forest_state->id])->get();
      $forest_ranges = ForestRange::lwd()->get();
      $forest_beats = ForestBeat::lwd()->get();
    }else{
      $forest_states = ForestState::lwd()->get();
      $forest_divisions = ForestDivision::lwd()->get();
      $forest_ranges = ForestRange::lwd()->get();
      $forest_beats = ForestBeat::lwd()->get();
      //dd($forest_divisions);
    }



    return view(self::VIEW_PATH . 'index',compact('report_threes','parameters','divisions','categories','states','forest_states','forest_divisions','forest_ranges','forest_beats'));
  }

  public function store(Request $request)
  {
    $this->authorize('create',App\ReportThree::class);
    
    $this->validate($request, [
      //'name' => 'required|min:1|max:128',
      //'from_date' => 'required',
      'to_date' => 'required',
    ]);
    
    //return $request->all();
    Session::forget(['from_date','to_date','forest_state_id','forest_division_id','forest_range_id','forest_beat_id','category_id']);
    Session::put(['from_date'=>$request->from_date,'to_date'=>$request->to_date, 'forest_state_id'=>$request->forest_state_id, 
    'forest_division_id'=>$request->forest_division_id, 'forest_range_id'=>$request->forest_range_id, 'forest_beat_id'=>$request->forest_beat_id,
    'category_id'=>$request->category_id]);
    //dd(Session::all());

    return redirect()->route('admin.report_three');

  }

  public function print(Request $request, $id)
  {
    $this->authorize('print',App\ReportThree::class);
    
    return view(self::VIEW_PATH . 'print', compact('report_three'));
  }



}
