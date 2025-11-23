<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminSettingsController extends Controller
{
    /**
     * Show the admin settings page.
     */
    public function index()
    {
        $admin = Auth::user();
        return view('admin.settings.admin', compact('admin'));
    }

    /**
     * Update admin account settings.
     */
    public function update(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => [
                'nullable',
                'required_with:current_password',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ], [
            'name.required' => 'Admin name is required.',
            'email.required' => 'Admin email is required.',
            'email.unique' => 'This email address is already in use.',
            'current_password.required_with' => 'Current password is required when setting a new password.',
            'new_password.required_with' => 'New password is required when current password is provided.',
            'new_password.confirmed' => 'New password confirmation does not match.',
        ]);

        // Update name and email
        $admin->name = $request->name;
        $admin->email = $request->email;

        // Handle password update
        if ($request->filled('current_password')) {
            // Verify current password
            if (!Hash::check($request->current_password, $admin->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'The current password is incorrect.'])
                    ->withInput();
            }

            // Update to new password
            $admin->password = Hash::make($request->new_password);
        }

        $admin->save();

        $message = 'Admin settings updated successfully!';
        if ($request->filled('new_password')) {
            $message .= ' Your password has been changed.';
        }

        return redirect()->route('admin.settings.admin')
            ->with('success', $message);
    }
}
