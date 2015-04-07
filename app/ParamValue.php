<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ParamValue extends Model {
	public function param() {
		return $this->belongsTo('App\Param');
	}
}
