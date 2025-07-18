<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Login extends Controller
{
    public function getLogin(Request $request) {
        if (1==1) {
            return response()->json(['message' => 'Login successful']);
        } else {
            return response()->json(['message' => 'Contrat id not found'], 404);
        }
    }
}
