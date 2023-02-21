<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use \Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
         ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['data' => $user,'access_token' => $token, 'token_type' => 'Bearer', ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        $request->session()->put('xrfTOKEN',$token);
        
        $request->headers->set('Authorization', 'Bearer '.$token);

        return response()->json([
            'message' => 'Hi '.$user->name.', welcome to home',
            'access_token' => $token, 
            'token_type' => 'Bearer', 
        ]);
    }

    // method for user logout and delete token
    public function logout(Request $request)
    {
        $sessionToken = session()->get('xrfTOKEN');
        $getToken = PersonalAccessToken::findToken($sessionToken);
        // $token = $getToken->tokenable_id;
        if($getToken){
            auth()->user()->tokens()->delete();
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Successfully logout',
            ]);
        }

        return response()->json([
            'code' => 403,
            'status' => false,
            'message' => 'Unauthenticated',
        ]);
        
    }
}
