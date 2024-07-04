<?php

namespace App\Models;

use App\Scopes\ActiveScope;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Validated;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;

use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];


    const MASTER = 1;
    const CCF = 2;
    const DCCF = 3;
    const CF = 4;
    const DCF = 5;
    const ACCF = 6;
    const DFO = 7;
    const ACF = 8;
    const RO = 9;
    const BO = 10;


    const DEFAULT_ROLE = [
        '1' => 'Master Type',
        '2' => 'System Admin Type',
        '3' => 'Circle Wise',
        '4' => 'Forest Division Wise',
        '5' => 'Range/SFNTC Wise',
        '6' => 'Beat/SFPC Wise',
    ];

    const DEFAULT_ROLE_LIST = [0,1,2,3,4,5,6];



    protected $guarded = ['id'];

    protected static $recordEvents = ['updated','deleted'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('admins')
        ->logAll()
        //->logOnly(['name'])
        ->setDescriptionForEvent(fn(string $eventName) => $eventName)
        //->dontLogIfAttributesChangedOnly(['sort'])
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //'status' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

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

    public function role() {
        return $this->belongsTo(Role::class,'role_id');
    }

    public function userType() {
        return $this->belongsTo(UserType::class,'user_type_id');
    }


    public function scopeOffice($query, $UT_ID) {
        return $query->where('user_type_id',$UT_ID);
    }

    // Location user type wise data
    public function scopeLutwd($query) {

        $authUser = Auth::guard('admin')->user()->load(['userType']);
        
        if($authUser->userType->default_role == self::DEFAULT_ROLE_LIST[6]){
            return $query->where(['forest_beat_id' => $authUser->forest_beat_id, 'id' => $authUser->id]);
        } elseif($authUser->userType->default_role == self::DEFAULT_ROLE_LIST[5]){
            return $query->where(['forest_range_id' => $authUser->forest_range_id])->where('user_type_id','>=',$authUser->user_type_id);
        } elseif($authUser->userType->default_role == self::DEFAULT_ROLE_LIST[4]){
            return $query->where(['forest_division_id' => $authUser->forest_division_id])->where('user_type_id','>=',$authUser->user_type_id);
        } elseif($authUser->userType->default_role == self::DEFAULT_ROLE_LIST[3]){
            return $query->where(['forest_state_id' => $authUser->forest_state_id])->where('user_type_id','>=',$authUser->user_type_id); //New
        } elseif($authUser->userType->default_role == self::DEFAULT_ROLE_LIST[2]){
            return $query->where('user_type_id','>=',$authUser->user_type_id);
            //return $query->where('user_type_id','!=',self::DEFAULT_ROLE_LIST[1]);
        } else{
            return $query;
        }
    }

    // Location user type wise beat office data
    public function scopeLutwbod($query) {

        $authUser = Auth::guard('admin')->user()->load(['userType']);
        
        if($authUser->userType->default_role == self::DEFAULT_ROLE_LIST[6]){
            return $query->where(['id' => $authUser->id]);
        } elseif($authUser->userType->default_role == self::DEFAULT_ROLE_LIST[5]){
            return $query->where(['forest_range_id' => $authUser->forest_range_id, 'user_type_id' => self::BO]);
        } elseif($authUser->userType->default_role == self::DEFAULT_ROLE_LIST[4]){
            return $query->where(['forest_division_id' => $authUser->forest_division_id, 'user_type_id' => self::BO]);
        } elseif($authUser->userType->default_role == self::DEFAULT_ROLE_LIST[3]){
            return $query->where(['forest_state_id' => $authUser->forest_state_id, 'user_type_id' => self::BO]); // New
        } elseif($authUser->userType->default_role == self::DEFAULT_ROLE_LIST[2]){
            return $query->where(['user_type_id' => self::BO]);
        } else{
            return $query->where(['user_type_id' => self::BO]);
        }
    }

    // User type wise validation
    public static function Utwv($request) {

        $authUser = Auth::guard('admin')->user()->load(['userType']);
        
        if($authUser->userType->default_role == self::DEFAULT_ROLE_LIST[6]){
            return $validator = Validator::make($request->all(), [
                'forest_state_id' => 'required',
                'forest_division_id' => 'required',
                'forest_range_id' => 'required',
                'forest_beat_id' => 'required',
            ]);
        } elseif($authUser->userType->default_role == self::DEFAULT_ROLE_LIST[5]){
            return $validator = Validator::make($request->all(), [
                'forest_state_id' => 'required',
                'forest_division_id' => 'required',
                'forest_range_id' => 'required',
                //'forest_beat_id' => 'required',
            ]);
        } elseif($authUser->userType->default_role == self::DEFAULT_ROLE_LIST[4]){
            return $validator = Validator::make($request->all(), [
                'forest_state_id' => 'required',
                'forest_division_id' => 'required',
                //'forest_range_id' => 'required',
                //'forest_beat_id' => 'required',
            ]);
        } elseif($authUser->userType->default_role == self::DEFAULT_ROLE_LIST[3]){
            return $validator = Validator::make($request->all(), [
                'forest_state_id' => 'required',
                //'forest_district_id' => 'required',
                //'forest_range_id' => 'required',
                //'forest_beat_id' => 'required',
            ]);
        } else{
            return $validator = Validator::make($request->all(), [
                'forest_state_id' => 'required',
                //'forest_division_id' => 'required',
                //'forest_range_id' => 'required',
                //'forest_beat_id' => 'required',
            ]);
        }
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }  
}
