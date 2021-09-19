<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Facades\Storage;

class ImagesController extends Controller
{
    public function getImage($name)
    {
        $name = Str::of($name)->basename();
        if(Storage::exists("products/$name")){
            $file = FacadesFile::get(storage_path("products/$name"));
            $mime = FacadesFile::mimeType(storage_path("products/$name"));
            return Response($file, 200, [
                'content-type' => $mime,
            ]);
        }
        else return abort(404);
    }
}
