<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;

use Illuminate\Support\Str;
use App\Traits\HttpResponses;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewPassword;
use PhpParser\Node\Stmt\Return_;
use Stringable;

class AuthController extends Controller
{
    use HttpResponses;


    
    public function register(Request $request){
        try {
            //code...
            $validateData = $request->validate([
                'name'=> 'required',
                'email'=> 'required|email|unique:users',
                'password'=>'required|same:password_confirmation|min:8',
                'password_confirmation' => 'required|min:8'
            ]);
          $user = User::create([
            'name'=>$validateData['name'],
            'email'=>$validateData['email'],
            'password'=>Hash::make($validateData['password'])
          ]);
          if (User::count() == 1) {
            $user->role = 1; // Set default role to 1 for the first user added
            $user->save();
        }
          $token = $user->createToken('token')->plainTextToken;
    
          
          
          return response()->json([
              'user' => $user,
              'token' => $token
              
    
          ],201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()]);
        }
       
    }
    public function login(Request $request)
{//change error code to give unauthorized 401 error not 500
    try {
        //code...
        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
             $user = auth()->user();
             $token = $user->createToken('token')->plainTextToken;
             if(Auth::user()->role ==1){
             
              $roleArray = ['admin'];
             }
             elseif(Auth::user()->role==0){
               
                $roleArray = ['supervisor'];
             }
             elseif(Auth::user()->role == 2){
               
                $roleArray = ['intern'];
             }
             else{
                return response()->json([
                    "Message"=>"Not Sure which user type you are"
                ],403);
             }
             
             return response()->json([ 'user' => $user,
             'role'=>$roleArray,
             'token' => $token],201);
        }
return response()->json(['Message'=>'invalid credentails'],401);

       
    } catch (\Throwable $th) {
        //throw $th;
        return response()->json([$th->getMessage()],500);
    }
   

  

   
}

    public function remember($id){
        //fill in remeber me token
        try {
            //code...
            $user = User::find($id);
            if(!$user){
                return response()->json(['Message'=>'No such user'],403);
            }
            $user->rememberToken();
            $user->save();
            return response()->json(['Message'=>'i remember you']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()]);
        }


    }
    public function logout(Request $request)
    {
        try {
            Auth::user()->currentAccessToken()->delete();
            Auth::user()->tokens()->delete();
            session()->invalidate();
            
               
                return response()->json(['message' => 'Logout successful'], 204);
            
            
        } catch (\Throwable $th) {
            return response()->json([$th->getMessage()]);
        }
    }
    
    

    public function dashboard()
{
    $user = auth()->user();

    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    return response()->json(['name' => $user->name]);
}
public function forgot(Request $request)
{
    try {
        //code...
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);
    
        $token = Str::random(64);
    
        DB::table('password_resets')->insert([
            'email' => $request->email, 
            'token' => $token, 
            'created_at' => Carbon::now()
          ]);
         //$resetLink = route('reset', ['token' => $token]);
       
        $data =[
            'email'=>$request->email,
'token'=>$token,
'message'=>"New Password Credentials Sent"
//'resetLink'=>$resetLink
        ];

       Mail::send('new', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password');

        });
    
        return response()->json([$data],201);
      
    } catch (\Throwable $th) {
        //throw $th;
        return response()->json([
            $th->getMessage()
        ],500);
    }
    
}
public function reset(Request $request)
      {
        try {
            //code...
            $request->validate([
                'email' => 'required|email|exists:users',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required'
            ]);
    
            $updatePassword = DB::table('password_resets')
                                ->where([
                                  'email' => $request->email, 
                                  'token' => $request->token
                                ])
                                ->first();
    
            if(!$updatePassword){
                return response()->json(["Message"=>"Error in password Rest"],404);
            }
    
            $user = User::where('email', $request->email)
                        ->update(['password' => Hash::make($request->password)]);
   
            DB::table('password_resets')->where(['email'=> $request->email])->delete();
    
           return response()->json([
              "Message"=>"Password Reset"
           ],201);
        
        } catch (\Throwable $th) {
            //throw $th;
return response()->json([$th->getMessage()],500);
        }
         
 }

public function resetPasswordLink($token)
{
  
    // Replace the URL with your frontend URL and the page where the user can reset the password
    $frontendURL = 'https://example.com/reset-password-page';
    
    // Append the token as a parameter to the frontend URL
    $resetLink = $frontendURL . '?token=' . $token;

    // Redirect to the frontend URL with the token as a parameter
    return redirect()->away($resetLink);
}

}






