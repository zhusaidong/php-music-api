<?php
/**
 * 酷狗音乐
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */
namespace zhusaidong\phpMusicApi\Musics;

use zhusaidong\phpMusicApi\BaseMusic;
use zhusaidong\phpMusicApi\MusicInterface;
use zhusaidong\phpMusicApi\MusicType;

class KugouMusic extends BaseMusic implements MusicInterface
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
			$url            = 'http://wwwapi.kugou.com/yy/index.php';
			$get            = [
				'r'        => 'play/getdata',
				'callback' => '',
				'hash'     => $this->getId(),
				'album_id' => '',
				'_'        => time() . rand(100, 999),
			];
			$data           = $this->get($url . '?' . http_build_query($get));
			$array          = json_decode($data, TRUE);
			$this->songData = $array;
		}
		
		return $this->songData;
	}
	
	private function mid2vid()
	{
		$data = $this->getSongData();
		$this->setId($data['data']['video_id'] ?? NULL, MusicType::MV);
	}
	
	/**
	 * get music url
	 *
	 * @return null|string
	 */
	public function getMusicUrl() : ?string
	{
		$data = $this->getSongData();
		
		return $data['data']['play_url'] ?? NULL;
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
		$url = 'http://m.kugou.com/app/i/krc.php?cmd=100&hash=' . $this->getId() . '&timelength=1';
		
		return $this->get($url);
	}
	
	/**
	 * get lyrics content
	 *
	 * @return null|string
	 */
	public function getLyricsContent1() : ?string
	{
		$data = $this->getSongData();
		
		return $data['data']['lyrics'] ?? NULL;
	}
	
	/**
	 * get mv url
	 *
	 * @return null|string
	 */
	public function getMvUrl() : ?string
	{
		$this->mid2vid();
		if(($mvId = $this->getId(MusicType::MV)) == NULL)
		{
			return NULL;
		}
		$mvUrl = 'http://www.kugou.com/mvweb/html/mv_' . $mvId . '.html';
		$data  = $this->get($mvUrl);
		
		preg_match('/mv_hash = "(.*)",/', $data, $match);
		$mv_hash = $match[1] ?? '';
		
		$apiurl = 'http://m.kugou.com/app/i/mv.php?cmd=100&hash=' . $mv_hash . '&ismp3=1&ext=mp4';
		$data   = $this->get($apiurl);
		$data   = json_decode($data, TRUE);
		
		$mvdata  = end($data['mvdata']);
		$downUrl = $mvdata['downurl'] ?? NULL;
		
		return $downUrl;
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
			'title'  => $data['data']['song_name'] ?? '',
			'album'  => $data['data']['album_name'] ?? '',
			'singer' => $data['data']['author_name'] ?? '',
			'pic'    => $data['data']['img'] ?? '',
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
