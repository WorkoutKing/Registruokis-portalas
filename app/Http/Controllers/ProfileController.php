<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('my_profile.profile', compact('user'));
    }
    public function update(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'surname' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:255',
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'surname' => $request->surname,
                'phone' => $request->phone,
                'password' => $request->password ? Hash::make($request->password) : $user->password,
            ]);

            return redirect()->back()->with('success', 'Profilis sėkmingai atnaujintas!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }
}
