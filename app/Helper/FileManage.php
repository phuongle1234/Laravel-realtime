<?php


namespace App\Helper;


use Illuminate\Support\Facades\Storage;

use Image;

class FileManage
{
    private $_driver;
    private $_mainPath;

    /**
     * FileManage constructor.
     * @param string $nameFile
     * @param null $dataFile
     * @param string $option
     * @param string|null $model
     * @param string $driver
     * @param string $mainPath
     */
    public function __construct(
                                    $driver = 'local',
                                    $mainPath = "file_chat"
                                )
    {
        $this->_driver = $driver;
        $this->_mainPath = $mainPath;
    }

    /**
     * @param null $path
     * @return string
     */

      
    public function upload($_file,$path = null, $_file_name = null)
    {

        if ($this->_driver == 's3') {
            $fileName = Storage::disk($this->_driver)->putFileAs($this->_mainPath, $_file, $name,'public');
            $path = env('AWS_URL');
            return $path . $fileName;
        }

        $_file = Storage::disk('public')->putFileAs($path, $_file, $_file_name );

        $img = Image::make("storage/" . $_avatar);

        $img->resize(298, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save();

        return $_file;

        // $fileName = Storage::disk('local')->put('file_chat', $_file);

    }

    public function delete($_path): string
    {
        return Storage::disk($this->_driver)->delete($_path);
    }


}
