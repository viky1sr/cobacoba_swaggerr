<?php

use App\Http\Controllers\SwaggerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::controller(SwaggerController::class)->group(function (){
    Route::get('documentation','swagger');
    Route::get('swagger-yml','swaggeryml');
});
