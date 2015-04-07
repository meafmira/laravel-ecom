<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Param extends Model {
	public function values() {
		return $this->hasMany('App\ParamValue');
	}
}
