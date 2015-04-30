<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {
	protected $appends = [ 'shortText', 'thumb' ];

	public function category() {
		//у поста есть категория
		return $this->hasOne('Category');
	}

	public function getShortTextAttribute() {
		//получение короткого содержания поста (лида)
		$strippedText = strip_tags($this->text); //получаем текст
		$cuttedText = substr($strippedText, 0, 200)."..."; //удаляем тэги и сокращаем до 200 символов
		return $cuttedText;
	}

	public function getThumbAttribute() {
		//получение миниатюры поста - первого соответствующего изображения
		return $this->morphToMany('App\Image', 'imageable')
			->where('type', 'thumb')
			->first();
	}
}
