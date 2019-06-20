<?php
/**
 * echo回声
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */
namespace zhusaidong\phpMusicApi\Musics;

use zhusaidong\phpMusicApi\BaseMusic;
use zhusaidong\phpMusicApi\MusicInterface;

class AppechoMusic extends BaseMusic implements MusicInterface
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
			$url            = 'http://www.app-echo.com/api/sound/info?id=' . $this->songId;
			$data           = $this->get($url);
			$array          = json_decode($data, TRUE);
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
		$array = $this->getSongData();
		
		return $array['info']['source'] ?? NULL;
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
		return NULL;
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
			'title'  => $array['info']['name'],
			'pic'    => $array['info']['pic'],
			'singer' => NULL,
			'album'  => NULL,
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
