<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecretRoomScore extends Model
{
    public $table = "secret_room_score";


    public $guarded = [
        "id",
    ];

    public function creator()
    {
        return $this->belongsTo('App\User', 'create_uid');
    }

    public function lastModifier()
    {
        return $this->belongsTo('App\User', 'write_uid');
    }
}
