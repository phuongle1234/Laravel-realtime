<?php

namespace App\Traits;

use App\Helper\FileManage;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use Image;
use Throwable;

trait StoreFileTrait {


    private $_driver = 's3';


    public function storageUpload($_file, $path = null, $_file_name = null)
    {

        if( $_file->getClientOriginalExtension() == 'pdf' )
        {
            $_file_name = $_file_name != null ? $_file_name : $_file->getClientOriginalName();
            $_file_stogage = Storage::disk($this->_driver)->putFileAs($path, $_file, $_file_name,'public');
            return Storage::disk( $this->_driver )->url($_file_stogage );
        }

        $_file = Image::make($_file)->resize( 500 , null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->encode('png', 60 )->stream();

        Storage::disk($this->_driver)->put($path."/".$_file_name, $_file ,'public');

        return Storage::disk( $this->_driver )->url( $path."/".$_file_name );
    }

    // private function uploadFileS3($path, $_file, $_file_name){


    //     Storage::disk($this->_driver)->put($path."/".$_file_name, $image ,'public');
    //     return Storage::disk( $this->_driver )->url( $path."/".$_file_name );

    // }

    public function storageDelete($_path): string
    {
        return Storage::disk($this->_driver)->delete($_path);
    }

}
