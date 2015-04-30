<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Product;
use App\Param;
use App\ParamValue;
use App\Image;

use Illuminate\Http\Request;

class ProductController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	//получение всех товаров
	public function index()
	{
		return Product::all();
	}

	//последние 3 товара
	public function latest() {
		return Product::orderBy('created_at', 'desc')
			->take(3)
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

	//сохранение товара
	public function store(Request $request)
	{
		//создаем товар
		$product = new Product();
		//получаем необходимые параметры
		$product->title = $request->input('title');
		$product->description = $request->input('description');
		$product->price = $request->input('price');
		$product->discount = $request->input('discount');
		$product->category_id = $request->input('category_id');
		$params = $request->input('params');
		$paramValueIds = [];
		foreach ($params as $param) {
			//создаем значения параметров товара
			$paramValue = new ParamValue();
			$paramValue->param_id = $param['param_id'];
			$paramValue->value = $param['value'];
			$paramValue->save();
			$paramValueIds[] = $paramValue->id;
		}
		//сохраняем товар
		$product->save();
		//сохраняем связи товара со значениями параметров
		$product->params()->sync($paramValueIds);
		return $product;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	//получение одного товара
	public function show(Product $product)
	{
		$result = $product //товар
			->load('category') //загружаем категорию
			->load('images') //загружаем изображения
			->load('params.param'); //загружаем параметры

		return $result;
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

	//изменение товара
	public function update(Request $request, Product $product)
	{
		$params = $request->params;
		$productParamValueIds = []; //значения параметров
		foreach ($params as $param) {
			$paramValue = ParamValue::where('param_id', $param['param_id'])->first();
			//если значение уже существует
			if ($paramValue) {
				//если новое значение не соответствует старому
				if ($paramValue->value != $param['value']) {
					//создаем новое значени
					$newValue = new ParamValue();
					$newValue->value = $param['value'];
					$newValue->param_id = $param['param_id'];
					//сохраняем значение
					$newValue->save();
					$productParamValueIds[] = $newValue->id;
				}
				//если значения совпадают
				else {
					//добавляем в список значений параметров товара
					$productParamValueIds[] = $paramValue->id;
				}
			}
			//если значение не сущетвует
			else {
				//создаем новое значение
				$newValue = new ParamValue();
				$newValue->value = $param['value'];
				$newValue->param_id = $param['param_id'];
				//сохраняем значения
				$newValue->save();
				$productParamValueIds[] = $newValue->id;
			}
		}
		//получаем id изображений
		$images = $request->images;
		$productImagesIds = [];
		foreach ($images as $image) {
			$productImagesIds[] = $image['id'];
		}
		$product->title = $request->title;
		$product->price = $request->price;
		$product->discount = $request->discount;
		$product->description = $request->description;
		//связь между значениями параметров и товаром
		$product->params()->sync($productParamValueIds);
		//связь между товаром и изображениями
		$product->images()->sync($productImagesIds);
		//сохраняем товар
		$product->save();
		return $product;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	//удаление товара
	public function destroy(Product $product)
	{
		$product->delete();
		return $product;
	}

	//добавление изображений товару
	public function images(Request $request, $productId) {
		//получаем файл
		$file = $request->file('file');
		//даем ему новое уникальное имя
		$fileName = uniqid('product-image').'.'.$file->guessExtension();
		//перемещаем файл
		$file->move('images/', $fileName);
		//путь к файлу
		$path = 'images/'.$fileName;
		//создаем новое изображение
		$productImage = new Image();
		$productImage->path = $path;
		//сохраняем изображение
		$productImage->save();
		//получаем товар по его id
		$product = Product::find($productId);
		//присоединяем изображение к товару
		$product->images()->attach($productImage->id);
		return $product;
	}

}
