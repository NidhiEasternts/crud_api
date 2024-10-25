<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Http\Resources\RegisterResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResource;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $userData = $request->validated();
        $userData['password'] = Hash::make($userData['password']);
        return User::CreateUser($userData);
    }

    public function login(LoginRequest $request){
        $userData = $request->validated();
        if(!Auth::attempt($userData)){
            return User::GetError(config('constants.messages.user.invalid'));
        }
        $user = $request->user();
        $oauthClient = Client::where('password_client', 1)->latest()->first();
        if (is_null($oauthClient)) {
            return User::GetError('Oauth password client not found.');
        }
        if($user){
            $data = [
                'username' => $request->email,
                'password' => $request->password,
                'client_id' => $oauthClient->id,
                'client_secret' => $oauthClient->secret,
                'grant_type' => 'password',
            ];

            $request = app('request')->create('/oauth/token', 'POST', $data);
            $tokenResult = json_decode(app()->handle($request)->getContent());
            $user->authorization = $tokenResult->access_token;
            $user->refresh_token = $tokenResult->refresh_token;
        }
        return new LoginResource($user);
    }
}
