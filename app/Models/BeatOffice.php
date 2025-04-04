<?php

namespace App\Models;

use App\Scopes\ActiveScope;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BeatOffice extends Model
{
    use HasFactory, LogsActivity;
    public $table = 'admins';
    public $timestamps = true;
    protected $guarded = ['id'];

    protected static $recordEvents = ['updated','deleted'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('beat_offices')
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

    public function role() {
        return $this->belongsTo(Role::class,'role_id');
    }

    public function userType() {
        return $this->belongsTo(UserType::class,'user_type_id');
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

    // public function rangeOffic()
    // {
    //     return $this->belongsTo(BeatOffice::class,'parent_id');
    // }


    public function scopeLwd($query) {
        if (auth()->guard('admin')->check()) {
            if(!$authUser) $authUser = Auth::guard('api')->user();
            
            if ($authUser) {
                // Check if the user has the 'userType' relationship
                $authUser->load(['userType']);
                
                if ($authUser->userType) {
                    if ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
                        return $query->where('forest_beat_id', $authUser->forest_beat_id);
                    } elseif ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
                        return $query->where('forest_range_id', $authUser->forest_range_id);
                    } elseif ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
                        return $query->where('forest_division_id', $authUser->forest_division_id);
                    } elseif ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
                        return $query->where('forest_state_id', $authUser->forest_state_id); // New
                    } elseif ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[2]){
                        return $query;
                    } else {
                        return $query;
                    }
                } else {
                    // Handle the case where 'userType' is not loaded
                }
            } else {
                // Handle the case where 'auth()->guard('admin')->user()' is null
            }
        } else {
            // Handle the case where the user is not authenticated
        }
    }

    // Location user type wise beat office data
    public function scopeLutwbod($query) {

        $authUser = Auth::guard('admin')->user()->load(['userType']);
        
        if($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
            return $query->where('forest_beat_id',$authUser->forest_beat_id);
        }elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
            return $query->where(['forest_range_id' => $authUser->forest_range_id, 'user_type_id' => Admin::BO]);
        } elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
            return $query->where(['forest_division_id' => $authUser->forest_division_id, 'user_type_id' => Admin::BO]);
        } elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
            return $query->where(['forest_state_id' => $authUser->forest_state_id, 'user_type_id' => Admin::BO]); // New
        } elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[2]){
            return $query->where(['user_type_id' => Admin::BO]);
        } else{
            return $query->where(['user_type_id' => Admin::BO]);
        }
    }
}
