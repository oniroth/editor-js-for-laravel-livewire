<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class uploadFileController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);
        $file = $request->file('file');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('uploads', $filename, 'public');
        $url = Storage::url('uploads/' . $filename);
        return response()->json([
            'success' => 1,
            'file' => [
                'url' => $url,
            ],
        ]);
    }
}
