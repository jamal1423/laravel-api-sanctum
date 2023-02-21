<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

//API route for register new user
Route::post('/register', [AuthController::class, 'register']);
//API route for login user
Route::post('/login', [AuthController::class, 'login']);

//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Route::get('/profile', function(Request $request) {
    //     // return auth()->user();
    //     return response()->json([
    //         'token' => $request->bearerToken()
    //     ]);
    // });

    Route::get('/profile', [ProfileController::class, 'data_profile']);

    // Http::withHeaders([
    //     'Accept' => 'application/json', 
    //     'Authorization' => 'Bearer 6|JlvzZB50V8gQ8Toft2xS1pP6jRriNRKJwFL8Q8pC'
    // ])->get('http://127.0.0.1:8000/api/profile');

    // API route for logout user
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
