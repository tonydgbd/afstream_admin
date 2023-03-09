<?php

namespace Modules\Location\Entities;

use Illuminate\Database\Eloquent\Model;

class AllCity extends Model
{
    protected $fillable = ['name','state_id','updated_at'];
    public $timestamps = false;

    function state(){
        return $this->belongsTo('\Modules\Location\Entities\allState');
    }
}
