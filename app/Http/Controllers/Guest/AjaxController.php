<?php
namespace App\Http\Controllers\Guest;
use DB;
use App;
use App\Models\Unit;
use App\Models\User;
use App\Models\Admin;
use App\Models\Nursery;
use App\Models\Product;
use App\Models\Upazila;

use App\Models\District;
use App\Models\Division;
use App\Models\ForestBeat;
use App\Models\ForestRange;
use App\Models\ForestState;
use Illuminate\Http\Request;
use App\Models\ForestDivision;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class AjaxController extends Controller
{
    
    public function getDivision($id)
    {
        if($id){
            return $divisions = Division::where('state_id',$id)->get();
        }
        return [];
        
    }
    
    public function getDsitrict($id)
    {
        if($id){
            return $districts = District::where('division_id',$id)->get();
        }
        return [];
        
    }
    
    public function getUpazila($id)
    {
        if ($id) {
            return $upazilas = Upazila::where('district_id',$id)->get();
        }
        return [];
    }

    public function getUpazilaSelf($id = 0)
    {
        if ($id) {
            return $upazila = Upazila::where('id',$id)->get();
        }
        return [];
    }

    
    public function getForestDivision($id)
    {
        // if($id){
        //     return $forest_divisions = ForestDivision::where('forest_state_id',$id)->get();
        // }
        // return [];

        $authUser = Auth::guard('admin')->user()->load(['userType']);
        if ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
            $forest_division_id = $authUser->forest_division_id;
            $forest_divisions = ForestDivision::where(['id'=>$forest_division_id])->get();
        
        }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
            $forest_division_id = $authUser->forest_division_id;
            $forest_divisions = ForestDivision::where(['id'=>$forest_division_id])->get();

        }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
            $forest_division_id = $authUser->forest_division_id;
            $forest_divisions = ForestDivision::where(['id'=>$forest_division_id])->get();
        }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
            $forest_state_id = $authUser->forest_state_id;
            $forest_divisions = ForestDivision::where(['forest_state_id'=>$forest_state_id])->get();
        }else{
            $forest_divisions = ForestDivision::lwd()->where(['forest_state_id'=>$id])->get();
            //dd($forest_divisions);
        }

        return $forest_divisions;
        
    }
    
    public function getForestRange($id)
    {
        // if($id){
        //     return $forest_ranges = ForestRange::where('forest_division_id',$id)->get();
        // }
        // return [];

        $authUser = Auth::guard('admin')->user()->load(['userType']);
        if ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
            $forest_range_id = $authUser->forest_range_id;
            $forest_ranges = ForestRange::where(['id'=>$forest_range_id])->get();
        }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
            $forest_range_id = $authUser->forest_range_id;
            $forest_ranges = ForestRange::where(['id'=>$forest_range_id])->get();
        }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
            $forest_ranges = ForestRange::lwd()->where(['forest_division_id'=>$id])->get();
        }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
            $forest_ranges = ForestRange::lwd()->where(['forest_division_id'=>$id])->get();
        }else{
            $forest_ranges = ForestRange::lwd()->where(['forest_division_id'=>$id])->get();
        }

        return $forest_ranges;
        
    }

    public function getForestRangeSelf($id = 0)
    {
        if ($id) {
            return $forest_beat = ForestRange::where('id',$id)->get();
        }
        return [];
    }
    
    public function getForestBeat($id)
    {
        // if ($id) {
        //     return $forest_beats = ForestBeat::where('forest_range_id',$id)->get();
        // }
        // return [];

        $authUser = Auth::guard('admin')->user()->load(['userType']);
        if ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
            $forest_beat_id = $authUser->forest_beat_id;
            $forest_beats = ForestBeat::where(['id'=>$forest_beat_id])->get();
        
        }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
            $forest_beats = ForestBeat::lwd()->where(['forest_range_id'=>$id])->get();

        }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
            $forest_beats = ForestBeat::lwd()->where(['forest_range_id'=>$id])->get();
        }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
            $forest_beats = ForestBeat::lwd()->where(['forest_range_id'=>$id])->get();
        }else{
            $forest_beats = ForestBeat::lwd()->where(['forest_range_id'=>$id])->get();
        }

        return $forest_beats;
    }
    
    public function getForestBeatFromDivision($id)
    {
        // if ($id) {
        //     return $forest_beats = ForestBeat::where('forest_division_id',$id)->get();
        // }
        // return [];

        $authUser = Auth::guard('admin')->user()->load(['userType']);
        if ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
            $forest_state_id = $authUser->forest_state_id;
            $forest_division_id = $authUser->forest_division_id;
            $forest_range_id = $authUser->forest_range_id;
            $forest_beat_id = $authUser->forest_beat_id;
            $forest_beats = ForestBeat::where(['id'=>$forest_beat_id])->get();
        
        }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
            $forest_state_id = $authUser->forest_state_id;
            $forest_division_id = $authUser->forest_division_id;
            $forest_range_id = $authUser->forest_range_id;
            $forest_beats = ForestBeat::lwd()->where(['forest_range_id'=>$forest_range_id])->get();

        }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
            $forest_state_id = $authUser->forest_state_id;
            $forest_division_id = $authUser->forest_division_id;
            $forest_beats = ForestBeat::lwd()->where(['forest_division_id'=>$forest_division_id])->get();
        }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
            $forest_state_id = $authUser->forest_state_id;
            $forest_beats = ForestBeat::lwd()->where(['forest_state_id'=>$forest_state_id])->get();
        }else{
            $forest_beats = ForestBeat::lwd()->where(['forest_division_id'=>$id])->get();
        }

        return $forest_beats;
    }

    public function getForestBeatSelf($id = 0)
    {
        if ($id) {
            return $forest_beat = ForestBeat::where('id',$id)->get();
        }
        return [];
    }




    
    public function getProduct($id = 0)
    {
        if ($id > 0) {
            return $products = Product::with('size','color','age','unit','category')->where('id',$id)->get();
        }
        return [];
    }
    
    public function getProducts($id = 0)
    {
        if ($id > 0) {
            return $products = Product::with('size','color','age','unit','category')->where('category_id',$id)->get();
        }
        return [];
    }
    
    public function getUnit($id = 0)
    {
        if ($id) {
            return $products = Unit::where('id',$id)->get();
        }
        return [];
    }
    
    
    
    public function getForestBeatForestDivision($id = 0)
    {
        if ($id) {
            $forest_beat = ForestBeat::find($id);
            $forest_division_id = $forest_beat->forest_division_id;

            return $forest_division = ForestDivision::find($forest_division_id);
        }

        return \response()->json(null);
    }
    
    public function getNursery($id = 0)
    {
        if ($id) {
            $nursery = Nursery::find($id);
            $division_id = $nursery->division_id;

            return $division = Division::find($division_id);
        }

        return \response()->json(null);
    }
    
    public function getNursery1($id = 0)
    {
        if ($id) {
            return $nurseries = Nursery::where(['upazila_id'=>$id])->get();
        }

        return [];
    }
    
    public function getUsers()
    {
        $users = User::get();
        return \response()->json($users);
    }
    
    public function rangeOffice($id)
    {
        $admin = Admin::find($id);
        $upazila = Upazila::where('district_id',$admin->district_id)->get();

        return \response()->json($upazila);
    }
}