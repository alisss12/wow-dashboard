<?php

namespace App\Http\Controllers;

use App\Models\BoosterApplication;
use Illuminate\Http\Request;

class BoosterApplicationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'character_name' => 'required|string|max:255',
            'realm' => 'required|string|max:255',
            'class' => 'required|string',
            'spec' => 'required|string',
            'roles' => 'required|array|min:1',
            'experience' => 'required|string',
            'logs_url' => 'nullable|url',
        ]);

        BoosterApplication::create([
            'user_id' => auth()->id(),
            'character_name' => $request->character_name,
            'realm' => $request->realm,
            'class' => $request->class,
            'spec' => $request->spec,
            'roles' => $request->roles,
            'experience' => $request->experience,
            'logs_url' => $request->logs_url,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your application has been submitted and is pending HQ verification.');
    }
}
