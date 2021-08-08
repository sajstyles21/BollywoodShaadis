<?php 

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait ImageUpload
{
    public function UserImageUpload($query) // Taking input image as parameter
    {
        $originName = $query->getClientOriginalName();
        $fileName = pathinfo($originName, PATHINFO_FILENAME);
        $folder = '';
        $image = $query;
        $extension = $query->getClientOriginalExtension();
        $fileName = $fileName.'_'.time().'.'.$extension;
        $file = $image->storeAs($folder, $fileName, 'articles');
        $url = Storage::disk('articles')->url($fileName);

        return $url; // Just return image
    }
}
