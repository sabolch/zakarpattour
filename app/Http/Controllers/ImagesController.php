<?php

namespace App\Http\Controllers;


use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

use Image;
use Intervention\Image\Exception\NotReadableException;

class ImagesController extends Controller
{
    public function store(Request $request)
    {
        $name = uniqid('img_') . '.jpg';
        $path = Storage::putFileAs(
            'post_images', $request->file('file'), $name
        );
        return response()->json(['success' => true, 'path' => $path, 'name' => $name], 200);
    }

    public static function get($name, $size = null)
    {
        try {
            $img = Image::make(storage_path('app' . DIRECTORY_SEPARATOR . 'post_images' . DIRECTORY_SEPARATOR . $name));
            if ($size) {
                $size = explode('x', $size);
                $img->resize($size[0], $size[1]);
            }
            return $img->response();

        } catch (NotReadableException $exception) {
            return Image::make(storage_path('app' . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR . 'default.jpg'))->response();
        }

    }

    public function upload(Request $request)
    {
        try {
            $type = $request->input('type');
            $file = $request->file('file');
            $id = (int)$request->input('id');

            $name = uniqid('img_') . '.' . $file->extension();
            $path = $type . DIRECTORY_SEPARATOR . $id;
            $path = Storage::putFileAs(
                $path, $file, $name
            );
            return response()->json(['image' => ['url' => config('app.url') . DIRECTORY_SEPARATOR . 'image'
                . DIRECTORY_SEPARATOR . 'show' . DIRECTORY_SEPARATOR
                . $type .DIRECTORY_SEPARATOR . $id , 'name'=>$name,'path' => $path]], 202);
        } catch (\Exception $e) {
            return response()->json([], 204);
        }
    }

    public function remove(Request $request)
    {
        try {
            Storage::delete($request->input('path'));
            return response()->json([], 202);
        } catch (\Exception $e) {
            return response()->json([], 204);
        }
    }


    public function titleImg($type, $id)
    {
        $img_path = storage_path('app' . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR
            . $id . DIRECTORY_SEPARATOR . 'title');
        if (is_dir($img_path)) {
            $filesInFolder = \File::files($img_path);
            $img = pathinfo($filesInFolder[0])['basename'];
            return Image::make($img_path . DIRECTORY_SEPARATOR . $img)->response();
        }
        return Image::make(storage_path('app') . DIRECTORY_SEPARATOR . 'default.jpg')->response();

    }

    public function setTitleImg(Request $request)
    {
        try {
            $path = $request->input('path');
            $id = $request->input('id');
            $type = $request->input('type');

            $title_path = storage_path('app' . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'title');
            $image_full_path = storage_path('app') . DIRECTORY_SEPARATOR . $path;

            if(is_dir($title_path)){
                \File::deleteDirectory($title_path);
            }
            $img = Image::make($image_full_path);
//            // resize the image
            $img->resize(600, 400, function ($constraint) {
                $constraint->aspectRatio();
            });
//            // create dir if not exist with sizes
            mkdir($title_path);
//            //save img
            $img->save($title_path . DIRECTORY_SEPARATOR . basename($image_full_path));

            return response()->json([], 200);
        } catch (\Exception $e) {
            return response()->json(['error'=>204], 204);
        }
    }

    public function collect($type, $id)
    {
        $path_to = $type . DIRECTORY_SEPARATOR . $id;
        $files = [];
        $filesInFolder = \File::files(storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . $path_to);
        foreach ($filesInFolder as $path) {
            $p = pathinfo($path)['basename'];
            $files[] = ['url' => config('app.url') . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR
                . 'show' . DIRECTORY_SEPARATOR . $path_to, 'name' => $p, 'path' => $path_to
                . DIRECTORY_SEPARATOR . $p];
        }
        return response()->json($files, 200);

    }

    public function showOriginal($type, $id, $name)
    {
        return $this->show($type, $id, null, $name);
    }

    public function show($type, $id, $size = null, $name)
    {
        try {
            $path = storage_path('app' . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR
                . $id . DIRECTORY_SEPARATOR . $size);
            $image_full_path = $path . DIRECTORY_SEPARATOR . $name;
            $img_path = storage_path('app' . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR
                . $id . DIRECTORY_SEPARATOR . $name);
            // Resize and store image
            if ($size && !file_exists($image_full_path)) {
                // make image from file
                $img = Image::make($img_path);
                $size = explode('x', $size);
                // resize the image
                $img->resize((int)$size[0], (int)$size[1], function ($constraint) {
//                    $constraint->aspectRatio();
                });
                // create dir if not exist with sizes
                if (!is_dir($path)) {
                    mkdir($path);
                }
                //save img
                $img->save($image_full_path);
                return $img->response();
            }
            // If resized return the resized img
            if ($size) {
                return Image::make($image_full_path)->response();
            }
            // if $size null return original
            return Image::make($img_path)->response();
        } catch (\Exception $e) {
            return Image::make($img_path)->response();
        }
    }

}
