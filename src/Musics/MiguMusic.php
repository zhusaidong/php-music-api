<?php
/**
 * 咪咕音乐
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */
namespace zhusaidong\phpMusicApi\Musics;

use zhusaidong\phpMusicApi\BaseMusic;
use zhusaidong\phpMusicApi\MusicInterface;

class MiguMusic extends BaseMusic implements MusicInterface
{
	/**
	 * get music url
	 *
	 * @return null|string
	 */
	public function getMusicUrl() : ?string
	{
		$url   = 'http://music.migu.cn/v3/api/music/audioPlayer/getPlayInfo?copyrightId=' . $this->getId();
		$data  = $this->get($url);
		$array = json_decode($data, TRUE);
		
		return $array['walkmanInfo']['playUrl'] ?? NULL;
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
		$url   = 'http://music.migu.cn/v3/api/music/audioPlayer/getLyric?copyrightId=' . $this->getId();
		$data  = $this->get($url);
		$array = json_decode($data, TRUE);
		
		return $array['lyric'] ?? '';
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
		$url  = 'http://music.migu.cn/v3/music/song/' . $this->songId;
		$data = $this->get($url);
		
		preg_match("/data-share=\'(?P<musicInfoJson>.*)\'/iUs", $data, $match);
		
		$musicInfoJson = isset($match['musicInfoJson']) ? $match['musicInfoJson'] : NULL;
		$musicInfo     = json_decode($musicInfoJson, TRUE);
		
		return [
			'title'  => $musicInfo['title'] ?? NULL,
			'album'  => $musicInfo['album'] ?? NULL,
			'singer' => $musicInfo['singer'] ?? NULL,
			'pic'    => isset($musicInfo['imgUrl']) ? 'http:' . $musicInfo['imgUrl'] : NULL,
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
