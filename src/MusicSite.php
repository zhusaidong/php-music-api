<?php
/**
 * Music Site
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

namespace zhusaidong\phpMusicApi;

use zhusaidong\phpMusicApi\Musics\AppechoMusic;
use zhusaidong\phpMusicApi\Musics\KgeMusic;
use zhusaidong\phpMusicApi\Musics\KugouMusic;
use zhusaidong\phpMusicApi\Musics\KuwoMusic;
use zhusaidong\phpMusicApi\Musics\LizhiMusic;
use zhusaidong\phpMusicApi\Musics\MiguMusic;
use zhusaidong\phpMusicApi\Musics\NetEaseYun1Music;
use zhusaidong\phpMusicApi\Musics\NetEaseYunMusic;
use zhusaidong\phpMusicApi\Musics\OnetingMusic;
use zhusaidong\phpMusicApi\Musics\QianQianMusic;
use zhusaidong\phpMusicApi\Musics\QingtingMusic;
use zhusaidong\phpMusicApi\Musics\QQMusic;
use zhusaidong\phpMusicApi\Musics\TaiheMusic;
use zhusaidong\phpMusicApi\Musics\XimalayaMusic;
use zhusaidong\phpMusicApi\Musics\YinyuetaiMusic;

class MusicSite
{
	const Appecho       = AppechoMusic::class;
	const Kge           = KgeMusic::class;
	const Kugou         = KugouMusic::class;
	const Kuwo          = KuwoMusic::class;
	const Migu          = MiguMusic::class;
	const NetEaseYun    = NetEaseYunMusic::class;
	const NetEaseYun1   = NetEaseYun1Music::class;
	const Oneting       = OnetingMusic::class;
	const QQ            = QQMusic::class;
	const QianQian      = QianQianMusic::class;
	const Qingting      = QingtingMusic::class;
	const Taihe         = TaiheMusic::class;
	const Ximalaya      = XimalayaMusic::class;
	const Yinyuetai     = YinyuetaiMusic::class;
	const Lizhi         = LizhiMusic::class;
}
