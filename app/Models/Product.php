<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'condition_id',
        'name',
        'imgPath',
        'brand',
        'price',
        'description',
        'sold',
        'buyer_id',
    ];

    public function categories(){
        return $this->belongsToMany(Category::class,'sorts');
    }
    public function getCategories(){
        $categories = Product::find($this->id)->categories()->get();
        return $categories;
    }
    public function condition(){
        return $this->belongsTo(Condition::class);
    }
    public function comments(){
        return $this->hasMany(Comment::class);
    }
    public function likeds(){
        return $this->belongsToMany(User::class,'likes');
    }
    public function likedOrNot(){
        if(Auth::check()){
        $user = Auth::user();
        return $this->likeds()->where('user_id',$user->id)->exists();
        }else{
            return false;
        }
        
    }
    public function countLike(){
        return $this->likeds()->count();
    }
    public function countComment(){
        return $this->comments()->count();
    }
}
