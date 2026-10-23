<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function updateStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,suspended',
        ]);

        $user->update(['status' => $validated['status']]);

        return back()->with('success', 'Status pengguna berhasil diperbarui');
    }
}
