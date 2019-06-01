<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function writeContent(Request $request)
    {
        // Write File
        $newJsonString = json_encode($request->input('about'), JSON_PRETTY_PRINT);
        file_put_contents(base_path('resources/pages/about.json'), stripslashes($newJsonString));
        return response()->json(['success' => true], 200);
    }

    public function readContent()
    {
        // Read File
        $jsonString = file_get_contents(base_path('resources/pages/about.json'));
        $data = json_decode($jsonString, true);

        return response()->json(['success' => true,'data' => $data], 200);
    }
}
