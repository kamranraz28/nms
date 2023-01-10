<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ContentCategory extends Model
{
    use HasFactory, LogsActivity;
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $parentColumn = 'parent_id';

    
    protected static $recordEvents = ['created','updated','deleted'];

    public function getStatusAttribute($value)
    {
        return $status = (app()->getLocale() == 'en') ? Status::EN[$value] : Status::BN[$value];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('content_ategories')
        //->logAll()
        ->logOnly(['name'])
        ->setDescriptionForEvent(fn(string $eventName) => "content_ategories has been {$eventName}")
        //->dontLogIfAttributesChangedOnly(['sort'])
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }

    public function contentCategory()
    {
        return $this->belongsTo(ContentCategory::class,'parent_id')->select('id','parent_id','name','position','last');
    }

    public function content()
    {
        return $this->hasMany(Content::class)->select('id','content_category_id','name','slug');
    }

    public function children()
    {
        return $this->hasMany(ContentCategory::class, $this->parentColumn)->select('id','parent_id','name','position','last');
    }

    public function allChildren()
    {
        return $this->children()->with('allChildren','content');
    }
}
