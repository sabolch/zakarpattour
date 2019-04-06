<?php

namespace App\Http\Controllers;


use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Image;
use Intervention\Image\Exception\NotReadableException;

class ImagesController extends Controller
{
    public function store(Request $request)
    {
        $name =  uniqid('img_') . '.jpg';
        $path = Storage::putFileAs(
            'post_images', $request->file('file'),$name
        );
        return response()->json(['success' =>  true,'path'=>$path,'name'=>$name], 200);
    }

    public static function get($name,$size = null)
    {
        try {
            $img = Image::make(storage_path('app' . DIRECTORY_SEPARATOR . 'post_images' . DIRECTORY_SEPARATOR . $name ));
            if ($size){
                $size = explode('x',$size);
                $img->resize($size[0], $size[1]);
            }
            return $img->response();

        } catch (NotReadableException $exception) {
            return Image::make(storage_path('app' . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR . 'default.jpg'))->response();
        }

    }
}
