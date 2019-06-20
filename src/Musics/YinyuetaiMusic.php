<?php
/**
 * 音悦台
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */
namespace zhusaidong\phpMusicApi\Musics;

use zhusaidong\phpMusicApi\BaseMusic;
use zhusaidong\phpMusicApi\MusicInterface;
use zhusaidong\phpMusicApi\MusicType;

class YinyuetaiMusic extends BaseMusic implements MusicInterface
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
			$url            = 'http://ext.yinyuetai.com/main/get-h-mv-info?json=true&videoId=' . $this->getId();
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
		return NULL;
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
		$array     = $this->getSongData();
		$videoUrls = $array['videoInfo']['coreVideoInfo']['videoUrlModels'] ?? [];
		$videoUrl  = end($videoUrls);
		$videoUrl  = $videoUrl['videoUrl'] ?? NULL;
		
		return $videoUrl;
	}
	
	/**
	 * get music info
	 *
	 * @return null|array
	 */
	public function getMusicInfo() : ?array
	{
		$data = $this->getSongData();
		
		$videoInfo = $data['videoInfo']['coreVideoInfo'] ?? [];
		
		return [
			'title'  => $videoInfo['videoName'] ?? NULL,
			'album'  => NULL,
			'singer' => $videoInfo['artistNames'] ?? NULL,
			'pic'    => $videoInfo['bigHeadImage'] ?? NULL,
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
