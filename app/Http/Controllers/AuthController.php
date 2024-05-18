<?php

namespace App\Http\Controllers;

use App\Helper\TokenGenHelper;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    protected $tokenGenHelper;
    use HasApiTokens;

    public function __construct(TokenGenHelper $tokenGenHelper)
    {
        $this->tokenGenHelper = $tokenGenHelper;
    }
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>['required','min:3','max:50'],
            'email' => ['required','email','unique:users'],
            'password' => ['required','min:6']
        ]);
        if($validator->fails())
        {
            return $this->sendError('Invalid data', $validator->errors(),401);
        }

        

        $user = User::create([
            'name'=>$request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $expirationDate = now()->addHours(2);
        $token = $this->tokenGenHelper->generateToken($user->id);
        $user->api_token = $token['token'];
        $user->api_token_expire_at = $token['expiration_date'];
        $user->save();

        return $this->sendResponse($user,'User created successfully',201);
    }

    public function signin(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails())
        {
            return $this->sendError('Invalid data', $validator->errors(),401);
        }

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $this->tokenGenHelper->generateToken($user->id);
            $user->api_token = $token['token'];
            $user->api_token_expire_at = $token['expiration_date'];
            $user->save();
            return $this->sendResponse(['token' => $token],'Token generated successfully',200);
        } else {
            return $this->sendError('Unauthorized', [],401); 
        }
    }

    

    public function signout(Request $request)
    {
        $user = $this->checkUser($request->header('Authorization'));
        $user->api_token = null;
        $user->api_token_expire_at = null;
        $user->save();
        return $this->sendResponse(null,'Logged out successfully',200);
    }
}
