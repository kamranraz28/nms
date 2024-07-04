<?php

namespace App\Models;

use App\Scopes\ActiveScope;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nursery extends Model
{
    use HasFactory, LogsActivity;
    public $timestamps = true;
    protected $guarded = ['id'];

    protected static $recordEvents = ['updated','deleted'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('nurseries')
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

    public function admin() {
        return $this->belongsTo(Admin::class,'admin_id');
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


      public function scopeLwd($query) {

        $authUser = Auth::guard('admin')->user();
        if(!$authUser) $authUser = Auth::guard('api')->user();
        $authUser = $authUser->load(['userType']);
        
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
