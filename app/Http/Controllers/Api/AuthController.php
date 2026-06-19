<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // HARDCODE USERNAME & PASSWORD ADMIN DI SINI
        // (Bisa diganti dengan database user nanti jika perlu)
        if ($request->username === 'admin' && $request->password === 'admin123') {
            return response()->json(['message' => 'Login Berhasil']);
        }

        return response()->json(['message' => 'Username atau Password salah'], 401);
    }
}