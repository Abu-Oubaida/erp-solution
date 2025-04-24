<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    //
    public function saveSidebarWidth(Request $request)
    {
        $request->validate([
            'width' => 'required|integer|min:230|max:400',
        ]);

        session(['sidebar_width' => $request->input('width')]);

        return response()->json(['status' => 'success']);
    }
}
