<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\Employees;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

use Symfony\Component\HttpFoundation\Response;

class ResetPasswordController extends Controller
{

    public function resetPasswordRequest(Request $request)
    {
        try {
            $email = $request->input('email');
            $employee = Employees::where('email', $email)->first();
            if (!$employee) {
                return response()->json(['error' => 'Email not found in our database'], 400);
            }
            $this->sendResetPasswordEmail($email);
            return response()->json(['message' => 'Reset password email sent successfully, please check your inbox.'], 200);
        } catch (\Exception $e) {
            // Return an error response with a meaningful message
            return response()->json(['error' => 'An unexpected error occurred while processing your request.'], 500);
        }
    }

    public function sendResetPasswordEmail($email)
{
    $oldToken = DB::table('password_resets')->where('email', $email)->first();

    if ($oldToken) {
        DB::table('password_resets')->where('email', $email)->delete();
    }

    $token = Str::random(60);
    Mail::to($email)->send(new ResetPasswordMail($token));
    $this->saveToken($token, $email);
    return $token;
}


    public function saveToken($token, $email){
        DB::table('password_resets')->insert([
            "email" => $email,
            "token" => $token,
            "created_at" => Carbon::now()
        ]);
    }

    public function resetPassword(Request $request)
    {
       try {
           $validated = $request->validate([
               'email' => 'required',
               'password' => 'required',
               'password_confirmation' => 'required',
               'resetToken' => 'required',
           ]);


           return $this->resetPasswordTable($validated) ? $this->changePasswordDB($validated) :
               $this->NotFound();
       } catch (\Exception $e) {

           return response()->json(['error' => 'An unexpected error occurred while processing your request.'], 500);
       }
    }


       public function resetPasswordTable($request)
       {
           return DB::table('password_resets')->where(['email' => $request['email'], 'token' => $request['resetToken']]);
       }


       public function NotFound(){
           return response()->json(['error' => 'Token or email incorrect']);
       }


       public function changePasswordDB($request){
           $employee = Employees::whereEmail($request['email'])->first();
           $user = User::find($employee->users_id);
           $user->Update(['password' => bcrypt($request['password'])]);
           $this->resetPasswordTable($request)->delete();
           return response()->json(['message' => 'Successfully Changed Password'], 200);

       }


}
