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
    public function saveSidebarToggled(Request $request)
    {
        $request->validate([
            'value' => 'required|integer|in:0,1',
        ]);

        $value = $request->post('value'); // correct way to get value
        session(['sidebar_toggled' => (int) $value]);

        return response()->json(['status' => 'success']);
    }
}
