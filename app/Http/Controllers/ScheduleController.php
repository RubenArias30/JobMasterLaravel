<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use App\Models\Schedule;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

       /**
     * Display a listing of the schedules for a specific employee.
     *
     * @param  int  $employeeId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($employeeId)
    {
        $employee = Employees::find($employeeId);

        if (!$employee) {
            return response()->json(['error' => 'Empleado no encontrado'], 404);
        }

        $schedules = $employee->schedules;

        return response()->json($schedules, 200);
    }

   /**
     * Store a newly created schedule for the specified employee.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $id)
    {
        // Find the employee
        $employee = Employees::find($id);

        if (!$employee) {
            return response()->json(['error' => 'Empleado no encontrado'], 404);
        }

        // Validate the data
        $request->validate([
            'title' => 'required',
            'start_datetime' => 'required',
            'end_datetime' => 'required',
        ]);


        // Create the schedule
        $schedule = new Schedule([
            'title' => $request->title,
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
        ]);

        // Associate the schedule with the employee
        $employee->schedules()->save($schedule);

        return response()->json($schedule, 201);
    }

        /**
     * Display the specified schedule.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $event = Schedule::find($id);
        if (!$event) {
            return response()->json(['error' => 'Evento no encontrado'], 404);
        }
        return response()->json($event);
    }
    /**
     * Show the schedule of the authenticated employee.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showSchedule(Request $request)
    {
        // Get the authenticated employee
        $employeeId = $request->user()->id;

        // Check if the employee exists
        if (!$employeeId) {
            return response()->json(['error' => 'Empleado no encontrado'], 404);
        }

        // Get the schedules of the employee
        $schedule = Schedule::where('employees_id', $employeeId)->get();

        return response()->json($schedule);
    }

       /**
     * Update the specified schedule in the storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

        // Find the schedule by its ID
        $schedule = Schedule::find($id);

        // Check if the schedule exists
        if (!$schedule) {
            return response()->json(['message' => 'Horario no encontrado'], 404);
        }

        // Validate the form data
        $request->validate([
            'title' => 'required',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date',
        ]);

        // Update the schedule data
        $schedule->title = $request->input('title');
        $schedule->start_datetime = $request->input('start_datetime');
        $schedule->end_datetime = $request->input('end_datetime');

        // Save the changes to the database
        $schedule->save();

        // Return a JSON response with a success message and the updated schedule data
        return response()->json(['message' => 'Horario actualizado correctamente', 'schedule' => $schedule], 200);
    }

    /**
     * Remove the specified event from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteEvent($id)
{
    try {
        $event = Schedule::findOrFail($id); // Find the event by its ID
        $event->delete(); // Delete the event

        return response()->json(['message' => 'Evento eliminado correctamente'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error al eliminar el evento'], 500);
    }
}



}
