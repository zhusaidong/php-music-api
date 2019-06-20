# php-music-api

**数据调用的是各网站的 API 接口，有的接口并不是开放的，随时可能失效，本项目相关代码仅供参考。**

## 安装

> composer require zhusaidong/php-music-api

## 用法

```php
require(__DIR__ . '/vendor/autoload.php');

use zhusaidong\phpMusicApi\MusicFactory;
use zhusaidong\phpMusicApi\MusicSite;

$musicApi = new MusicFactory(MusicSite::NetEaseYun);

$info = $musicApi->music(2188235);
var_dump($info);
```

## 支持站点

```php
MusicSite::Appecho
MusicSite::Kge
MusicSite::Kugou
MusicSite::Kuwo
MusicSite::Migu
MusicSite::NetEaseYun
MusicSite::Oneting
MusicSite::QQ
MusicSite::QianQian
MusicSite::Qingting
MusicSite::Taihe
MusicSite::Xiami
MusicSite::Ximalaya
MusicSite::Yinyuetai
MusicSite::Lizhi
```

## api

```php
$musicApi->music($id = '');//获取音乐
$musicApi->mv($id = '');//获取mv
$musicApi->playlist($id = '', bool $getSong = FALSE);//获取歌单
```

## 免责声明

1. 本站音频文件来自各网站接口，本站不会修改任何音频文件
2. 音频版权来自各网站，本站只提供数据查询服务，不提供任何音频存储和贩卖服务
