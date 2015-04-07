<?php
use App\User;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::group(['prefix' => 'api/v1'], function()
{
  Route::post('/signup', function () {
    $credentials = [ 'email' => Input::get('email')
		, 'password' => bcrypt(Input::get('password'))
		, 'name' => Input::get('name') ? Input::get('name') : ''];

    try {
      $user = User::create($credentials);
    } catch (Exception $e) {
      return Response::json(['error' => $e], 401);
    }

    $token = JWTAuth::fromUser($user);

    return Response::json(compact('token'));
  });

	Route::post('/signin', function () {
   	$credentials = Input::only('email', 'password');

   	if ( ! $token = JWTAuth::attempt($credentials)) {
       return Response::json(false, 401);
   	}

   	return Response::json(compact('token'));
	});

	Route::get('/restricted', [ 'middleware' => 'jwt.auth', function () {
		return Response::json('text');
	}]);

	Route::get('categories/{category}/products', 'CategoryController@products');
	Route::get('categories/random', 'CategoryController@random');
	Route::resource('categories', 'CategoryController');
	Route::get('products/latest', 'ProductController@latest');
	Route::resource('products', 'ProductController');
	Route::get('/test', function () {
		$image = Image::make('images/image.jpg')
			->resize(null, 500, function ($constraint) {
				$constraint->aspectRatio();
			});
		return $image->response('jpg');
	});
	Route::resource('pages', 'PageController');
	Route::resource('posts', 'PostController');
	Route::resource('post-categories', 'PostCategoryController');
});
