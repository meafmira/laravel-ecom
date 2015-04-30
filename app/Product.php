<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
	protected $table = 'products';
	protected $appends = ['thumb'];

	public function category() {
		//продукт принадлежит определенной категории
		return $this->belongsTo('App\Category');
	}

	public function getThumbAttribute() {
		//получение миниатюры товара (первое связанное изображение)
		return $this->morphToMany('App\Image', 'imageable')
			->first();
	}

	public function params() {
		//товару принадлежит несколько значений параметров
		return $this->belongsToMany('App\ParamValue');
	}

	public function images() {
		//у товара может быть несколько изображений
		return $this->morphToMany('App\Image', 'imageable');
	}
}
