# swoole-clip
这是一个基于Swoole/Imagin/Composer开发的简单实现CDN动态裁剪图片大小功能.
这个项目目前只用在个人项目上面,其它使用场景可能会有问题.


## 基本使用
#### 准备工作
- `git clone git@github.com:liaomars/swoole-clip.git`
到你的本地.

- 进入目录执行`composer update`更新安装依赖包.

- 执行 `php bootstrap`启动Swoole http服务.

下面再配置Nginx或Apache来把请求代理到Swoole http服务,这里以Nginx配置为例.

```
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
```
#### 配置
配置文件 `config.defautl.php`

```
    
 ```
#### url格式
http://pic.gz99.cn/thumb.php?file=201904/qSrZ9z182RJC,c_fill,h_100,w_100.jpg

201904/qSrZ9z182RJC.jpg 这个是你的原始图片

c_fill:表示填充裁剪

h_100: 表示裁剪成高100

w_100: 表示裁剪成宽100

201904/qSrZ9z182RJC,c_fill,h_100,w_100.jpg 表示把原始图片裁剪成宽100,高100的图片.


## 问题
- 已经生成的图片,还没有想到好的方法来处理,所以后面请求会再次生成覆盖
## 感谢
思路参考这了个优秀的项目[EvaThumber](https://github.com/AlloVince/EvaThumber)







