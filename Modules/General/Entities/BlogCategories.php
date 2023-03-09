<?php

namespace Modules\General\Entities;

use Illuminate\Database\Eloquent\Model;

class BlogCategories extends Model
{
    protected $fillable = ['title','slug','is_active'];
    
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
