<?php


namespace Thumb\Thumb;

use Thumb\Config\Config;

/**
 * 参数处理
 * Class Parameters
 * @package Thumb\Thumb
 */
class Parameters
{
    protected $crop;
    protected $height;
    protected $width;
    protected $extension;
    protected $filename;
    protected $imageWidth;
    protected $imageHeight;
    protected $file;
    protected $filepath;

    protected $argMapping = array(
        'c' => 'crop',
        'h' => 'height',
        'w' => 'width',
    );

    protected $argDefaults = array(
        'crop' => 'crop',
        'height' => null,
        'quality' => 100,
        'width' => null,
    );

    protected $config;


    public function __construct(Config $config)
    {
        $this->config = $config;
    }


    public function getSourceFile()
    {
        if ($this->filepath) {
            $sourceFile = $this->filepath . '/' . $this->filename . '.' . $this->extension;
        } else {
            $sourceFile = $this->filename . '.' . $this->extension;
        }
        return $sourceFile;
    }

    public function fromString($file)
    {
        $fileInfo = pathinfo($file);
        $this->setExtension($fileInfo['extension']);
        $this->setFilepath($fileInfo['dirname']);
        $this->setFile($fileInfo['filename']);

        $fileArray = explode(',', $fileInfo['filename']);
        $fileArray = array_filter($fileArray);

        $filename = array_shift($fileArray);
        $this->setFilename($filename);

        $params = [];
        foreach ($fileArray as $val) {
            if (!$val)
                continue;

            if (strlen($val) < 3 || strpos($val, '_') !== 1) {
                continue;
            }

            $key = $val[0];

            if (isset($this->argMapping[$key])) {
                $arg = substr($val, 2);
                if ($arg !== '') {
                    $params[$this->argMapping[$key]] = $arg;
                }
            }
        }

        $this->fromArray($params);
        return $params;
    }


    public function fromArray(array $params)
    {
        if ($params) {
            foreach ($params as $k => $v) {
                $method = 'set' . ucfirst($k);
                if (method_exists($this, $method)) {
                    $this->$method($v);
                }
            }
        }
        $this->check();
    }

    public function setExtension($extension)
    {
        $this->extension = $extension;
        return $this;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFilepath($filePath)
    {
        $this->filepath = $filePath;
        return $this;
    }

    public function getFilepath()
    {
        return $this->filepath;
    }

    public function setCrop($crop)
    {
        $crops = ['crop', 'fill'];
        if (is_numeric($crop)) {
            $crop = (int)$crop;
        } elseif (is_string($crop) && in_array($crop, $crops)) {
            $crop = strtolower($crop);
        }
        $this->crop = $crop;
        return $this;
    }

    public function getCrop()
    {
        if ($this->crop) {
            return $this->crop;
        }
        return $this->crop = $this->argDefaults['crop'];
    }


    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function __set($name, $value)
    {

    }

    public function __get($name)
    {

    }

    public function setWidth($width)
    {
        $this->width = (int)$width;
        return $this;
    }

    public function setHeight($height)
    {
        $this->height = (int)$height;
        return $this;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    /**
     * 检查宽高是否在配置范围内.
     */
    protected function check()
    {
        $default = $this->argDefaults;
        $config = $this->config;

        $width = $this->width;
        $height = $this->height;

        $maxWidth = $config->max_width;
        $maxHeight = $config->max_height;

        $width = $width > $maxWidth ? $maxHeight : $width;
        $height = $height > $maxHeight ? $maxHeight : $height;


        $default['width'] = $width;
        $default['height'] = $height;

        //判断是不是在允许尺寸配置内
        $allowSize = $config->allow_sizes;
        if ($allowSize && count($allowSize) > 0) {
            $matched = false;
            foreach ($allowSize as $allow) {
                list($allowWidth, $allowHeight) = explode('*', $allow);

                if ($width && $width == $allowWidth && $height && $height == $allowHeight) {
                    $matched = true;
                    break;
                }
            }

            if ($matched === false) {
                $this->width = null;
                $this->height = null;
            }
        }

        $this->argDefaults = $default;
        return $this;
    }
}