<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Akun sistem tunggal yang menampung token admin (bukan akun login biasa).
    private const ADMIN_EMAIL = 'admin@injourney.local';

    /**
     * ADMIN: Login.
     * Kredensial dibaca dari config/admin.php (.env), tidak ada lagi yang
     * hardcode di controller maupun di JS sisi client.
     * Sukses -> mengeluarkan Sanctum token yang harus dikirim sebagai
     * "Authorization: Bearer <token>" di setiap request admin selanjutnya.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $validUsername = (string) config('admin.username');
        $validPassword = (string) config('admin.password');

        // hash_equals supaya perbandingan tahan timing-attack.
        $usernameMatch = hash_equals($validUsername, (string) $request->username);
        $passwordMatch = hash_equals($validPassword, (string) $request->password);

        if (!$usernameMatch || !$passwordMatch) {
            return response()->json(['message' => 'Username atau Password salah'], 401);
        }

        $admin = User::firstOrCreate(
            ['email' => self::ADMIN_EMAIL],
            ['name' => 'Administrator', 'password' => Hash::make(Str::random(40))]
        );

        // Hanya satu sesi admin aktif pada satu waktu.
        $admin->tokens()->delete();
        $token = $admin->createToken('admin-dashboard')->plainTextToken;

        return response()->json([
            'message' => 'Login Berhasil',
            'token'   => $token,
        ]);
    }

    /**
     * ADMIN: Logout - mencabut token yang sedang dipakai.
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $token = $user->currentAccessToken();
            if ($token) {
                $token->delete();
            }
        }

        return response()->json(['message' => 'Logout berhasil']);
    }
}
