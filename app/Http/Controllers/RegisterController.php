<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    
    public function showRegistrationForm()
    {
        return view('register');
    }

   
    public function register(Request $request)
    {
      
        $validator = Validator::make($request->all(), [ //make : d-ru-mes  //all tabAsso
            'first_name' => 'required|string|max:255|regex:/^[\p{L}\s\'-]+$/u',
            'last_name' => 'required|string|max:255|regex:/^[\p{L}\s\'-]+$/u',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|in:teacher,student',
        ]);

        if ($validator->fails()) { // ktrj3 vrai ila une ou plusieurs règles ne sont pas respectées.
            return back() // rj3 l page prece ,  ola 3ndk erreures b9a 
                ->withErrors($validator)  ;   //sift les message     
        }

        
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // Required field - must be teacher or student
        ]);

        return redirect('/login')->with('success', 'Registration successful! Please log in.');
    }
}

