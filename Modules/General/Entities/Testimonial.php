<?php

namespace Modules\General\Entities;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['image', 'client_name', 'designation', 'rating', 'detail', 'sort', 'status'];
}
