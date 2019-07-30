<?php

namespace Thumb;

use Imagine;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Box;
use Imagine\Image\Color;
use Imagine\Image\Point;
use mysql_xdevapi\Exception;
use Thumb\Config\Config;
use Thumb\Thumb\Parameters;

class Thumber
{
    /** @var $parameter Parameters */
    protected $parameter;

    /** @var $config Config */
    protected $config;

    protected $sourceFile;

    protected $image;

    protected $thumber;

    public $file;

    public function __construct($config, $file)
    {
        $this->parseConfig($config);
        $this->file = $file;
    }


    public function parseConfig($config)
    {
        if (!$config instanceof Config) {
            $config = new Config($config);
        }
        $defaultConfig = $config->thumbers->current();//获取当前数组的key
        if (!$defaultConfig) {
            throw new \Exception('setting is valid.');
        }

        $this->config = $defaultConfig;
        unset($defaultConfig);
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getParameter()
    {
        if ($this->parameter) {
            return $this->parameter;
        }
        $parameter = new Parameters($this->config);
        $parameter->fromString($this->file);
        $this->parameter = $parameter;
        return $this->parameter;
    }

    public function show()
    {
        $this->process();

        //保存图片


        //显示图片
//        $this->showImage($extension);

        $file = $this->save();
        return $file;
    }

    protected function showImage($extension)
    {
        return $this->showNormalImage($extension);
    }

    protected function showNormalImage($extension)
    {
        return $this->image->show($extension);
    }

    protected function process()
    {
        /** @var  $parameter Parameters */
        $parameter = $this->getParameter();

        //原始图片
        $sourceFile = $parameter->getSourceFile();
        $sourceFile = $this->getSourceFile($sourceFile);


        $thumber = $this->getThumber($sourceFile, $this->config->adapter);

        $this->resize();
    }


    protected function getSourceFile($sourceFile)
    {
        return $this->config->source_path . '/' . $sourceFile;
    }

    protected function resize()
    {
        $this->resizeBySize();
        return $this;
    }

    protected function resizeBySize()
    {
        $width = $this->parameter->getWidth(); //目标宽
        $height = $this->parameter->getHeight(); //目标高度

        $maxWidth = $this->config->max_width;
        $maxHeight = $this->config->max_height;

        $image = $this->image;

        $imageWidth = $image->getSize()->getWidth();
        $imageHeight = $image->getSize()->getHeight();

        var_dump($imageWidth, $imageHeight);

        //No size input, require size limit from config
        if (!$width && !$height) {
            if (!$maxWidth && !$maxHeight) {
                return $this;
            }

            if ($maxWidth && $imageWidth > $maxWidth || $maxHeight && $imageHeight > $maxHeight) {
                $width = $maxWidth && $imageWidth > $maxWidth ? $maxWidth : $width;
                $height = $maxHeight && $imageHeight > $maxHeight ? $maxHeight : $height;

                //If only width or height, resize by image size radio
                $width = $width ? $width : ceil($height * $imageWidth / $imageHeight);
                $height = $height ? $height : ceil($width * $imageHeight / $imageWidth);
            } else {
                return $this;
            }

        } else {
            if ($width === $imageWidth || $height === $imageHeight) {
                return $this;
            }

            //If only width or height, resize by image size radio
            $width = $width ? $width : ceil($height * $imageWidth / $imageHeight);
            $height = $height ? $height : ceil($width * $imageHeight / $imageWidth);

            $allowStretch = $this->config->allow_stretch;

            if (!$allowStretch) {
                $width = $width > $maxWidth ? $maxWidth : $width;
                $width = $width > $imageWidth ? $imageWidth : $width;
                $height = $height > $maxHeight ? $maxHeight : $height;
                $height = $height > $imageHeight ? $imageHeight : $height;
            }
        }

        //实例化 image box对象
        var_dump($width, $height);
        $size = new Imagine\Image\Box($width, $height);
        $crop = $this->parameter->getCrop();
        if ($crop === 'fill') {
            $mode = Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;
        } else {
            $mode = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        }
        //生成缩略图
        $this->image = $image->thumbnail($size, $mode);
        return $this;
    }

    public function save()
    {
        $parameter = $this->getParameter();

        //获取缓存图片目录
        $cachePath = $this->config->thumb_cache_path;

        //获取原始图片目录
        $originalFilepath = $parameter->getFilepath();

        $extension = $parameter->getExtension();

        //获取请求图片名
        $file = $parameter->getFile();

        $path = $cachePath . DIRECTORY_SEPARATOR . $originalFilepath;
        if ($originalFilepath) {
            if (!is_dir($path)) {
                mkdir($cachePath . DIRECTORY_SEPARATOR . $originalFilepath, 0777, true);
            }
        }

        var_dump($originalFilepath, $file, basename($cachePath));

        $image = $this->getImage();
        $file = $file . '.' . $extension;
        $image->save($path . DIRECTORY_SEPARATOR . $file);
        return $this->config->url . '/' . basename($cachePath) . '/' . $originalFilepath . $file;
    }

    protected function setSourceFile($sourceFile)
    {
        $this->sourceFile = $sourceFile;
    }


    public function getThumber($sourceFile = null, $adapter = null)
    {
        if ($this->thumber) {
            return $this->thumber;
        }

        $thumber = $this->createThumber($adapter);

        if ($sourceFile) {
            $this->image = $thumber->open($sourceFile);
        }

        return $this->thumber = $thumber;
    }

    public function getImage()
    {
        return $this->image;
    }

    protected function createThumber($adapter = null)
    {
        $adapter = $adapter ? $adapter : strtolower($this->config->adapter);
        switch ($adapter) {
            case 'gd':
                $thumber = new Imagine\Gd\Imagine();
                break;
            case 'imagick':
                $thumber = new Imagine\Imagick\Imagine();
                break;
            case 'gmagick':
                $thumber = new Imagine\Gmagick\Imagine();
                break;
            default:
                $thumber = new Imagine\Gd\Imagine();
        }
        return $thumber;
    }
}