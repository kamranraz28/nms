<?php

namespace App\Models;

use App\Scopes\ActiveScope;
use Illuminate\Support\Str;

use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserType extends Model
{
    use HasFactory, LogsActivity;
    public $timestamps = true;
    protected $guarded = ['id'];

    protected static $recordEvents = ['updated','deleted'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('user_types')
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


    public function scopeUtwd($query) {

        $authUser = Auth::guard('admin')->user()->load(['userType']);
        
        if($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
            return $query->where('id',$authUser->id);
        } elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
            return $query->where('id','>=',$authUser->id);
        } elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
            return $query->where('id','>=',$authUser->id);
        } elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
            return $query->where('id','>=',$authUser->id); // New
        } elseif($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[2]){
            return $query->where('id','>=',$authUser->id);
        } else{
            return $query;
        }
    }
}
