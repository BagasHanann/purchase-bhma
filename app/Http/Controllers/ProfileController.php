<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // Display the profile edit form
    public function edit()
    {
        $user = Auth::user(); // Ensure Auth facade is used to get the authenticated user
        return view('profile.edit', compact('user'));
    }

    // Update the profile
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if ($user) {
            $user->name = $request->name;
            $user->nip = $request->nip;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
            return redirect()->route('home')->with('success', 'Profile updated successfully.');
        } else {
            return redirect()->route('edit-profile')->with('error', 'User not found.');
        }
    }
}