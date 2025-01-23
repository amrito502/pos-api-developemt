<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\OTPMail;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function UserRegistration(Request $request){
        try{
            User::create([
                'firstName'=> $request->input('firstName'),
                'lastName'=> $request->input('lastName'),
                'email'=> $request->input('email'),
                'mobile'=> $request->input('mobile'),
                'password'=> $request->input('password'),
            ]);

            return response()->json([
               'status'=>'success',
               'message'=> 'User Registration Successfully!'
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'status'=>'failed',
                // 'message'=> 'Failed User Registration!'
                'message'=> $e->getMessage()
             ]);
        }
     }


     public function UserLogin(Request $request){
        $count = User::where('email','=',$request->input('email'))
            ->where('password','=',$request->input('password'))
            ->count();

        if($count==1){
            $token = JWTToken::CreateToken($request->input('email'));
            return response()->json([
                'status'=>'success',
                'message'=> 'User Login Successfully!',
                'token'=> $token
             ]);
        }
        else{
            return response()->json([
                'status'=>'failed',
                'message'=> 'unauthorized!'
             ]);
        }
     }


     public function SendOTPCode(Request $request){
        $email = $request->input('email');
        $otp = rand(1000, 9999);
        $count = User::where('email','=',$email)->count();

        if($count == 1){
            //mail-send-otp
            Mail::to($email)->send(new OTPMail($otp));
            //otp-code-table-update
            User::where('email','=',$email)->update(['otp'=>$otp]);
            return response()->json([
                'status'=>'success',
                'message'=> '4 Digit OTP Code Has Been Sent To Your Email!'
             ]);
        }
        else
        {
            return response()->json([
                'status'=>'failed',
                'message'=> 'unauthorized!'
             ]);
        }
     }


     public function VerifyOTP(Request $request){
        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email','=',$email)
            ->where('otp','=',$otp)->count();

        if($count==1){
            // Database otp update
            User::where('email','=',$email)
            ->update(['otp'=>'0']);
            // password reset token issue
            $token = JWTToken::CreateTokenForSetPassword($request->input('email'));
            return response()->json([
                'status'=>'success',
                'message'=> 'OTP Verification Successfully!',
                'token'=> $token
             ]);
        }
        else{
            return response()->json([
                'status'=>'failed',
                'message'=> 'unauthorized'
             ]);
        }
     }

     public function ResetPassword(Request $request){
        try{
            $email = $request->header('email');
            $password = $request->input('password');
            User::where('email','=',$email)->update(['password'=>$password]);
            return response()->json([
                'status'=>'success',
                'message'=> 'Request Successfully!'
             ]);
        }
        catch(\Exception $e){
            return response()->json([
                'status'=>'failed',
                'message'=> 'Somethings Went Wrong!'
             ]);
        }
     }


}
