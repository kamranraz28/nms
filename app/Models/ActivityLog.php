<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Activity
{
    
    use HasFactory;
    public $table = 'activity_log';
    public $timestamps = true;
    //public $guarded = ['id'];

    // protected $casts = [
    //     'properties' => 'collection',
    // ];

    public function scopeLogName($query, $log_name=null) {
        return $query->where('log_name',$log_name);
    }


    public function scopeLogNames($query, ...$logNames)
    {
        if (is_array($logNames[0])) {
            $logNames = $logNames[0];
        }

        return $query->whereIn('log_name', $logNames);
    }

    public function admin() {
        return $this->belongsTo(Admin::class,'causer_id','id')->select('id','name','email');
    }
}
