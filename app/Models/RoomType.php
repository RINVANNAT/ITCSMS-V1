<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class RoomType extends Model
{
    
	public $table = "roomTypes";
    

	public $fillable = [
	    "name",
        "create_uid",
        "write_uid"
	];

    public function rooms(){
        return $this->hasMany('App\Models\Room');
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "name" => "string"
    ];

	public static $rules = [
	    "name" => "required"
	];

}
