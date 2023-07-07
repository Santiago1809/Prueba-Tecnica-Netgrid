<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request):JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        if (auth()->attempt($credentials)) {
            return response()->json(['mensaje'=>'Successfully loged'], 201);
        }
        else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }
}
