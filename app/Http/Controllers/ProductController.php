<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Product;
use App\Param;
use App\ParamValue;

use Illuminate\Http\Request;

class ProductController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Product::all();
	}

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
	public function store(Request $request)
	{
		$product = new Product();
		$product->title = $request->input('title');
		$product->description = $request->input('description');
		$product->price = $request->input('price');
		$product->discount = $request->input('discount');
		$product->category_id = $request->input('category_id');
		$params = $request->input('params');
		$paramValueIds = [];
		foreach ($params as $param) {
			$paramValue = new ParamValue();
			$paramValue->param_id = $param['param_id'];
			$paramValue->value = $param['value'];
			$paramValue->save();
			$paramValueIds[] = $paramValue->id;
		}
		$product->save();
		$product->params()->sync($paramValueIds);
		$product->images()->sync([1]);
		return $product;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Product $product)
	{
		$result = $product
			->load('category')
			->load('images')
			->load('params.param');

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
	public function update(Request $request, Product $product)
	{
		$params = $request->params;
		$productParamValueIds = [];
		foreach ($params as $param) {
			$paramValue = ParamValue::where('param_id', $param['param_id'])->first();
			if ($paramValue) {
				if ($paramValue->value != $param['value']) {
					$newValue = new ParamValue();
					$newValue->value = $param['value'];
					$newValue->param_id = $param['param_id'];
					$newValue->save();
					$productParamValueIds[] = $newValue->id;
				}
				else {
					$productParamValueIds[] = $paramValue->id;
				}
			}
			else {
				$newValue = new ParamValue();
				$newValue->value = $param['value'];
				$newValue->param_id = $param['param_id'];
				$newValue->save();
				$productParamValueIds[] = $newValue->id;
			}
		}
		$images = $request->images;
		$productImagesIds = [];
		foreach ($images as $image) {
			$productImagesIds[] = $image['id'];
		}
		$product->title = $request->title;
		$product->price = $request->price;
		$product->discount = $request->discount;
		$product->description = $request->description;
		$product->params()->sync($productParamValueIds);
		$product->images()->sync($productImagesIds);
		$product->save();
		return $product;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
