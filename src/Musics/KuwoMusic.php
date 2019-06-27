<?php
/**
 * 酷我音乐
 * //搜索：http://search.kuwo.cn/r.s?SONGNAME={$song_name}&ft=music&rformat=json&encoding=utf8&rn=8&callback=song&vipver=MUSIC_8.0.3.1
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */
namespace zhusaidong\phpMusicApi\Musics;

use zhusaidong\phpMusicApi\BaseMusic;
use zhusaidong\phpMusicApi\MusicInterface;

class KuwoMusic extends BaseMusic implements MusicInterface
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
			$lrcUrl         = 'http://www.kuwo.cn/yinyue/' . $this->songId;
			$this->songData = $this->get($lrcUrl);
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
		$musicUrl = 'http://antiserver.kuwo.cn/anti.s?format=mp3|aac&rid=' . $this->songId . '&type=convert_url';
		
		return $this->get($musicUrl);
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
		$data = $this->getSongData();
		
		preg_match('/var lrcList = (.*) \|\|/', $data, $match);
		$lrcList    = $match[1] ?? '';
		$lrcList    = json_decode($lrcList, TRUE);
		$lrcContent = '';
		if(is_array($lrcList))
		{
			foreach($lrcList as $lrc)
			{
				$time      = $lrc['time'];
				$microtime = sprintf('%.2f', ($time - floor($time)));
				//[分钟:秒.毫秒] 歌词
				$lrcContent .= '[' . date('i:s', $time) . substr($microtime, 1) . ']' . $lrc['lineLyric'] . PHP_EOL;
			}
		}
		
		return $lrcContent;
	}
	
	/**
	 * get mv url
	 *
	 * @return null|string
	 */
	public function getMvUrl() : ?string
	{
		$mvUrl = 'http://www.kuwo.cn/yy/st/mvurl?rid=MUSIC_' . $this->songId;
		
		return str_replace('res not found', '', $this->get($mvUrl));
	}
	
	/**
	 * get music info
	 *
	 * @return null|array
	 */
	public function getMusicInfo() : ?array
	{
		$data = $this->getSongData();
		
		preg_match('/<p id="lrcName">(.*)<\/p>/', $data, $title);
		preg_match('/<p class="album">专辑：<span><a target="_blank" href="(.*)">(.*)<\/a><\/span><\/p>/', $data, $album);
		preg_match('/<p class="artist"(.*)>歌手：<span><a target="_blank" href="(.*)"(.*)>(?P<author>.*)<\/a><\/span><\/p>/', $data, $author);
		
		$data  = $this->get('http://www.kuwo.cn/webmusic/sj/dtflagdate?flag=6&rid=MUSIC_' . $this->songId);
		$datas = explode('#', $data);
		$datas = explode(',', $datas[0]);
		
		return [
			'title'  => $title[1] ?? NULL,
			'album'  => $album[2] ?? NULL,
			'singer' => $author['author'] ?? NULL,
			'pic'    => $datas[1] ?? NULL,
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
