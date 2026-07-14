<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Services\WebsiteContentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin(WebsiteContentService $websiteContent)
    {
        return view('login', ['content' => $websiteContent->all()]);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('username', $validated['username'])->first();

        if ($admin && Hash::check($validated['password'], $admin->password)) {
            $request->session()->regenerate();
            $request->session()->put('admin_logged_in', true);
            $request->session()->put('admin_id', $admin->id);

            return redirect()->route('admin.dashboard', ['tab' => 'dashboard']);
        }

        return redirect()->route('login')->with('error', 'Login gagal, cek kembali username atau password.');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
