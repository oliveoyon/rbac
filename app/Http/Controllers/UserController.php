<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\District;
use App\Models\Pngo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Display a listing of the users
    public function index()
    {
        $users = User::all();
        $districts = District::all();
        $pngos = Pngo::all();
        return view('dashboard.admin.users', compact('users', 'districts', 'pngos'));
    }

    // Show the form for creating a new user
    public function create()
    {
        $districts = District::all();
        $pngos = Pngo::all();
        return view('dashboard.admin.index', compact('districts', 'pngos'));
    }

    // Store a newly created user
    // Store a newly created user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'district_id' => 'required',
            'pngo_id' => 'required',
            'status' => 'required',
        ]);
    
        // Create the user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt('defaultpassword'); // You can set a default password or generate one
        $user->district_id = $request->district_id;
        $user->pngo_id = $request->pngo_id;
        $user->status = $request->status;
        $user->save();
    
        return response()->json([
            'success' => true,
            'message' => 'User created successfully!',
        ]);
    }
    


    // Show the form for editing the user
    public function edit(User $user)
    {
        $districts = District::all();
        $pngos = Pngo::all();
        return response()->json([
            'user' => $user,
            'districts' => $districts,
            'pngos' => $pngos
        ]);
    }

    // Update the specified user
    public function update(Request $request, User $user)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id, // Unique except current user
            'district_id' => 'required|exists:districts,id',
            'pngo_id' => 'required|exists:pngos,id',
            'status' => 'required|in:active,inactive',
        ]);

        // If password is provided, hash it
        if ($request->has('password')) {
            $validated['password'] = Hash::make($request->input('password'));
        }

        // Update the user
        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    // Remove the specified user
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }
}

