<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;

Route::get('/profiles-json', function () {
    // Path to the JSON file
    $jsonFilePath = public_path('stories.json');

    // Check if the file exists
    if (File::exists($jsonFilePath)) {
        // Get the contents of the JSON file
        $jsonContent = File::get($jsonFilePath);

        // Return the content with the appropriate JSON response
        return response($jsonContent, 200)
                ->header('Content-Type', 'application/json');
    }

    // Return a 404 response if the file doesn't exist
    return response()->json(['error' => 'File not found'], 404);
});