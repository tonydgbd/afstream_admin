<?php

namespace Modules\Artist\Entities;

use Illuminate\Database\Eloquent\Model;

class ArtistGenre extends Model
{
    protected $fillable = ['genre_name','genre_slug'];
}
