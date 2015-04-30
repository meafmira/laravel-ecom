<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Param extends Model {
	public function values() {
		//у каждого параметра может быть множество значений
		return $this->hasMany('App\ParamValue');
	}
}
