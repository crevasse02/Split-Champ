<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    public function index(){
        return view('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout(); // This will log the user out

        // Optionally, you can invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken(); // Regenerate the CSRF token

        return redirect('/login'); // Redirect to homepage or login page after logout
    }
}
