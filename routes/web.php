<?php

use App\Http\Controllers\ImagesController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

Route::get('/a', function ()
{
    return Str::slug(" '+-*-\\/5 This is a title of any / this * . + .. @\" \\ \" ' done!  54 3{{#~", "_");
});

Route::get('/products/images/{name}', [ImagesController::class, 'getImage']);

Route::get('/{any?}', function () {
    return view('welcome');
})->where('any', '.*');