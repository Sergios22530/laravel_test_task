<?php

namespace App\Http\Controllers\Api;

use App\Core\Controllers\CoreApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ApiLoginController extends CoreApiController
{
    public function login(Request $request)
    {
        // Validate the incoming request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user with the provided credentials
        if (Auth::attempt($credentials)) {
            // Authentication passed, create a token for the user
            $user = Auth::user();
            $token = $user->createToken('TODO_LIST')->plainTextToken;

            $expiresAt = Carbon::now()->addDay();

            // Return the token in the response
            return response()->json(
                $this->formatter
                    ->addData($token, 'token')
                    ->addData($expiresAt->toDateTimeString(), 'expires_at')
                    ->formatAnswer()
            );
        }

        // If authentication fails, return an error
        return response()->json(
            $this->formatter
                ->addData('Unauthorized', 'message')
                ->formatAnswer(401),
            401
        );

    }
}
