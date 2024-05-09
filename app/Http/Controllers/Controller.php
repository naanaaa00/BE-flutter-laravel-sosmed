<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage; // Menambahkan impor untuk menggunakan Storage
use Illuminate\Support\Facades\URL;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function saveImage($image, $disk = 'public')
    {
        if (!$image) {
            return null;
        }

        $filename = time() . '.png';

        // Menyimpan gambar
        Storage::disk($disk)->put($filename, base64_decode($image));

        return URL::to('/') . '/storage/' . $disk . '/' . $filename; // Menggunakan fungsi helper 'url' untuk membuat URL
    }
}
