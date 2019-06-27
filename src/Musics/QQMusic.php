<?php
/**
 * qq音乐
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */
namespace zhusaidong\phpMusicApi\Musics;

use zhusaidong\phpMusicApi\BaseMusic;
use zhusaidong\phpMusicApi\MusicInterface;
use zhusaidong\phpMusicApi\MusicType;

class QQMusic extends BaseMusic implements MusicInterface
{
	private function songmid2songid($mid)
	{
		$url  = 'https://y.qq.com/n/yqq/song/' . $mid . '.html';
		$data = $this->get($url);
		preg_match('/var g_SongData = (.*);/', $data, $match);
		$songData = $match[1] ?? '';
		$songData = json_decode($songData, TRUE);
		$songid   = $songData['songid'];
		
		return $songid;
	}
	
	/**
	 * musicu.fcg post
	 *
	 * @param $postData
	 *
	 * @return array
	 */
	private function musicuPost($postData)
	{
		$data = $this->post('http://u.y.qq.com/cgi-bin/musicu.fcg', json_encode($postData, JSON_UNESCAPED_UNICODE));
		$data = json_decode($data, TRUE);
		
		return current($data);
	}
	
	private function songmid2mvid($mid)
	{
		$post = [
			'mv' => [
				'module' => "MvService.MvInfoProServer",
				'method' => "GetMvBySongid",
				'param'  => [
					'mids' => [$mid],
				],
			],
		];
		$data = $this->musicuPost($post);
		
		$mvinfo = $data['data']['mvinfo'] ?? [];
		$vid    = $mvinfo[$mid]['vid'] ?? '';
		
		$this->setId($vid, MusicType::MV);
	}
	
	/**
	 * get music url
	 *
	 * @return null|string
	 */
	public function getMusicUrl() : ?string
	{
		$post = [
			'vkey' => [
				'module' => "vkey.GetVkeyServer",
				'method' => "CgiGetVkey",
				'param'  => [
					'guid'      => time() . rand(100, 999),
					'songmid'   => [$this->getId()],
					'songtype'  => [],
					'uin'       => '0',
					'loginflag' => '0',
					'platform'  => 23,
					'h5to'      => 'speed',
				],
			],
		];
		
		$data       = $this->musicuPost($post);
		
		$midurlinfo = $data['data']['midurlinfo'] ?? [];
		$midurlinfo = current($midurlinfo);
		$url        = $midurlinfo['purl'] ?? '';
		
		return 'http://dl.stream.qqmusic.qq.com/' . $url;
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
		$songid = $this->songmid2songid($this->getId());
		$post   = [
			'format'        => 'json',
			'inCharset'     => 'utf-8',
			'outCharset'    => 'utf-8',
			'notice'        => '0',
			'platform'      => 'h5',
			'needNewCode'   => '1',
			'nobase64'      => '1',
			'musicid'       => $songid,
			'songtype'      => '0',
			'_'             => '1536993003097',
			'jsonpCallback' => 'json',
		];
		$header = [
			'referer'    => 'https://i.y.qq.com/v8/playsong.html?ADTAG=newyqq.song&songmid=' . $this->getId(),
			'user-agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1',
		];
		$json   = $this->post('https://c.y.qq.com/lyric/fcgi-bin/fcg_query_lyric.fcg', $post, $header);
		
		$json  = substr($json, 5, -1);
		$data  = json_decode($json, TRUE);
		$lyric = $data['lyric'] ?? NULL;
		
		return $lyric != NULL ? html_entity_decode(str_replace(" [", "\n[", $lyric)) : NULL;
	}
	
	/**
	 * get lyrics content
	 *
	 * @return null|string
	 */
	public function getLyricsContent1() : ?string
	{
		$songid = $this->songmid2songid($this->getId());
		$post   = [
			'detail' => [
				'module' => 'music.pf_song_detail_svr',
				'method' => 'get_song_detail',
				'param'  => [
					'song_id' => $songid,
				],
			],
		];
		$data   = $this->musicuPost($post);
		
		$info       = $data['data']['info'] ?? [];
		$lyricsData = end($info);
		$lyrics     = $lyricsData['content'][0]['value'] ?? NULL;
		
		return $lyrics;
	}
	
	/**
	 * get mv url
	 *
	 * @return null|string
	 */
	public function getMvUrl() : ?string
	{
		$vid = $this->songmid2mvid($this->getId());
		
		$post = [
			'getMvUrl' => [
				'module' => 'gosrf.Stream.MvUrlProxy',
				'method' => 'GetMvUrls',
				'param'  => [
					'vids'          => [$this->getId(MusicType::MV)],
					'request_typet' => 10001,
				],
			],
		];
		$data = $this->musicuPost($post);
		
		$info = $data['data'][$vid]['mp4'] ?? [];
		//倒序取最大分辨率
		for($i = count($info) - 1; $i >= 0; $i--)
		{
			$freeflow_url = $info[$i]['freeflow_url'];
			if(!empty($freeflow_url))
			{
				return $freeflow_url[array_rand($freeflow_url)];
			}
		}
		
		return NULL;
	}
	
	/**
	 * get music info
	 *
	 * @return null|array
	 */
	public function getMusicInfo() : ?array
	{
		$post       = [
			'detail' => [
				'module' => 'music.pf_song_detail_svr',
				'method' => 'get_song_detail',
				'param'  => [
					'song_id' => $this->songmid2songid($this->getId()),
				],
			],
		];
		$data       = $this->musicuPost($post);
		
		$track_info = $data['data']['track_info'];
		
		return [
			'title'  => $track_info['name'],
			'album'  => $track_info['album']['name'],
			'singer' => implode(',', array_column($track_info['singer'], 'name')),
			'pic'    => 'https://y.gtimg.cn/music/photo_new/T002R500x500M000' . $track_info['album']['mid'] . '.jpg',
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
