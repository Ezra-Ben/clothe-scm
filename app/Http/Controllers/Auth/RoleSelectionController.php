<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleSelectionController extends Controller
{
    public function show()
    {
        return view('auth.select_role');
    }

    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|in:carrier,customer',
        ]);
        session(['selected_role' => $request->role]);
        return redirect()->route('register');
    }
}