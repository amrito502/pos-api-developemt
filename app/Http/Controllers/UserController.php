<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\OTPMail;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    // pages
    public function LoginPage(){
        return view('pages.auth.login-page');
    }

    public function RegistrationPage(){
        return view('pages.auth.registration-page');
    }
    public function SendOtpPage(){
        return view('pages.auth.send-otp-page');
    }
    public function VerifyOTPPage(){
        return view('pages.auth.verify-otp-page');
    }

    public function ResetPasswordPage(){
        return view('pages.auth.reset-pass-page');
    }

    function ProfilePage(){
        return view('pages.dashboard.profile-page');
    }

    // api
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
            ->select('id')->first();


        if($count!==null){
            $token = JWTToken::CreateToken($request->input('email'), $count->id);
            return response()->json([
                'status'=>'success',
                'message'=> 'User Login Successfully!',
             ])->cookie('token', $token, 60 * 24 * 30);
        }
        else{
            return response()->json([
                'status'=>'failed',
                'message'=> 'unauthorized!'
             ]);
        }
     }


    // public function UserLogin(Request $request) {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     $user = User::where('email', $request->input('email'))
    //                 ->where('password', $request->input('password'))
    //                 ->first();

    //     if ($user) {
    //         $token = JWTToken::CreateToken($user->email); // Generate JWT token

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'User Login Successfully!',
    //             'token' => $token
    //         ])->cookie('token', $token, 60 * 24 * 30);
    //     }

    //     return response()->json([
    //         'status' => 'failed',
    //         'message' => 'Unauthorized!',
    //     ], 401);
    // }





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
             ],200)->cookie('token', $token, 60 * 24 * 30);
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


     public function UserLogout(Request $request){
        return redirect('/userLogin')->cookie('token', '', -1);
     }



    function UserProfile(Request $request){
        $email=$request->header('email');
        $user=User::where('email','=',$email)->first();
        return response()->json([
            'status' => 'success',
            'message' => 'Request Successful',
            'data' => $user
        ],200);
    }

    function UpdateProfile(Request $request){
        try{
            $email=$request->header('email');
            $firstName=$request->input('firstName');
            $lastName=$request->input('lastName');
            $mobile=$request->input('mobile');
            $password=$request->input('password');
            User::where('email','=',$email)->update([
                'firstName'=>$firstName,
                'lastName'=>$lastName,
                'mobile'=>$mobile,
                'password'=>$password
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Profile Successful Updated!',
            ],200);

        }catch (\Exception $exception){
            return response()->json([
                'status' => 'fail',
                'message' => 'Something Went Wrong',
            ],200);
        }
    }


}
