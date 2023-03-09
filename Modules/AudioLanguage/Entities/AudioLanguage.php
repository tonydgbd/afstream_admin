<?php

namespace Modules\AudioLanguage\Entities;

use Illuminate\Database\Eloquent\Model;

class AudioLanguage extends Model
{
    protected $fillable = ['language_name','language_code','image','is_default','status'];
}
