<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::get('/v1/test', function (Request $request) {
    return "jalan";
});

Route::group(['middleware' => ['cors', 'json.response'], 'prefix' => 'v1'], function () {
    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/register', [App\Http\Controllers\Api\V1\AuthController::class, 'register']);
    Route::post('/login', [App\Http\Controllers\Api\V1\AuthController::class, 'login']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::group(['middleware' => 'admin','prefix' => 'admin'], function () {
            Route::post('/logout', [App\Http\Controllers\Api\V1\AuthController::class, 'logout']);
            Route::group(['prefix' => 'categories'], function () {
                Route::post('/', [App\Http\Controllers\Api\V1\Admin\NewsController::class, 'create']);
                // Route::post('/delete/{id}', [App\Http\Controllers\Api\V1\NewsController::class,'delete']);
            });
            Route::group(['prefix' => 'categories'], function () {
                Route::post('/', [App\Http\Controllers\Api\V1\Admin\CategoryController::class, 'createCategory']);
                Route::get('/', [App\Http\Controllers\Api\V1\Admin\CategoryController::class, 'getAllCategory']);
                Route::get('/{slug}', [App\Http\Controllers\Api\V1\Admin\CategoryController::class, 'getCategoryBySlug']);
                Route::patch('/{slug}', [App\Http\Controllers\Api\V1\Admin\CategoryController::class, 'updateCategoryBySlug']);
                Route::delete('/{slug}', [App\Http\Controllers\Api\V1\Admin\CategoryController::class, 'deleteCategoryBySlug']);
            });
            Route::group(['prefix' => 'news'], function () {
                Route::post('/', [App\Http\Controllers\Api\V1\Admin\NewsController::class, 'createNews']);
                Route::get('/', [App\Http\Controllers\Api\V1\Admin\NewsController::class, 'getAllNews']);
                Route::get('/{slug}', [App\Http\Controllers\Api\V1\Admin\NewsController::class, 'getNewsBySlug']);
                Route::patch('/{slug}', [App\Http\Controllers\Api\V1\Admin\NewsController::class, 'updateNewsBySlug']);
                Route::delete('/{slug}', [App\Http\Controllers\Api\V1\Admin\NewsController::class, 'deleteNewsBySlug']);
            });
        });

        Route::group(['prefix' => 'news'], function () {
            Route::get('/', [App\Http\Controllers\Api\V1\User\NewsController::class, 'getAllNews']);
            Route::get('/{slug}', [App\Http\Controllers\Api\V1\User\NewsController::class, 'getNewsBySlug']);
            Route::post('/comment', [App\Http\Controllers\Api\V1\User\NewsController::class, 'createCommentByUser']);
        });
    });
});
