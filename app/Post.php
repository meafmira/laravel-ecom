<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {
	protected $appends = [ 'shortText', 'thumb' ];

	public function category() {
		return $this->hasOne('Category');
	}

	public function getShortTextAttribute() {
		$strippedText = strip_tags($this->text);
		$cuttedText = substr($strippedText, 0, 200)."...";
		return $cuttedText;
	}

	public function getThumbAttribute() {
		return $this->morphToMany('App\Image', 'imageable')
			->where('type', 'thumb')
			->first();
	}
}
