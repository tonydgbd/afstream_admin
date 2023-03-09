<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $guarded = [''];

    // public function defaultCurrency(){
    //     return $this->hasOne('Modules\Setting\Entities\Currency','id','default_currency_id');
    // }
}
