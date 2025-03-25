<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function profile()
    {
        return view('user.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'address' => ['nullable', 'string', 'max:255'], // Added address validation
            'contact_number' => ['nullable', 'string', 'max:20'], // Added contact number validation
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address; // Added address
        $user->contact_number = $request->contact_number; // Added contact number

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo) {
                Storage::delete('public/' . $user->photo);
            }
            $photoPath = $request->file('photo')->store('users', 'public');
            $user->photo = $photoPath;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.profile')->with('success', 'Profile updated successfully');
    }

    public function index()
    {
        $this->authorize('viewAny', User::class);
        return view('admin.users.index');
    }

    public function list()
    {
        $this->authorize('viewAny', User::class);
        $users = User::all();
        return response()->json(['data' => $users]);
    }

    public function updateStatus(Request $request, User $user)
    {
        $this->authorize('update', $user);
        $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $user->status = $request->status;
        $user->save();

        return response()->json(['message' => 'User status updated successfully', 'user' => $user]);
    }

    public function updateRole(Request $request, User $user)
    {
        $this->authorize('update', $user);
        $request->validate([
            'role' => ['required', 'string', Rule::in(['user', 'admin'])],
        ]);

        $user->role = $request->role;
        $user->save();

        return response()->json(['message' => 'User role updated successfully', 'user' => $user]);
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return view('admin.users.show', compact('user'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.users.index')->with('error', 'User not found.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:6',
            'address' => 'nullable|string|max:255', // Added address validation
            'contact_number' => 'nullable|string|max:20', // Added contact number validation
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = $request->file('photo') ? $request->file('photo')->store('users', 'public') : null;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'address' => $request->address, // Added address
            'contact_number' => $request->contact_number, // Added contact number
            'photo' => $photoPath,
            'role' => $request->role ?? 'user',
            'status' => true,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255', // Added address validation
            'contact_number' => 'nullable|string|max:20', // Added contact number validation
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::delete('public/' . $user->photo);
            }
            $photoPath = $request->file('photo')->store('users', 'public');
            $user->photo = $photoPath;
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address, // Added address
            'contact_number' => $request->contact_number, // Added contact number
            'photo' => $user->photo,
        ]);

        return redirect()->route('admin.users.show', $user->id)->with('success', 'User updated successfully');
    }
}