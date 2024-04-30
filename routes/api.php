<?php
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\DocumentController;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ScheduleController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//LOGIN
Route::post('/login', [LoginController::class, 'login']);
Route::get('/me', [LoginController::class, 'me']);
Route::post('/refresh', [LoginController::class, 'refresh']);
Route::post('/logout', [LoginController::class, 'logout']);

//EMPLOYEE
Route::get('/employees', [EmployeeController::class, 'index']);
Route::post('/employees', [EmployeeController::class, 'store']);
Route::delete('/employees/{id}', [EmployeeController::class, 'delete']);
Route::get('/employees/{id}', [EmployeeController::class, 'show']);
Route::put('/employees/{id}', [EmployeeController::class, 'update']);

//BUDGET
Route::delete('/budget/{id}', [InvoiceController::class, 'delete']);
Route::get('/budget', [InvoiceController::class, 'index']);
Route::post('/budget', [InvoiceController::class, 'store']);
Route::put('/budget/{id}', [InvoiceController::class, 'update']);

//DOCUMENT
Route::get('/documents/{employeeId}', [DocumentController::class, 'index']);
Route::delete('/documents/{id}', [DocumentController::class, 'destroy']);
Route::post('/documents/details/{employeeId}', [DocumentController::class, 'store']);


//Schedule
Route::post('/employees/{id}/schedule', [ScheduleController::class, 'store']);
Route::get('/employees/{id}', [ScheduleController::class, 'show']);
Route::get('/employees/{employeeId}/events', [ScheduleController::class, 'index']);
Route::post('employees/{id}/check-existing-schedule', [ScheduleController::class, 'checkExistingSchedule']);

