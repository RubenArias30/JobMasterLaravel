<?php
use App\Http\Controllers\AttendancesController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\DocumentController;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController; // Importa el controlador ProfileController
use App\Http\Controllers\ResetPasswordController;

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

//RESET PASSWORD
Route::post('/sendPasswordResetLink', [ResetPasswordController::class, 'resetPasswordRequest']);
Route::post('/resetPassword', [ResetPasswordController::class, 'resetPassword']);


//EMPLOYEE
Route::get('/employees', [EmployeeController::class, 'index']);
Route::post('/employees', [EmployeeController::class, 'store']);
Route::delete('/employees/{id}', [EmployeeController::class, 'delete']);
Route::get('/employees/{id}', [EmployeeController::class, 'show']);
Route::get('/employees/checkNifExists/{nif}', [EmployeeController::class, 'checkNifExists']);
Route::put('/employees/{id}', [EmployeeController::class, 'update']);
Route::post('/employees/{id}/photo', [EmployeeController::class, 'updatePhoto']);


//BUDGET
Route::delete('/budget/{id}', [InvoiceController::class, 'delete']);
Route::get('/budget', [InvoiceController::class, 'index']);
Route::post('/budget', [InvoiceController::class, 'store']);
Route::put('/budget/{id}', [InvoiceController::class, 'update']);
Route::get('/budget/{id}', [InvoiceController::class, 'show']);


//DOCUMENT
Route::get('/documents/{employeeId}', [DocumentController::class, 'index']);
Route::delete('/documents/{id}', [DocumentController::class, 'destroy']);
Route::post('employees/{employee}/documents', [DocumentController::class, 'store']);
Route::get('/my-documents', [DocumentController::class, 'myDocuments']);
Route::get('documents/download/{documentId}', [DocumentController::class, 'download']);

//Schedule
Route::post('/employees/{id}/schedule', [ScheduleController::class, 'store']);
Route::get('/employees/{employeeId}/events', [ScheduleController::class, 'index']);
Route::get('/events/{id}', [ScheduleController::class, 'show']);
Route::get('/schedule', [ScheduleController::class, 'showSchedule']);
Route::put('/events/{id}', [ScheduleController::class, 'update']);
Route::delete('/schedule/{id}', [ScheduleController::class, 'deleteEvent']);
Route::get('/employees/events', [ScheduleController::class, 'getEmployeeSchedule']);

//Incidents
Route::get('/all_incidents', [IncidentController::class, 'index']);
Route::get('/incidents', [IncidentController::class, 'show']);
Route::post('/incidents', [IncidentController::class, 'store']);
Route::delete('/incidents/{id}', [IncidentController::class, 'destroy']);
Route::put('/incidents/{id}/status', [IncidentController::class, 'updateStatus']);

//Attendances
Route::post('/attendances/entry', [AttendancesController::class, 'registerEntry']);
Route::post('/attendances/exit', [AttendancesController::class, 'registerExit']);
Route::get('/employee-status', [AttendancesController::class, 'getEmployeeStatus']);
Route::get('/lastExitDate', [AttendancesController::class, 'getLastExitDate']);


//My Profile(employees)
Route::get('/profile', [ProfileController::class, 'getProfile']);

//Absences
Route::get('/absences', [AbsenceController::class, 'index']);
Route::post('/absences', [AbsenceController::class, 'store']);
Route::delete('//absences/{id}', [AbsenceController::class, 'destroy']);
Route::put('/absences/{id}', [AbsenceController::class, 'update']); // Update an existing absence
Route::get('/absences/{id}', [AbsenceController::class, 'show']);
Route::get('/absences/employee/{id}', [AbsenceController::class, 'FilterAbsencesByEmployee']);
