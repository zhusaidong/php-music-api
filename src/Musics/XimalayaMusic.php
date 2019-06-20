<?php
/**
 * 喜马拉雅
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */
namespace zhusaidong\phpMusicApi\Musics;

use zhusaidong\phpMusicApi\BaseMusic;
use zhusaidong\phpMusicApi\MusicInterface;

class XimalayaMusic extends BaseMusic implements MusicInterface
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
			$url            = 'http://m.ximalaya.com/tracks/' . $this->getId() . '.json';
			$json           = $this->get($url);
			$array          = json_decode($json, TRUE);
			$this->songData = $array;
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
		$array   = $this->getSongData();
		$playurl = $array['play_path'] ?? NULL;
		
		return $playurl;
	}
	
	/**
	 * get lyrics url
	 *
	 * @return null|string
	 */
	public function getLyricsUrl() : ?string
	{
		return NULL;
	}
	
	/**
	 * get lyrics content
	 *
	 * @return null|string
	 */
	public function getLyricsContent() : ?string
	{
		$array = $this->getSongData();
		$url   = 'https://www.ximalaya.com/' . $array['category_name'] . '/' . $array['album_id'] . '/' . $array['id'];
		$html  = $this->get($url);
		
		preg_match('/"draft":"(.*)",/iUs', $html, $match);
		
		return $match[1] ?? NULL;
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
		$data = $this->getSongData();
		
		return [
			'title'  => $data['title'],
			'album'  => $data['album_title'],
			'singer' => $data['nickname'],
			'pic'    => $data['cover_url'],
		];
	}
	
	/**
	 * get mv info
	 *
	 * @return null|array
	 */
	public function getMvInfo() : ?array
	{
		return NULL;
	}
	
	/**
	 * get playlist
	 *
	 * @return null|array
	 */
	public function getPlaylist() : ?array
	{
		return NULL;
	}
}
