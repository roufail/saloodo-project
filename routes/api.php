<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['prefix' => 'v1'],function(){

    Route::group(['prefix' => 'admin','namespace' => 'admin'], function () {

        Route::post('login','AuthController@login');

        Route::group(['middleware'=>'auth:admin-api'],function(){
            Route::get('user','AuthController@user');
            Route::get('logout','AuthController@logout');
            Route::get('orders','ProductController@orders');
            Route::get('order/{orderid}','ProductController@get_order');
            Route::get('orders/{userid}','ProductController@get_user_orders');
            Route::resource('products', 'ProductController');
        });
    });


    Route::post('login','AuthController@login');
    Route::post('register','AuthController@register');
    Route::get('products','ProductsController@products');
    Route::get('products/{product}/show','ProductsController@show');

    Route::group(['middleware'=>'auth:customer-api'],function(){
        Route::get('logout','AuthController@logout');
        Route::get('products/{product}/addtocart','ProductsController@addtocart');
        Route::get('cart/order','ProductsController@order');
        Route::get('cart','ProductsController@cart');
        Route::post('change_item/{item}','ProductsController@change_cart_item_count');
        Route::get('delete_item/{item}','ProductsController@delete_cart_item');
        Route::get('order/{order}','ProductsController@get_order');
        Route::get('myorders','ProductsController@my_orders');
    });

});
