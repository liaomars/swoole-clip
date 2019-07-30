# swoole-clip
这是一个基于Swoole/Imagin/Composer开发的简单实现CDN动态裁剪图片大小功能.
这个项目目前只用在个人项目上面,其它使用场景可能会有问题.


##基本功能

### URL构成
http://pic.gz99.cn/thumb.php?file=201904/qSrZ9z182RJC,c_fill,h_100,w_100.jpg

201904/qSrZ9z182RJC.jpg 这个是你的原始图片

c_fill:表示填充裁剪

h_100: 表示裁剪成高100

w_100: 表示裁剪成宽100

201904/qSrZ9z182RJC,c_fill,h_100,w_100.jpg 表示把原始图片裁剪成宽100,高100的图片.


## Swoole + Nginx的配置
> Swoole虽说可以提供http服务,但是有些协议支持不够Nginx老牌项目的完善,通过Nginx代理到swoole这样结合两者的优点

"""
server 
{
    listen       端口;
    server_name  域名;
    
    location / {
        root 项目目录;
        index index.php index.html;
        if (!-e $request_filename) {
            proxy_pass http://127.0.0.1:9502; # http://127.0.0.1:9502是swoole的http请求地址
        }
    }
}
"""



## 感谢
思路参考这了个优秀的项目[EvaThumber](https://github.com/AlloVince/EvaThumber)



