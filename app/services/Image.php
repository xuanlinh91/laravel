<?php namespace App\Services;

use Config, File, Log;

class Image {

    /**
     * Instance of the Imagine package
     * @var Imagine\Gd\Imagine
     */
    protected $imagine;

    /**
     * Type of library used by the service
     * @var string
     */
    protected $library;

    /**
     * Initialize the image service
     * @return void
     */
    public function __construct()
    {
        if ( ! $this->imagine)
        {
            $this->library = Config::get('image.library', 'gd');

            // Now create the instance
            if     ($this->library == 'imagick') $this->imagine = new Intervention\Image\Facades\Image;
            elseif ($this->library == 'gmagick') $this->imagine = new \Imagine\Gmagick\Imagine();
            elseif ($this->library == 'gd')      $this->imagine = new \Imagine\Gd\Imagine();
            else                                 $this->imagine = new \Imagine\Gd\Imagine();
        }
    }

    /**
    * Upload an image to the public storage
    * @param  File $file
    * @return string
    */
    public function upload($file, $dir = null,$resize = false)
    {
        if ($file)
        {
            // Generate random dir
            if ( ! $dir) $dir = str_random(8);
            $size = getimagesize($file);
            $width = $size[0];
            $height = $size[1];

            // Get file info and try to move
            $destination = Config::get('image.upload_path') . $dir;
            $filename    = $file->getClientOriginalName();
            $path        = Config::get('image.upload_dir') . '/' . $dir . '/' . $filename;
            $uploaded    = $file->move($destination, $filename);

            if ($uploaded && $resize)
            {
                 $this->thumb($path, $width, $height);
            }
            return $path;
        }
    }

    /**
    * Resize an image
    * @param  string  $url
    * @param  integer $width
    * @param  integer $height
    * @param  boolean $crop
    * @return string
    */
    public function resize($url,$targetDirName ='resize',$filename_thumb = '', $width = null, $height = null, $quality = 90)
    {
        echo $url;
        if ($url)
        {
            // URL info
            $info = pathinfo($url);
            // The size
            if ( ! $height) $height = $width;

            // Quality
            $quality = Config::get('image.quality', $quality);

            // Directories and file names
            $fileName       = $info['basename'];
            $sourceDirPath  = public_path() . '/' . $info['dirname'];
            $sourceFilePath = $sourceDirPath . '/' . $fileName;
            $targetDirPath  = public_path() . '/' . $targetDirName . '/';
            $targetFilePath = $targetDirPath . $filename_thumb;
            $targetUrl      = asset(''. $targetDirName . '/' );
            $sourceFilePath = str_replace('\\', '/', $sourceFilePath);
            $targetFilePath = str_replace('\\', '/', $targetFilePath);
            if($width == null ){
                $img = getimagesize($sourceFilePath);
                $width = $img[0];
                $height = $img[1];
            }
            // Create directory if missing
            try
            {
                // Create dir if missing
                // if ( ! File::isDirectory($targetDirPath) and $targetDirPath) @File::makeDirectory($targetDirPath);

                // Set the size
                $size = new \Imagine\Image\Box($width, $height);

                // Now the mode
                $mode = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
                $array = explode('/', $targetDirName);
                $path = '';
                foreach ($array as $value) {
                    $path = $path.$value.'/';
                    if(!is_dir($path)) {
                        $Oldmask = umask ( 0 );
                        mkdir($path,0777);
                        umask ( $Oldmask );
                    }
                }
                $this->imagine->open($sourceFilePath)
                              ->thumbnail($size, $mode)
                              ->save($targetFilePath, array('quality' => $quality));
            }
            catch (\Exception $e)
            {
                echo '[IMAGE SERVICE] Failed to resize image "' . $url . '" [' . $e->getMessage() . ']';
                Log::error('[IMAGE SERVICE] Failed to resize image "' . $url . '" [' . $e->getMessage() . ']');
            }

            return array('targetUrl'=>$targetUrl,'targetFilePath'=>$targetDirName.'/' );
        }
    }

    /**
    * Resize an image
    * @param  string  $url
    * @param  integer $width
    * @param  integer $height
    * @param  boolean $crop
    * @return string
    */
    public function crop($url,$targetDirName = 'crop',$x = 0, $y=0, $width = null, $height = null, $quality = 90)
    {
        if ($url)
        {
            // URL info

            $info = pathinfo($url);

            // The size
            if ( ! $height) $height = $width;

            // Quality
            $quality = Config::get('image.quality', $quality);

            // Directories and file names
            $fileName       = $info['basename'];
            $sourceDirPath  = public_path() . '/' . $info['dirname'];
            $sourceFilePath = $sourceDirPath . '/' . $fileName;
            $targetDirPath  = public_path() . '/' . $targetDirName . '/';
            $targetFilePath = $targetDirPath . $fileName;
            $targetUrl      = asset(''.'/' . $targetDirName  . '/' );
            $sourceFilePath = str_replace('\\', '/', $sourceFilePath);
             if($width == null ){
                $img = getimagesize($sourceFilePath);
                $width = $img[0];
                $height = $img[1];
                $x = 0 ;
                $y = 0 ;
            }
            // Create directory if missing
            try
            {
                // Create dir if missing
                if ( ! File::isDirectory($targetDirPath) and $targetDirPath) @File::makeDirectory($targetDirPath);

                // Set the size
                $size = new \Imagine\Image\Box($width, $height);
                $point = new \Imagine\Image\Point($x, $y);

                // Now the mode
                $mode = \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND ;
                $array = explode('/', $targetDirName);
                $path = '';
                foreach ($array as $value) {
                    $path = $path .'/' .$value;
                    if(!is_dir($path)) {
                         $Oldmask = umask ( 0 );
                        mkdir($path,0777);
                        umask ( $Oldmask );
                    }
                }
                $this->imagine->open($sourceFilePath)
                              ->crop($point, $size)
                              ->save($targetFilePath, array('quality' => $quality));
                }
            catch (\Exception $e)
            {
                echo 'Có lỗi sảy ra vui lòng liên hệ quản trị viên để biết thêm chi tiết';

                return;
                Log::error('[IMAGE SERVICE] Failed to resize image "' . $url . '" [' . $e->getMessage() . ']');
            }

            return array('targetUrl'=>$targetUrl,'targetFilePath'=>$targetDirName . '/');
        }
    }
    /**
    * Creates image dimensions based on a configuration
    * @param  string $url
    * @param  array  $dimensions
    * @return void
    */
  /*  public function createDimensions($url, $dimensions = array())
    {
        // Get default dimensions
        $defaultDimensions = Config::get('image.dimensions');

        if (is_array($defaultDimensions)) $dimensions = array_merge($defaultDimensions, $dimensions);

        foreach ($dimensions as $dimension)
        {
            // Get dimmensions and quality
            $width   = (int) $dimension[0];
            $height  = isset($dimension[1]) ?  (int) $dimension[1] : $width;
            $crop    = isset($dimension[2]) ? (bool) $dimension[2] : false;
            $quality = isset($dimension[3]) ?  (int) $dimension[3] : Config::get('image.quality');

            // Run resizer
            $img = $this->resize($url, $width, $height, $crop, $quality);
        }
    }*/
    /**
    * Helper for creating thumbs
    * @param string $url
    * @param integer $width
    * @param integer $height
    * @return string
    */
    public function thumb($url, $width, $height = null)
    {
        return $this->resize($url, $width, $height, false);
    }

}