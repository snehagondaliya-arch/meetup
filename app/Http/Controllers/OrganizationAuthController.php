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
        $validated = $request->validate([
            'organization_name' => 'required|string|max:255',
            'email' => 'required|email|unique:organizations,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

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
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

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
