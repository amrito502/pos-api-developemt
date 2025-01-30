<?php

namespace App\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken{


    public static function CreateToken($userEmail, $userID):string{

        $key = env('JWT_KEY');
        $payload=[
            'iss'=>'laravel-token',
            'iat'=>time(),
            'exp'=>time()+60*60,
            'userEmail'=>$userEmail,
            'userID'=>$userID
        ];

        return JWT::encode($payload, $key, 'HS256');
    }


    public static function CreateTokenForSetPassword($userEmail):string{

        $key = env('JWT_KEY');
        $payload=[
            'iss'=>'laravel-token',
            'iat'=>time(),
            'exp'=>time()+60*20,
            'userEmail'=>$userEmail,
            'userID'=>'0'
        ];

        return JWT::encode($payload, $key, 'HS256');
    }


    public static function VerifyToken($token):string|object{

        try{
            if($token==null){
                throw new \Exception('Token is missing or invalid');
            }
            else
            {
                $key = env('JWT_KEY');
                $decoded = JWT::decode($token, new Key($key, 'HS256'));
                return $decoded;
            }
        }
        catch(\Exception $e){
            return 'unauthorized';
        }
    }

    // public static function VerifyToken($token): string {
    //     try {
    //         if (empty($token)) {
    //             throw new \Exception('Token is missing or invalid');
    //         }

    //         $key = env('JWT_KEY');

    //         $decoded = JWT::decode($token, new Key($key, 'HS256'));

    //         return $decoded->userEmail ?? 'unauthorized';
    //     } catch (\Exception $e) {
    //         return 'unauthorized';
    //     }
    // }

}



// ==============












