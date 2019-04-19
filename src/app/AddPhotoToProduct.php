<?php

namespace App;

use Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AddPhotoToProduct {

    /**
     * @var Product
     */
    protected $product;


    /**
     * The UploadedFile Instance.
     *
     * @var UploadedFile
     */
    protected $file;


    /**
     * Create a new AddPhotoToProduct form object.
     *
     * @param Product $product
     * @param UploadedFile $file
     * @param Thumbnail|null $thumbnail
     */
    public function __construct(Product $product, UploadedFile $file, Thumbnail $thumbnail = null) {
        $this->product = $product;
        $this->file = $file;
        //$this->thumbnail = $thumbnail ?: new Thumbnail;
    }


    /**
     * Process the form.
     */
    public function save() {

        // Attach the photo to the product.
        $photo = $this->product->addPhoto($this->makePhoto());

//        // move a file to the base directory with the file name.
//        $this->file->move($photo->baseDir(), $photo->name);
//
//        // Generate a photo thumbnail.
//        $this->thumbnail->make($photo->path, $photo->thumbnail_path);



        $thumbnailImage = Image::make($this->file);

        $thumbnailImage->resize(1000, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $thumbnailImage->save($photo->path, 60);
        $thumbnailImage->resize(200,200);
        $thumbnailImage->save($photo->thumbnail_path ,60);
    }


    /**
     * Make a new Photo Instance.
     *
     * @return ProductPhoto
     */
    protected function makePhoto() {
        return new ProductPhoto(['name' => $this->makeFilename()]);
    }


    /**
     * Make a Filename, based on the uploaded file.
     *
     * @return string
     */
    protected function makeFilename() {

        // Get the file name original name
        // and encrypt it with sha1
        $name = sha1 (
            time() . $this->file->getClientOriginalName()
        );

        // Get the extension of the photo.
        $extension = $this->file->getClientOriginalExtension();

        // Then set name = merge those together.
        return "{$name}.{$extension}";
    }

}