<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
	protected $table = 'products';
	protected $appends = ['thumb'];

	public function category() {
		return $this->belongsTo('App\Category');
	}

	public function getThumbAttribute() {
		return $this->morphToMany('App\Image', 'imageable')
			->first();
	}

	public function params() {
		return $this->belongsToMany('App\ParamValue');
	}

	public function images() {
		return $this->morphToMany('App\Image', 'imageable');
	}
}
