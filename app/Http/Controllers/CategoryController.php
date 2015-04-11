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
	public function index()
	{
		#return Category::get();
		#$image = Image::make('public/images/image.jpg')->resize(100, 100);
		#return $image->response('jpg');
		return Category::get();
	}

	public function random() {
		return Category::orderByRaw('RAND()')
			->take(2)
			->with('limitedProducts')
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
	public function store(Request $request)
	{
		$categoryTitle = $request->input('title');
		$params = $request->input('params');
		$categoryParams = [];
		foreach ($params as $param) {
			$categoryParam = new Param();
			$categoryParam->name = $param['name'];
			$categoryParams[] = $categoryParam;
		}
		$category = new Category();
		$category->title = $categoryTitle;
		$category->save();
		$category->params()->saveMany($categoryParams);
		return $category;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Category $category)
	{
		return $category->load('products')->load('params');
	}

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
	public function update(Request $request, Category $category)
	{
		$categoryParams = [];
		$params = $request->input('params');
		$paramIds = [];
		foreach ($params as $param) {
			if (!isset($param['id'])) {
				$categoryParam = new Param();
				$categoryParam->name = $param['name'];
				$categoryParam->category_id = $category->id;
				$categoryParam->save();
				$paramIds[] = $categoryParam->id;
				$categoryParams[] = $categoryParam;
			}
			else {
				$categoryParam = Param::find($param['id']);
				$categoryParam->name = $param['name'];
				$categoryParam->save();
				$paramIds[] = $categoryParam->id;
			}
		}
		$category->title = $request->input('title');

		Param::where('category_id', $category->id)
			->whereNotIn('id', $paramIds)
			->delete();

		$category->save();
		return $category;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Category $category)
	{
		$category->delete();
		return $category;
	}

}
