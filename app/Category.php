<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model {
  use SoftDeletes;
  protected $table = 'categories';

  public function params() {
    return $this->hasMany('App\Param');
  }

  public function products() {
    return $this->hasMany('App\Product');
  }

  public function limitedProducts() {
    return $this->hasMany('App\Product')->take(6);
  }

  public function randomProducts() {
    return $this->hasMany('App\Product')
      ->orderByRaw('RAND()')
      ->take(3);
  }
}
