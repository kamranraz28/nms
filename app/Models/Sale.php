<?php

namespace App\Models;

use App\Scopes\ActiveScope;
use Illuminate\Support\Str;

use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory, LogsActivity;
    public $timestamps = true;
    protected $guarded = ['id'];

    protected static $recordEvents = ['updated','deleted'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('sales')
        ->logAll()
        //->logOnly(['name'])
        ->setDescriptionForEvent(fn(string $eventName) => $eventName)
        //->dontLogIfAttributesChangedOnly(['sort'])
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }

    public function getStatusAttribute($value)
    {
        return $status = (app()->getLocale() == 'en') ? Status::EN[$value] : Status::BN[$value];
    }

    // public function scopeActive($query)
    // {
    //     return $query->where('status',1);
    // }


    //Admin::withoutGlobalScopes([FirstScope::class, SecondScope::class])->get();

    protected static function booted()
    {
        static::addGlobalScope(new ActiveScope);
    }

    public function saleDetail() {
        return $this->hasMany(SaleDetail::class,'sale_id', 'id');
    }

    public function budget() {
        return $this->belongsTo(Budget::class,'budget_id');
    }

    public function nursery() {
        return $this->belongsTo(Nursery::class,'nursery_id');
    }

    public function approvedBy() {
        return $this->belongsTo(Admin::class,'approved_by');
    }

    public function state() {
        return $this->belongsTo(State::class,'state_id');
    }

    public function division() {
        return $this->belongsTo(Division::class,'division_id');
    }

    public function district() {
        return $this->belongsTo(District::class,'district_id');
    }

    public function upazila() {
        return $this->belongsTo(Upazila::class,'upazila_id');
    }

    public function stockType() {
        return $this->belongsTo(StockType::class,'stock_type_id');
    }

    public function forestState() {
        return $this->belongsTo(ForestState::class,'forest_state_id');
    }

    public function forestDivision() {
        return $this->belongsTo(ForestDivision::class,'forest_division_id');
    }

    public function forestRange() {
        return $this->belongsTo(ForestRange::class,'forest_range_id');
    }

    public function forestBeat() {
        return $this->belongsTo(ForestBeat::class,'forest_beat_id');
    }



    public function scopeLwd($query) {

        $authUser = Auth::guard('admin')->user()->load(['userType']);
        
        if($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
            return $query->where('forest_beat_id',$authUser->forest_beat_id);
        } elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
            return $query->where('forest_range_id',$authUser->forest_range_id);
        } elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
            return $query->where('forest_division_id',$authUser->forest_division_id);
        } elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
            return $query->where('forest_state_id',$authUser->forest_state_id); // New
        } elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[2]){
            return $query;
        } else{
            return $query;
        }
    }
}
