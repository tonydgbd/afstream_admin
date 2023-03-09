<?php

namespace Modules\Location\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Location\Entities\AllState;

class AllCountry extends Model
{
    protected $fillable = ['id','iso','name','nicename','iso3','numcode','phoncode'];

    function states(){
        return $this->hasMany(AllState::class, 'country_id');
    }
}
