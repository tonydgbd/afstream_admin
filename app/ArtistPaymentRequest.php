<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArtistPaymentRequest extends Model 
{
    protected $table = 'artist_payment_request';
    public $guarded = [''];
}
