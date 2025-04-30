<?php

namespace App\Http\Controllers;

use App\Traits\CacheTrait;
use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    //
    use CacheTrait;
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
    public function clearCacheAll()
    {
        $this->clearCache();
        return back()->with('success', 'Cache cleared successfully!');
    }
}
