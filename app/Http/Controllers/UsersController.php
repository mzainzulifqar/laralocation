<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the new user
        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        // Save the user to the database
        $user->save();

        // Redirect back with a success message
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function update(Request $request, User $user)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update the user data
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->input('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        // Save the updated user to the database
        $user->save();

        // Redirect back with a success message
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Delete the user from the database
        $user->delete();

        // Redirect back with a success message
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
