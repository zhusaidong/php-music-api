<?php
/**
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

namespace zhusaidong\phpMusicApi\Musics;

class QianQian1Music extends QianQianMusic
{
	/**
	 * get lyrics content
	 *
	 * @return null|string
	 */
	public function getLyricsContent() : ?string
	{
		$url   = 'http://music.taihe.com/data/tingapi/v1/restserver/ting?method=baidu.ting.song.lry&songid=' . $this->songId;
		$data  = $this->get($url);
		$array = json_decode($data, TRUE);
		
		return $array['lrcContent'] ?? NULL;
	}
	
	/**
	 * get music info
	 *
	 * @return null|array
	 */
	public function getMusicInfo() : ?array
	{
		$url   = 'http://music.taihe.com/data/tingapi/v1/restserver/ting?method=baidu.ting.song.baseInfo&songid=' . $this->songId;
		$data  = $this->get($url);
		$array = json_decode($data, TRUE);
		$data  = $array['content'] ?? [];
		
		return [
			'title'  => $data['title'] ?? NULL,
			'singer' => $data['author'] ?? NULL,
			'album'  => $data['album_title'] ?? NULL,
			'pic'    => $data['pic_huge'] ?? NULL,
		];
	}
}
