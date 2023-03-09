<?php

namespace Modules\General\Entities;

use Illuminate\Database\Eloquent\Model;

class Blogs extends Model
{
    protected $fillable = ['user_id','keywords','blog_cat_id','title','slug','unique_id','detail','image','video','is_active'];
    
    function user(){
        return $this->belongsTo('App\user','user_id');
    }
    
    function category(){
        return $this->belongsTo('App\Modules\Blog\Models\BlogCategory','blog_cat_id');
    } 
}

