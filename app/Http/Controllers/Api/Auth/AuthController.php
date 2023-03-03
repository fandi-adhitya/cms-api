<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  public function auth(AuthRequest $request)
  {
    $payload = [
      'email' => $request['email'],
      'password' => $request['password']
    ];

    if(auth()->attempt($payload)){
      $user = User::where('email', $payload['email'])->first();
      $token = $user->createToken('secret');

      $output = [
        'user' => $payload['email'],
        'accessToken' => $token->accessToken,
        'expiredAt' => $token->token->expires_at
      ];

      return response()->json($output,200);
    } 

    $output = [
      "message" => "Wrong email & password"
    ];

    return response()->json($output, 422);
  }
}
