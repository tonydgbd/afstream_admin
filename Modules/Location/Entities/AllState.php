<?php

namespace Modules\Location\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Location\Entities\AllCity;

class AllState extends Model
{
    protected $fillable = ['name', 'country_id'];
    public $timestamps = false;

    function cities(){
        return $this->hasMany(AllCity::class, 'state_id');
    }

    function country(){
        return $this->belongsTo('\Modules\Location\Entities\AllCountry');
    }
}
