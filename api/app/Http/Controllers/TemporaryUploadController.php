<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TemporaryUploadController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate(['file' => 'required|file|max:1024',]);

        $path = $request->file('file')->store('', 'temp');

        return response()->json([
            'url' => asset('storage/temp/' . $path),
        ]);
    }
}
