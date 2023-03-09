<?php

namespace Modules\Location\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Location\Entities\AllCountry;

class Country extends Model
{
    protected $fillable = ['country'];

    function allCountries(){
        return $this->belongsTo(AllCountry::class , 'country', 'iso3');
    }
}
