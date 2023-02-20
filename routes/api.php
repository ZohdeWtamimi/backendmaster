<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
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

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('/users', UserController::class);
    Route::post('/users/edit/{user}', [UserController::class, 'myedit']);
    // route::post('/upload', function(Request $request){
        //     // $data = $request['name'];
        //     $newImageName = time() . '_' . $request->name . '.' . 
        //     $request->image->extension();
        //     $request->image->move(public_path('images'), $newImageName);     
        //     // $newImageName = time() . '_' . '.' . 
        //     // $request->file('image')->extension();
        //     // $request->file('image')->move(public_path('images'), $newImageName);
        //     return response()->json(['name' => $request->image]);
        // });
    Route::apiResource('/categories', CategoryController::class);
    Route::post('/categories/edit/{category}', [CategoryController::class, 'myedit']);
    Route::apiResource('/products', ProductController::class);
    Route::post('/products/edit/{product}', [ProductController::class, 'myedit']);
});

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);
// filter
Route::post('/products/filter', [ProductController::class, 'filter']);

Route::apiResource('/comments', CommentController::class);
Route::apiResource('/public/products', ProductController::class);