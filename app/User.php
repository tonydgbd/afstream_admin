<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable; 

    
    protected $guarded = [];

   
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /* USER VIEW */
    public static function userDetail($id){ 
        return User::find($id);
    }

    function country(){
        $this->belongsTo('\Modules\Location\Entities\AllCountry','country_id');
    }

    function state(){
        $this->belongsTo('\Modules\Location\Entities\AllState','state_id');
    }

    function city(){
        $this->belongsTo('\Moduels\Location\Entities\AllCity', 'city_id');
    }
}
