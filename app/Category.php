<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model {
  use SoftDeletes;
  protected $table = 'categories';

  public function params() {
    //у категории может быть несколько параметров
    return $this->hasMany('App\Param');
  }

  public function products() {
    //у категории может быть несколько продуктов
    return $this->hasMany('App\Product');
  }

  public function limitedProducts() {
    //6 первых продуктов категории для вывода на главной странице
    return $this->hasMany('App\Product')->take(6);
  }

  public function randomProducts() {
    //случайные 3 продукта из категории
    return $this->hasMany('App\Product')
      ->orderByRaw('RAND()')
      ->take(3);
  }
}
