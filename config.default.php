<?php
return [
    'thumbers' => [
        'd' => [
            'debug' => 0,
            'url' => 'http://pic.gz99.cn',//图片的域名
            //0: redirect to error png | 1: redirect to error png with error url msg | 2: throw an exception
            'source_path' => '/Users/liaobinbin/opt/htdocs/youxuan_upload/', //配置原始图片上传目录
            'system_file_encoding' => 'UTF-8',
            'thumb_cache_path' => __DIR__ . '/thumb', //配置处理过的图片缓存目录
            'system_cache_path' => null,
            'adapter' => 'GD',
            //GD | Imagick | Gmagick
            'prefix' => 'thumb',
            //if no prefix, will use array key
            'allow_stretch' => false,
            'max_width' => 2000,
            'max_height' => 2000,
            'quality' => 80,
            'allow_extensions' => [],
            'allow_sizes' => [
                '200*200',
                '100*100',
                '313*313',
            ],
            // separator of class in url
            'class_separator' => '!',
            'classes' => array(
                'cover' => 'w_120,h_200'
            )
        ],
    ],
];