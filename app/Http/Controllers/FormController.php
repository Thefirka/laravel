<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormController extends Controller
{
    public function view()
    {
        return view('form');
    }
    public function post(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|alpha_num|min:8|max:12'
        ]);
        return dd($validated);
    }
}
