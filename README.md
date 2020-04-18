# 炸毛通用模块

这里为一些可能是你非常需要的基础模块。

## 图片上传
如果你使用的是 **酷Q Pro**，想通过炸毛框架来发送图片，则安装此模块后可以上传发送图片。

### 安装步骤
把 `src/Module/Tools/HttpImageTool.php` 拷贝进框架对应的文件夹里即可。

### 配置
此模块默认的图片文件夹是 `zm_data/images/`，如果有需要可自行修改其他目录（`innerImage()` 方法）。

同时请求 url 为 `/images/{文件名}`，可根据需要修改其他的。

更改 `config/global.php` 配置文件里面的 `http_reverse_link`，就是改成外部（酷Q）可以访问你的框架的地址。

### 使用
在配置好后，可以在其他功能模块的函数里面使用 CQ 码来发送图片。假设你在 `zm_data/images/` 下存了一张图片：
```php
/**
 * @CQCommand("图片")
 */
public function image() {
    return CQ::image(DataProvider::getFrameworkLink()."/images/a.jpg");
}
```
