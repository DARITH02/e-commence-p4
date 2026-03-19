<?php

use Illuminate\Support\Facades\Storage;

echo "Starting migration of local images to Cloudinary...\n";

$files = Storage::disk('public')->allFiles();

foreach ($files as $file) {
    if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
        echo "Uploading $file...\n";
        try {
            $content = Storage::disk('public')->get($file);
            // Use storeAs to maintain the same path/name
            Storage::disk('cloudinary')->put($file, $content); 
            echo "Successfully uploaded $file to Cloudinary.\n";
        } catch (\Exception $e) {
            echo "Failed to upload $file: " . $e->getMessage() . "\n";
        }
    }
}

echo "Migration completed.\n";
