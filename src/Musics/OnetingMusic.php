<?php
/**
 * 一听音乐
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */
namespace zhusaidong\phpMusicApi\Musics;

use zhusaidong\phpMusicApi\BaseMusic;
use zhusaidong\phpMusicApi\MusicInterface;

class OnetingMusic extends BaseMusic implements MusicInterface
{
	protected $songData = NULL;
	
	/**
	 * get song data
	 *
	 * @return mixed|null
	 */
	protected function getSongData()
	{
		if($this->songData == NULL)
		{
			$url  = 'https://www.1ting.com/player/1c/player_' . $this->getId() . '.html';
			$html = $this->get($url);
			
			preg_match('/\$YP\.create\(\[(.*)\]\);/', $html, $match);
			$json           = $match[1] ?? '';
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
		
		$array = $this->getSongData();
		
		$mp3url = $array[7] ?? NULL;
		
		if(empty($mp3url))
		{
			return NULL;
		}
		
		$mp3url = 'https://www.1ting.com/api/audio?' . $mp3url;
		
		$header = [
			'referer'=>'https://www.1ting.com/player/1c/player_' . $this->getId() . '.html',
		];
		$data   = $this->interceptedLocationUrl($mp3url, $header);
		
		return $data;
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
		//该接口`/api/geci/lrc/`不稳定，有可能失效
		$url = 'https://www.1ting.com/api/geci/lrc/' . $this->getId();
		$lrc = $this->get($url);
		if(strpos($lrc, 'error!') !== FALSE)
		{
			$url  = 'https://www.1ting.com/lrc' . $this->getId() . '.html';
			$html = $this->get($url);
			preg_match('/<div id="lrc">(.*)<\/div>/Us', $html, $match);
			$lrc = $match[1] ?? NULL;
		}
		
		return $lrc;
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
			'title'  => $array[3],
			'album'  => $array[5],
			'singer' => $array[1],
			'pic'    => $array[8],
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

