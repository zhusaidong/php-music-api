# php-music-api

php 音乐 api

## 安装

>   composer require zhusaidong/php-music-api

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
$musicApi->music($id = '');
$musicApi->mv($id = '');
$musicApi->playlist($id = '', bool $getSong = FALSE);
```
