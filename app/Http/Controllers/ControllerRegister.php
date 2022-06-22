<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;



class ControllerRegister extends Controller
{
    public function registe(Request $request)
    {
        $request->validate([
            'CIN' => ['required', 'string', 'max:50'],
            'nom' => ['required', 'string', 'max:50'],
            'prenom' => ['required', 'string', 'max:50'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],
            'password' => ['required', 'string', 'max:100'],
            'password' => ['same:password_confirmation'],
        ]);

        return User::create([
            'CIN' => $request->CIN,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    }
}
