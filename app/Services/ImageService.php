<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use InterventionImage; // リサイズ

class ImageService
{
    /**
     * Update the specified resource in storage.
     * Illuminate\Http\UploadedFile
     * @param  Illuminate\Http\UploadedFile  $imageFile
     * @param  string  $folderName
     * @return string  $fileNameToStore
     */
    public static function upload($imageFile, $folderName)
    {
        $file = is_array($imageFile) ? $imageFile['image'] : $imageFile;
        // リサイズなし
        // Storage::putFile('public/' . $folderName, $imageFile);

        // リサイズあり
        $fileNameToStore = uniqid(rand().'_') . '.' . $file->extension();
        $resizedImage = InterventionImage::make($file)->resize(1920, 1080)->encode();
        Storage::put('public/' . $folderName . '/' . $fileNameToStore, $resizedImage); // ex) public/shops/xxxxx.png
        return $fileNameToStore;
    }
}
