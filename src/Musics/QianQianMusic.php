<?php
/**
 * 千千音乐
 *    apis:
 *        http://play.taihe.com/data/music/songlink?songIds=
 *        http://musicapi.taihe.com/v1/restserver/ting?method=baidu.ting.song.playAAC&format=json&songid=
 *        http://music.taihe.com/data/tingapi/v1/restserver/ting?method=baidu.ting.song.baseInfo&songid=
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */
namespace zhusaidong\phpMusicApi\Musics;

use zhusaidong\phpMusicApi\BaseMusic;
use zhusaidong\phpMusicApi\MusicInterface;

class QianQianMusic extends BaseMusic implements MusicInterface
{
	private $songData = NULL;
	
	/**
	 * get song data
	 *
	 * @return mixed|null
	 */
	private function getSongData()
	{
		if($this->songData == NULL)
		{
			$url            = 'http://music.taihe.com/data/music/songlink?songIds=' . $this->getId();
			$data           = $this->get($url);
			$array          = json_decode($data, TRUE);
			$this->songData = current($array['data']['songList']);
		}
		
		return $this->songData;
	}
	
	/**
	 * get music url
	 *
	 * @return null|string
	 */
	public function getMusicUrl() : ?string
	{
		$array = $this->getSongData();
		
		return $array['songLink'] ?? NULL;
	}
	
	/**
	 * get lyrics url
	 *
	 * @return null|string
	 */
	public function getLyricsUrl() : ?string
	{
		$array = $this->getSongData();
		
		return $array['lrcLink'] ?? NULL;
	}
	
	/**
	 * get lyrics content
	 *
	 * @return null|string
	 */
	public function getLyricsContent() : ?string
	{
		$array  = $this->getSongData();
		$lrcurl = $array['lrcLink'] ?? NULL;
		
		return $this->get($lrcurl);
	}
	
	/**
	 * get mv url
	 *
	 * @return null|string
	 */
	public function getMvUrl() : ?string
	{
		return NULL;
	}
	
	/**
	 * get music info
	 *
	 * @return null|array
	 */
	public function getMusicInfo() : ?array
	{
		$array = $this->getSongData();
		
		return [
			'title'  => $array['songName'] ?? NULL,
			'singer' => $array['artistName'] ?? NULL,
			'album'  => $array['albumName'] ?? NULL,
			'pic'    => $array['songPicRadio'] ?? NULL,
		];
	}
	
	/**
	 * get mv info
	 *
	 * @return null|array
	 */
	public function getMvInfo() : ?array
	{
		$url = 'http://music.taihe.com/song/' . $this->getId();
		
		$data = $this->get($url);
		
		preg_match('/data\.push\((.*)\);/iUs', $data, $match);
		
		$json = $match[1] ?? '';
		
		$array = json_decode($json, TRUE);
		
		$file_link = $array['file_link'] ?? NULL;
		
		return $file_link;
	}
	
	/**
	 * get playlist
	 *
	 * @return null|array
	 */
	public function getPlaylistInfo() : ?array
	{
		return NULL;
	}
	
	/**
	 * get user playlist
	 *
	 * @return null|array
	 */
	public function getUserPlaylist() : ?array
	{
		return NULL;
	}
}
