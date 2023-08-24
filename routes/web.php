<?php

use App\Http\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/request', function (Request $request) {
    $data = $request->all();
    return ApiResponse::format($data);
});

    Route::get('/api/validation-error', function () {
    throw ValidationException::withMessages([
        'field_name' => ['Validation error message'],
    ]);
});

Route::get('/api/unexpected-error', function () {
    throw new Exception('Unexpected error occurred.');
});

Route::get('/api/logical-test', function (Request $request) {
    $s = $request->query('s');

    if ($s === 'example') {
        $data = 'Logical test passed.';
    } else {
        $data = 'Logical test failed.';
    }

    return ApiResponse::format($data);
});
