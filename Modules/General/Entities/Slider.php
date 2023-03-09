<?php

namespace Modules\General\Entities;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = ['title','link','image','position','status'];
}
