<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArtistPaymentGateway extends Model
{
    protected $table = 'artist_payment_gateways';
    public $guarded = [''];
}
