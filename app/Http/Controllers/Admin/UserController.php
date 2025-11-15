<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\emailController;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\sendPassword;
use Illuminate\Support\Facades\Mail;

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

        // Store original password for email
        $password = $request->password;
        $email = $request->email;

        // Create user
        User::create([
            "name" => ucwords(strtolower($request->name)),
            "email" => $request->email,
            "password" => bcrypt($password), // Hash for security
            "role" => $request->role,
        ]);

        // Send email to the user
        Mail::to('peterjeronimojr@gmail.com')->queue(new sendPassword($password, $email));

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
    public function update(Request $request, User $user_management)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "email" => [
                "required",
                "string",
                "email",
                "max:255",
                \Illuminate\Validation\Rule::unique('users', 'email')->ignore($user_management->id),
            ],
            "password" => "nullable|string|min:8|max:255",
            "role" => "required|string|in:Admin,Publisher",
        ], [
            'email.unique' => 'The email has already been taken. Please choose a different email address.',
        ]);

        $user_management->update([
            "name" => ucwords(strtolower($request->name)),
            "email" => $request->email,
            "password" => $request->password ?: $user_management->password,
            "role" => $request->role,
        ]);
        return redirect()->route('user_management.edit', $user_management->id)
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
