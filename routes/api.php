<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\API\ApiController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/form', function () {
    return view('api.login');
});

Route::post('/certlist', [ApiController::class, 'login']);
Route::post('/verify', [ApiController::class, 'verifySingle']);
Route::post('/register', [ApiController::class, 'register']);
Route::post('/logout', [ApiController::class, 'logout']);
