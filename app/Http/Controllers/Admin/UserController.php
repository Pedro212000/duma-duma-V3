<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_management = User::paginate(10);
        return view("admin.user_management.index", [
            'user_management' => $user_management,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.user_management.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|string|email|max:255|unique:users,email",
            "password" => "required|string|min:8|max:255",
            "role" => "required|string|in:Admin,Publisher",
        ], [
            "email.unique" => "The email address is already in use. Please use a different email.",
            "email.required" => "The email field is required.",
        ]);

        // Create a new user
        User::create([
            "name" => ucwords(strtolower($request->name)),
            "email" => $request->email,
            "password" => $request->password,
            "role" => $request->role,
        ]);

        return redirect()->route('user_management.create')
            ->with('status', 'User Added Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $userManagement)
    {
        $user_detail = $userManagement;
        return view("admin.user_management.edit", compact('user_detail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $userManagement)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "email" => [
                "required",
                "string",
                "email",
                "max:255",
                \Illuminate\Validation\Rule::unique('users', 'email')->ignore($userManagement->user_id, 'user_id'), // Use 'user_id'
            ],
            "password" => "nullable|string|min:8|max:255",
            "role" => "required|integer|in:0,1,2,3",
        ], [
            'email.unique' => 'The email has already been taken. Please choose a different email address.',
        ]);

        // Update the user data
        $userManagement->update([
            "name" => ucwords(strtolower($request->name)),
            "email" => $request->email,
            "password" => $request->password ? $request->password : $userManagement->password, // Hash password if provided
            "role" => $request->role,
        ]);

        return redirect()->route('user_management.edit', $userManagement->user_id)
            ->with('status', 'User Detail Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $userManagement)
    {
        $userManagement->delete();

        return redirect()->route('user_management.index')
            ->with('status', 'User Deleted Successfully');
    }
}
