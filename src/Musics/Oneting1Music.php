<?php
/**
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

namespace zhusaidong\phpMusicApi\Musics;

class Oneting1Music extends OnetingMusic
{
	/**
	 * get song data
	 *
	 * @return mixed|null
	 */
	protected function getSongData()
	{
		if($this->songData == NULL)
		{
			$url  = 'http://h5.1ting.com/touch/api/song?ids=' . $this->getId();
			$json = $this->get($url);
			
			$array          = json_decode($json, TRUE);
			$this->songData = current($array);
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
		
		$mp3url = $array['song_filepath'] ?? NULL;
		
		if(empty($mp3url))
		{
			return NULL;
		}
		
		$url  = 'https://h5.1ting.com/file?url=' . str_replace('.wma', '.mp3', $mp3url);
		$data = $this->interceptedLocationUrl($url);
		
		return $data;
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
			'title'  => $array['song_name'],
			'album'  => $array['album_name'],
			'singer' => $array['singer_name'],
			'pic'    => $array['album_cover'],
		];
	}
}
