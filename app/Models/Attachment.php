<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;


class Attachment extends Model
{
	public $table = "attachments";
    

	public $fillable = [
	    "name",
		"location",
		"active",
		"outcome_id",
		"create_uid",
		"write_uid"
	];

    public function outcome(){
        return $this->belongsTo('App\Models\Outcome');
    }


}
