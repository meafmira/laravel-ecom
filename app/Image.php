<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model {
	public function getPathAttribute($value) {
		//получение полного пути к изображению
		return url().'/'.$value;
	}
}
