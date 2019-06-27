<?php
/**
 * 全民k歌
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */
namespace zhusaidong\phpMusicApi\Musics;

use zhusaidong\phpMusicApi\BaseMusic;
use zhusaidong\phpMusicApi\MusicInterface;

class KgeMusic extends BaseMusic implements MusicInterface
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
			/*
			$url  = 'https://node.kg.qq.com/play?s=' . $this->getId();
			$data = $this->curlGet($url);
			preg_match('/window\.__DATA__ = (.*);/iUs', $data, $match);
			$match          = $match[1] ?? '';
			$array          = json_decode($match, TRUE);
			$this->songData = $array['detail'];
			*/
			
			$url            = 'https://kg.qq.com/cgi/kg_ugc_getdetail?shareid=' . $this->getId() . '&format=json&inCharset=utf8&outCharset=utf-8&v=4';
			$data           = $this->get($url);
			$array          = json_decode($data, TRUE);
			$this->songData = $array['data'];
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
		
		return !empty($array['playurl']) ? $array['playurl'] : ($array['playurl_video'] ?? NULL);
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
		$detail = $this->getSongData();
		
		return [
			'title'  => $detail['song_name'] ?? NULL,
			'album'  => '',
			'singer' => $detail['singer_name'] ?? NULL,
			'pic'    => $detail['fb_cover'] ?? NULL,
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
