<?php
use App\Http\Controllers\AttendancesController;
use App\Http\Controllers\IncidentController;
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
Route::post('employees/{employee}/documents', [DocumentController::class, 'store']);


//Schedule
Route::post('/employees/{id}/schedule', [ScheduleController::class, 'store']);
// Route::get('/employees/{id}', [ScheduleController::class, 'show']);
Route::get('/employees/{employeeId}/events', [ScheduleController::class, 'index']);
Route::post('employees/{id}/check-existing-schedule', [ScheduleController::class, 'checkExistingSchedule']);

Route::delete('/events/{id}', [ScheduleController::class, 'deleteEvent']);

// Route::delete('employees/{employeeId}/schedules/{scheduleId}', 'ScheduleController@delete');

//Incidents
Route::get('/all_incidents', [IncidentController::class, 'index']);
Route::get('/incidents', [IncidentController::class, 'show']);
Route::post('/incidents', [IncidentController::class, 'store']);
Route::delete('/incidents/{id}', [IncidentController::class, 'destroy']);
Route::put('/incidents/{id}/status', [IncidentController::class, 'updateStatus']);

//Attendances
Route::post('/attendances/entry', [AttendancesController::class, 'registerEntry']);
Route::post('/attendances/exit', [AttendancesController::class, 'registerExit']);
Route::get('/attendance/start-time', [AttendancesController::class, 'getStartTime']);
Route::get('/attendance/{id}', [AttendancesController::class, 'getUpdateTime']);
