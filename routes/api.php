<?php

use App\Http\Controllers\API\UserApiContoller;
use App\Http\Controllers\API\TransactionsApiContoller;
use App\Http\Controllers\API\TataLetakApiContoller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::group(['as' => 'admin.'], function () {
    Route::apiResources([
        'file-entry' => TransactionsApiContoller::class,
        'tata-letak' => TataLetakApiContoller::class,
    ]);
    Route::post('file-entry/import', [TransactionsApiContoller::class, 'fileImport'])->name('file-entry.import');
});
Route::group(['middleware' => ['auth:api']] , function() {
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
