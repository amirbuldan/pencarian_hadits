<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/thanks', 'HomeController@thanks')->name('thanks');
Route::get('/home', 'AdminController@index')->name('home');

Route::group(['middleware' => ['isActive']], function () {
  // Route::get('/admin', 'AdminController@index')->name('admin'); //contoh jika tidak menggunakan prefix

	// Route::group(['middleware' => ['role:admin']], function () {
	  	Route::prefix('admin')->group(function () {
	      
	  	  Route::resource('haditss', 'HaditsController');
	  	  Route::get('search', 'HaditsController@search');
	  	  Route::post('result', 'HaditsController@result');
	  	  Route::resource('biografis', 'BiografiController');
	  	  // Route::get('haditss/imambukhari', 'HaditsController@imambukhari');


	  	  Route::resource('users', 'UserController');
	  	  Route::resource('roles', 'RoleController');
	  	  Route::resource('permissions', 'PermissionController');



	  	});
	// });
});




