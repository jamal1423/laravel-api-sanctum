<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Laravel\Sanctum\PersonalAccessToken;

class ProfileController extends Controller
{
    public function data_profile(Request $request){

        // $headers = apache_request_headers();
        // foreach($headers as $key=>$hd){
        //     echo $key." : ".$hd."\n";
        // }

        $sessionToken = session()->get('xrfTOKEN');
        $getToken = PersonalAccessToken::findToken($sessionToken);
        // $token = $getToken->tokenable_id;
        if($getToken){
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Data found',
                'dataUser' => [auth()->user()->name,auth()->user()->email],
                'token' => $sessionToken
            ]);
        }

        return response()->json([
            'code' => 403,
            'status' => false,
            'message' => 'Unauthenticated',
        ]);
    }
}
