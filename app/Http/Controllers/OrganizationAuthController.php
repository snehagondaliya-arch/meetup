<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class OrganizationAuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate(
            [
                'organization_name' => 'required|string|max:255',
                'email' => 'required|email|unique:organizations,email',
                'password' => 'required|string|min:8|confirmed',
            ],
            [
                'organization_name.required' => 'Organization name is required.',
                'organization_name.string' => 'Organization name must be a string.',
                'organization_name.max' => 'Organization name may not be greater than 255 characters.',

                'email.required' => 'Email is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email is already registered.',

                'password.required' => 'Password is required.',
                'password.string' => 'Password must be a string.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.confirmed' => 'Password confirmation does not match.',
            ]
        );

        $organization = Organization::create([
            'organization_name' => $validated['organization_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        Auth::guard('organization')->login($organization);

        return response()->json([
            'redirect' => route('index'),
            'message' => 'success'
        ]);
    }
    public function login(Request $request)
    {
        $credentials = $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required|string|min:8',
            ],
            [
                'email.required' => 'Email is required.',
                'email.email' => 'Please enter a valid email address.',

                'password.required' => 'Password is required.',
                'password.string' => 'Password must be a string.',
                'password.min' => 'Password must be at least 8 characters.',
            ]
        );

        if (Auth::guard('organization')->attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json([
                'redirect' => route('index'),
                'message' => 'success'
            ]);
        }
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }


}
