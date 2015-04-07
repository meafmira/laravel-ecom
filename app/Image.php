<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model {
	public function getPathAttribute($value) {
		return url().'/'.$value;
	}
}
