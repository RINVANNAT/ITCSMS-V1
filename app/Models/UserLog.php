<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class UserLog extends Model
{
    public $table = "userLogs";


    public $fillable = [
        "model",
        "action",
        "data",
        "create_uid",
        "ip_address",
        "user_agent",
        "developer"
    ];

    public static function log($data = [])
    {
        if (is_object($data))
            $data = (array) $data;

        if (is_string($data))
            $data = ['action' => $data];

        $activity = new UserLog();


        $activity->create_uid = Auth::id();

        $activity->model   = isset($data['model'])   ? $data['model']   : null;
        $activity->action       = isset($data['action'])      ? $data['action']      : null;
        $activity->data      = isset($data['data'])     ? $data['data']     : null;


        //set developer flag
        if(isset($data['developer'])) $activity->is_developer  = $data['developer'];

        $activity->ip_address = Request::getClientIp();
        $activity->user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'No UserAgent';
        $activity->save();

        return true;
    }


    public function user(){
        return $this->belongsTo('App\User','create_uid');
    }


    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "model" => "string",
        "action" => "string",
        "data" => "string"
    ];

    public static $rules = [
        "model" => "Required",
        "action" => "Required",
        "data" => "Required"
    ];
}
