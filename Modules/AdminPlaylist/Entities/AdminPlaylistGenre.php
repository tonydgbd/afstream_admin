<?php

namespace Modules\AdminPlaylist\Entities;

use Illuminate\Database\Eloquent\Model;

class AdminPlaylistGenre extends Model
{
    protected $fillable = ['genre_name','genre_slug','status'];
}
