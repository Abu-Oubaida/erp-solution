<?php

namespace App\Http\Controllers\editor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    //
    public function upload(Request $request)
    {
        if ($request->hasFile('upload'))
        {
            $orginalName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($orginalName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;
            $request->file('upload')->move(public_path('image/media'),$fileName);
            $url = asset('image/media/'.$fileName);
            return response()->json(['fileName'=>$fileName,'uploaded'=>1,'url'=>$url]);
        }
    }
}
