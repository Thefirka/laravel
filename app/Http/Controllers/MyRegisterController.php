<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MyRegisterController extends Controller
{
    public function view()
    {
        return view('myRegister');
    }

    public function post(RegisterRequest $request)
    {
            User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return redirect('/login');
    }
}
