<?php


namespace Thumb\Thumb;


class Url
{
    /**
     * @var string JzXNKTXKExTW,c_fill,h_313,w_313
     */
    /** @var string */
    protected $file;

    protected $crop;
    protected $height;
    protected $width;
    protected $extension;
    protected $filename;
    protected $imageWidth;
    protected $imageHeight;

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

    public function __construct($file)
    {
        $this->file = $file;

        //分解组成信息
        $this->parse();
    }

    public function parse()
    {
        $path = pathinfo($this->file);

        $this->extension = $path['extension'];
        $this->imagePath = $path['dirname'];//这个可能没有值
    }

    /**
     * 获取原始图片
     */
    public function getOriginal()
    {
        $filename = explode(',', $this->fileName);

        if ($this->imagePath) {
            $original = $this->imagePath . '/' . $filename[0] . '.' . $this->extension;
        } else {
            $original = $filename[0] . '.' . $this->extension;
        }

        return $original;
    }

    public function getWidth()
    {
        $filename = explode(',', $this->fileName);

    }

    public function getHeight()
    {

    }
}