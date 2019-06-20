<?php
/**
 * 虾米音乐
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */
namespace zhusaidong\phpMusicApi\Musics;

use zhusaidong\phpMusicApi\BaseMusic;
use zhusaidong\phpMusicApi\MusicInterface;
use zhusaidong\phpMusicApi\MusicType;

class XiamiMusic extends BaseMusic implements MusicInterface
{
	private $songData = NULL;
	
	private function songid2vid()
	{
		$data = $this->getSongData();
		$mvId = $data['mvUrl'] ?? '';
		if(empty($mvId))
		{
			return NULL;
		}
		
		$data = $this->get('https://www.xiami.com/mv/' . $mvId);
		
		preg_match('/{vid:"(?P<vid>.*)"/U', $data, $match);
		$vId = $match['vid'] ?? '';
		
		$this->setId($vId, MusicType::MV);
	}
	
	private function localtion2musicUrl($location)
	{
		if($location == '')
		{
			return $location;
		}
		if(strpos($location, 'http://') !== FALSE)
		{
			return $location;
		}
		
		$arrayLength        = intval($location[0]);
		$location           = substr($location, 1);
		$lineLength         = floor(strlen($location) / $arrayLength);
		$lastLocationLength = strlen($location) % $arrayLength;
		
		$array = [];
		for($i = 0; $i < $arrayLength; $i++)
		{
			if($i < $lastLocationLength)
			{
				$array[$i] = substr($location, ($lineLength + 1) * $i, $lineLength + 1);
			}
			else
			{
				$array[$i] = substr($location, $lineLength * $i + $lastLocationLength, $lineLength);
			}
		}
		
		$url = '';
		for($i = 0; $i < $lineLength + 1; $i++)
		{
			for($j = 0; $j < $arrayLength; $j++)
			{
				if(isset($array[$j][$i]))
				{
					$url .= $array[$j][$i];
				}
			}
		}
		$url = urldecode($url);
		$url = str_replace(["^", "+"], ["0", " "], $url);
		
		return $url;
	}
	
	/**
	 * get song data
	 *
	 * @return mixed|null
	 */
	private function getSongData()
	{
		if($this->songData == NULL)
		{
			$url    = 'https://emumo.xiami.com/song/playlist/id/' . $this->getId() . '/cat/json';
			$header = [
				'referer' => 'https://emumo.xiami.com/play?ids=/song/playlist/id/' . $this->getId(),
			];
			$data   = $this->curlGet($url, $header);
			$array  = json_decode($data, TRUE);
			
			$this->songData = $array['data']['trackList'] ?? [];
			$this->songData = current($this->songData);
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
		$data     = $this->getSongData();
		$location = $data['location'] ?? '';
		
		return 'http:' . $this->localtion2musicUrl($location);
	}
	
	/**
	 * get lyrics url
	 *
	 * @return null|string
	 */
	public function getLyricsUrl() : ?string
	{
		$data      = $this->getSongData();
		$lyric_url = $data['lyric'] ?? '';
		
		$lyric = 'http:' . $lyric_url;
		
		return $lyric;
	}
	
	/**
	 * get lyrics content
	 *
	 * @return null|string
	 */
	public function getLyricsContent() : ?string
	{
		$data      = $this->getSongData();
		$lyric_url = $data['lyric'] ?? '';
		
		$lyric = $this->get('http:' . $lyric_url);
		
		return $lyric;
	}
	
	/**
	 * get mv url
	 *
	 * @return null|string
	 */
	public function getMvUrl() : ?string
	{
		$this->songid2vid();
		if(($vid = $this->getId(MusicType::MV)) == NULL)
		{
			return NULL;
		}
		
		return 'http://cloud.video.taobao.com/play/u//p/2/e/6/t/1/' . $vid . '.mp4';
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
			'title'  => $data['songName'] ?? NULL,
			'album'  => $data['album_name'] ?? NULL,
			'singer' => $data['singers'] ?? NULL,
			'pic'    => $data['album_pic'] ?? NULL,
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
