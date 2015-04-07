<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
  protected $table = 'categories';

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
