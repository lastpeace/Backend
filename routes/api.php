
<?php

use App\Http\Controllers\API\DivisionController;
use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\EmployeeController;

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
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/divisions', [DivisionController::class, 'index']);
Route::middleware('auth:sanctum')->get('/employees', [EmployeeController::class, 'index']);
Route::middleware('auth:sanctum')->post('/employees', [EmployeeController::class, 'store']);
Route::middleware('auth:sanctum')->put('/employees/{id}', [EmployeeController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/employees/{id}', [EmployeeController::class, 'destroy']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

