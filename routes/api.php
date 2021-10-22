<?php

use App\Http\Controllers\{
    API\UserController,
    EmployeeController
};
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

Route::post('/auth/token', [UserController::class, 'login'])->name('login');
Route::post('/register', [UserController::class, 'register'])->name('register');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('user');

    Route::get('/teste', function (Request $request) {
        return 'teste';
    })->name('teste');

    Route::get('/employees', [EmployeeController::class, 'get'])
        ->name('employees.get');

    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])
        ->name('employees.show');

    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])
        ->name('employees.destroy');
});
