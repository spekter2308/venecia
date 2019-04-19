<?php

namespace App\Classes;
use App\Thumbnail;
use Illuminate\Support\Facades\DB;
use Image;

class MainPhoto{

    public static function SavePhoto($photo){
        // Get the file name original name
        // and encrypt it with sha1
//        $photoName = sha1 (
//            time() . $photo->getClientOriginalName()
//        );
//        $extension = $photo->getClientOriginalExtension();
//        $photoName = $photoName.'.'.$extension;
//        //dir to save photo
//        $baseDir = 'src/public/MainImages/';
//        //path to photo
//        $pathToPhoto = $baseDir.$photoName;
//        //save photo in our folder
//        $photo->move($baseDir, $photoName);
//        //make thumbnailPhoto
//        $thumbnailPhoto = new Thumbnail();
//        $thumbnailPhotoPath = $baseDir.'th/'.'th-'.$photoName;
//        $thumbnailPhoto->make($pathToPhoto,$thumbnailPhotoPath);
//        //insert into db photo
//        DB::table('main_page_images')->insert(
//            ['name' => $photoName, 'path' => $pathToPhoto,'thumbnail_path'=>$thumbnailPhotoPath]
//        );

        $thumbnailImage = Image::make($photo);

        $photoName = sha1 (
            time() . $photo->getClientOriginalName()
        );
        $extension = $photo->getClientOriginalExtension();
        $photoName = $photoName.'.'.$extension;
        //dir to save photo
        $baseDir = 'src/public/MainImages/';
        //path to photo
        $pathToPhoto = $baseDir.$photoName;

        $thumbnailPhotoPath = $baseDir.'th/'.'th-'.$photoName;


        $thumbnailImage->resize(1500, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $thumbnailImage->save($pathToPhoto, 60);
        $thumbnailImage->resize(200,200);
        $thumbnailImage->save($thumbnailPhotoPath ,60);

        DB::table('main_page_images')->insert(
            ['name' => $photoName, 'path' => $pathToPhoto,'thumbnail_path'=>$thumbnailPhotoPath]
        );
    }

}