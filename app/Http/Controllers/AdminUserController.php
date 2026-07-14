<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50',
            'password' => 'required|string|min:4',
        ]);

        Admin::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
        ]);

        return $this->redirectWithSuccess('Akun admin berhasil ditambahkan.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:tb_admin,id',
            'username' => 'required|string|max:50',
            'password' => 'nullable|string|min:4',
        ]);

        $data = ['username' => $validated['username']];
        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        Admin::findOrFail($validated['user_id'])->update($data);

        return $this->redirectWithSuccess('Akun admin berhasil diperbarui.');
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:tb_admin,id',
        ]);

        if ($validated['user_id'] === (int) $request->session()->get('admin_id')) {
            return redirect()->route('admin.dashboard', ['tab' => 'users'])
                ->with('error', 'Akun yang sedang digunakan tidak dapat dihapus.');
        }

        Admin::findOrFail($validated['user_id'])->delete();

        return $this->redirectWithSuccess('Akun admin berhasil dihapus.');
    }

    private function redirectWithSuccess(string $message)
    {
        return redirect()->route('admin.dashboard', ['tab' => 'users'])->with('success', $message);
    }
}
