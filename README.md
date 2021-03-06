# 炸毛通用模块

这里是可能是你使用[炸毛框架](https://github.com/zhamao-robot/zhamao-framework)非常需要的基础模块。

## 图片上传和下载
如果你使用的是 **酷Q Pro**，想通过炸毛框架来发送图片，则安装此模块后可以上传发送图片。

### 安装步骤
把 `src/Module/Tools/HttpImageTool.php` 拷贝进框架对应的文件夹里即可。

### 配置
此模块默认的图片文件夹是 `zm_data/images/`，如果有需要可自行修改 `FILE_PATH` 常量。

同时请求 url 为 `/images/{文件名}`，可根据需要修改 `@RequestMapping`。

更改 `config/global.php` 配置文件里面的 `http_reverse_link`，就是改成外部（酷Q）可以访问你的框架的地址。

### 使用
在配置好后，可以在其他功能模块的函数里面使用 CQ 码来发送图片。假设你在 `zm_data/images/` 下存了一张图片：
```php
/**
 * @CQCommand("图片")
 */
public function image() {
    // 这里相当于返回的是："[CQ:image,file=http://你的框架地址:端口/images/a.jpg]"
    return CQ::image(DataProvider::getFrameworkLink() . "/images/a.jpg");
}
```

### 下载消息中 CQ 码的图片
此方法会将消息内的前 4 张图片下载到 `FILE_PATH` 目录下，返回的是文件名数组。
```php
/**
 * @CQMessage()
 */
public function downloadImage() {
    $msg = $this->getMessage();
    $downloaded = HttpImageTool::downloadImageFromCQ($msg);
    return "已下载 " . count($downloaded) . " 张图片\n" . implode("\n", $downloaded);
}
```
