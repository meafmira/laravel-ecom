<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Category;
use App\Param;
#use Intervention\Image;

use Illuminate\Http\Request;

class CategoryController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	//получаение всех категорий
	public function index()
	{
		return Category::get();
	}

	//получение 2х случайных категорий, для вывода на главной странице
	public function random() {
		return Category::orderByRaw('RAND()') //случайный порядок
			->take(2) //2 категории
			->with('limitedProducts') //по 6 товаров в каждой
			->get();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */

	//сохранение категории
	public function store(Request $request)
	{
		//заголовок категории
		$categoryTitle = $request->input('title');
		//параметры категории
		$params = $request->input('params');
		$categoryParams = [];
		foreach ($params as $param) {
			//создаем параметр для каждого названия
			$categoryParam = new Param();
			$categoryParam->name = $param['name'];
			$categoryParams[] = $categoryParam;
		}
		//создаем новую категорию
		$category = new Category();
		$category->title = $categoryTitle;
		//сохраняем категорию
		$category->save();
		//добавляем список параметров к категории
		$category->params()->saveMany($categoryParams);
		return $category;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	//получение одной категории
	public function show(Category $category)
	{
		return $category //категория
			->load('products') //загружаем товары
			->load('params');  //и параметры категории
	}


	//получение всех товаров категрии
	public function products(Request $request, $category) {
		$random = $request->input('random');
		if (isset($random)) {
			return Category::find($category)->randomProducts;
		}
		else {
			return Category::find($category)->products;
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	//изменение категории
	public function update(Request $request, Category $category)
	{
		$categoryParams = []; //называние параметров
		$params = $request->input('params');
		$paramIds = [];

		//изменение параметров категории
		foreach ($params as $param) {
			//если параметр еще не существовал
			if (!isset($param['id'])) {
				//создаем его
				$categoryParam = new Param();
				$categoryParam->name = $param['name'];
				//определяем его принадлежность к категории
				$categoryParam->category_id = $category->id;
				//сохраняем параметр
				$categoryParam->save();
				$paramIds[] = $categoryParam->id;
				$categoryParams[] = $categoryParam;
			}
			//если параметр уже существовал
			else {
				//находим его
				$categoryParam = Param::find($param['id']);
				//меняем его название
				$categoryParam->name = $param['name'];
				//сохраняем его
				$categoryParam->save();
				$paramIds[] = $categoryParam->id;
			}
		}
		$category->title = $request->input('title');

		//если есть удаленные параметры - удаляем их
		Param::where('category_id', $category->id)
			->whereNotIn('id', $paramIds)
			->delete();

		//сохраняем категорию
		$category->save();
		return $category;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	//удаление категории
	public function destroy(Category $category)
	{
		$category->delete();
		return $category;
	}

}
