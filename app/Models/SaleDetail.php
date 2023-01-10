<?php

namespace App\Models;

use App\Scopes\ActiveScope;
use Illuminate\Support\Str;

use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleDetail extends Model
{
    use HasFactory, LogsActivity;
    public $timestamps = true;
    protected $guarded = ['id'];

    protected static $recordEvents = ['updated','deleted'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('sale_details')
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
    
    public function sale() {
        return $this->belongsTo(Sale::class,'sale_id');
    }

    public function budget() {
        return $this->belongsTo(Budget::class,'budget_id');
    }
    
    public function category() {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function unit() {
        return $this->belongsTo(Unit::class,'unit_id');
    }

    public function age() {
        return $this->belongsTo(Age::class,'age_id');
    }

    public function color() {
        return $this->belongsTo(Color::class,'color_id');
    }

    public function size() {
        return $this->belongsTo(Size::class,'size_id');
    }

    public function product() {
        return $this->belongsTo(Product::class,'product_id');
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

    public function priceType() {
        return $this->belongsTo(PriceType::class,'price_type_id');
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

}