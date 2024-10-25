<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Http\Resources\RegisterResource;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function GetMessage($resource,$message,$additional = []){
        $response = [
            'message' => $message,
            'data' => $resource
        ];
        return response()->json(array_merge($response,$additional));
    }

    public static function scopeCreateUser($query,$request){

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password']
        ]);
        $user->token = self::scopeCreateToken($user);
        
        return self::GetMessage(new RegisterResource($user),config('constants.messages.register.success'));
    }

    public static function scopeCreateToken($user){
        return $user->createToken('Laravel Password Grant Client')->accessToken;
    }

    public static function scopeLoginUser($query,$request){
        $user = User::where('email',$request['email'])->first();
        if(!$user || !Hash::check($request['password'],$user->password)){
            return self::GetMessage([],config('constants.login.failed'));
        }
    }

    public static function GetError($message, $additional = [])
    {
        $response = [
            'message' => $message,
            'errors' => (object) [],
        ];

        return response()->json(array_merge($response, $additional), config('constants.validation_codes.unassigned'));
    }
}
