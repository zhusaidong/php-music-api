<?php
/**
 * 蜻蜓fm
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */
namespace zhusaidong\phpMusicApi\Musics;

use zhusaidong\phpMusicApi\BaseMusic;
use zhusaidong\phpMusicApi\MusicInterface;

class QingtingMusic extends BaseMusic implements MusicInterface
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
			$url = 'http://i.qingting.fm/wapi/channels/' . $this->getId();
			
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
		
		return isset($array['data']['file_path']) ? 'http://od.qingting.fm/' . $array['data']['file_path'] : NULL;
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
		
		$channelId = explode('/', $this->getId(), 2);
		$url       = 'http://i.qingting.fm/wapi/channels/' . $channelId[0];
		
		$data  = $this->get($url);
		$album = json_decode($data, TRUE);
		
		$podcasters = $album['data']['podcasters'];
		
		return [
			'title'  => $array['data']['name'],
			'album'  => $album['data']['name'],
			'singer' => is_array($podcasters) ? implode(',', array_column($podcasters, 'name')) : '',
			'pic'    => $album['data']['img_url'],
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
