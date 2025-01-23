USER MODUALS: </br>
Requests: </br>
    1. Registration = post request = http://127.0.0.1:8000/user-registeration </br>
    2. Login = post request = http://127.0.0.1:8000/user-login </br>
    3. Send OTP = post request = http://127.0.0.1:8000/send-otp </br>
    4. Verify OTP = post request = http://127.0.0.1:8000/verify-otp </br>
    5. Reset Password = post request = http://127.0.0.1:8000/reset-password </br>

JSON: </br>
Registration :  </br>
{
    "firstName":"Amrito",
    "lastName":"Bosu",
    "email":"amritobosu0@gmail.com",
    "mobile":"01792618308",
    "password":"123"
}

Login :  </br>
{
    "email":"amritobosu0@gmail.com",
    "password":"123"
}

Send OTP : </br>
{
    "email":"amritobosu0@gmail.com"
}

Verify OTP : </br>
{
   "email":"amritobosu0@gmail.com",
   "otp":"9733"
}

Reset Password : </br>
{
    "password":"xyz"
}

and include in header: key:token ,value: (we get in verify otp) like this : eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsYXJhdmVsLXRva2VuIiwiaWF0IjoxNzM3NjYzNDA4LCJleHAiOjE3Mzc2NjQ2MDgsInVzZXJFbWFpbCI6ImFtcml0b2Jvc3UwQGdtYWlsLmNvbSJ9.9rzbZf3tbrDxwD2iGzUk4uUc_1hdtm6ULuvpojnxIPU
