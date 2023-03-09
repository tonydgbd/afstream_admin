<?php

namespace Modules\Audio\Entities;

use Illuminate\Database\Eloquent\Model;

class AudioGenre extends Model
{
    protected $fillable = ['image','genre_name','genre_slug','is_featured','is_trending','is_recommended','status'];
}
