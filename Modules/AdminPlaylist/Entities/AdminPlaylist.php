<?php

namespace Modules\AdminPlaylist\Entities;

use Illuminate\Database\Eloquent\Model;

class AdminPlaylist extends Model
{

    protected $fillable = ['playlist_title','playlist_title_slug','audio_language','audio_id','artist_id','album_id','status'];
}
