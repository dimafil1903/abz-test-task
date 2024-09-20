<?php

namespace App\Http\Controllers\Api;

use App\Models\User;

class TokenController
{
    public function index()
    {
        $user = User::firstOrCreate([
                'email' => 'guest@example.com',
                'phone' => '1234567890',
            ],
            ['name' => 'Guest User']
        );

        $token = $user->createToken('registration_token', ['registration'])->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

}
