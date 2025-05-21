<?php 

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DigitalSignatureService
{
    public function saveSignature(string $base64Signature, string $directory = 'signatures')
    {
        // Extract the image data from the base64 string
        $imageData = explode(',', $base64Signature)[1];
        $imageData = base64_decode($imageData);
        
        // Generate a unique filename
        $filename = $directory . '/' . Str::uuid() . '.png';
        
        // Save the image
        Storage::put($filename, $imageData);
        
        return $filename;
    }
}