<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{

    public function index()
    {
        return view('web.contact-us');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        $data = [
            'first_name' => $request->first_name,   
            'last_name' => $request->last_name,
            'email' => $request->email,
            'message' => $request->message,
        ];

        Contact::create($data);

        return back()->with('success', 'Message sent successfully!');
    }
}
