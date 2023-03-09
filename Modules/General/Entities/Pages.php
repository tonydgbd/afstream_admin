<?php

namespace Modules\General\Entities;

use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    protected $fillable = ['title','slug','detail','is_active'];
}
