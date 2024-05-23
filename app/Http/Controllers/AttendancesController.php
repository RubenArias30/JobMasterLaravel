<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employees;
use Illuminate\Support\Facades\Auth;

class AttendancesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
      /**
     * Register entry for an employee.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerEntry(Request $request)
    {
        // Get the ID of the authenticated user
        $userId = auth()->user()->id;

        // Create a new attendance record
        $attendance = new Attendance();

        // Set up the data for the new record
        $attendance->date = now()->toDateString(); 
        $attendance->start_time = now()->toTimeString(); 
        $attendance->employees_id = $userId; 
        $attendance->status = 'present'; 

        // Save the new record to the database
        $attendance->save();

        // Return a success response with status code 201 
        return response()->json(['message' => 'Entrada registrada correctamente como presente'], 201);
    }


    /**
     * Register exit for an employee.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerExit(Request $request)
    {
        // Get the ID of the authenticated user
        $userId = auth()->user()->id;

        // Find the most recent attendance record for the authenticated user
        $attendance = Attendance::where('employees_id', $userId)->latest()->first();

        // If no attendance record is found for the authenticated user, return an error message
        if (!$attendance) {
            return response()->json(['error' => 'No se encontró un registro de asistencia para este usuario'], 404);
        }

        // Update the attendance record with the exit time and total elapsed time
        if (!$attendance->end_time) {
            $attendance->end_time = now()->toTimeString();

            // Calculate the total elapsed time
            $startTime = \Carbon\Carbon::parse($attendance->start_time);
            $endTime = \Carbon\Carbon::parse($attendance->end_time);
            $totalTime = $endTime->diff($startTime)->format('%H:%I:%S');

            $attendance->total_time = $totalTime;
            $attendance->status = 'finalized';
            $attendance->save();

            return response()->json(['message' => 'Salida registrada correctamente'], 200);
        } else {
            return response()->json(['error' => 'El usuario ya ha registrado la salida anteriormente'], 400);
        }
    }
    /**
     * Get the status of employees.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmployeeStatus()
    {
        // Get the IDs of all employees
        $employeeIds = Employees::pluck('id');

        // Filter out the admin ID to exclude it
        $adminId = 1;
        $employeeIds = $employeeIds->reject(function ($employeeId) use ($adminId) {
            return $employeeId === $adminId;
        });

        // Get the IDs of employees with at least one present attendance
        $presentEmployeeIds = Attendance::whereIn('employees_id', $employeeIds)
            ->where('status', 'present')
            ->distinct()
            ->pluck('employees_id');

        // Calculate the number of inactive employees
        $inactiveEmployeeCount = count($employeeIds) - count($presentEmployeeIds);

        return response()->json([
            'present_employee_count' => count($presentEmployeeIds),
            'inactive_employee_count' => $inactiveEmployeeCount
        ]);
    }
        /**
     * Get the last exit date for the authenticated user.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLastExitDate(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            $userId = $user->id;

            $lastExitDate = Attendance::where('employees_id', $userId)
                ->whereNotNull('end_time')
                ->orderByDesc('created_at')
                ->value('end_time');

            if ($lastExitDate) {
                $lastExitDateFormatted = Carbon::parse($lastExitDate)->toISOString();
                return response()->json(['lastExitDate' => $lastExitDateFormatted], 200);
            } else {
                return response()->json(['lastExitDate' => null], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching last exit date'], 500);
        }
    }



}
