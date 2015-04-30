<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ParamValue extends Model {
	public function param() {
		//значени принадлежит параметру
		return $this->belongsTo('App\Param');
	}
}
