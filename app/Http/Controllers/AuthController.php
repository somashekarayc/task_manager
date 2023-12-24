<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function signUp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', Password::min(8)->letters()->numbers()],
            'name' => ['required', 'string'],

        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;

        $user->save();

        return [
            'status' => 'success'
        ];
    }
}
