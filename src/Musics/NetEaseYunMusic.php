<?php
/**
 * 网易云音乐
 * https://www.jianshu.com/p/069e88181488
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */
namespace zhusaidong\phpMusicApi\Musics;

use zhusaidong\phpMusicApi\BaseMusic;
use zhusaidong\phpMusicApi\MusicType;
use zhusaidong\phpMusicApi\MusicInterface;

class NetEaseYunMusic extends BaseMusic implements MusicInterface
{
	protected $baseUrl = 'https://music.163.com/weapi/';
	
	private function songId2mvId()
	{
		$url = 'https://music.163.com/song?id=' . $this->getId();
		
		$data = $this->curlGet($url);
		preg_match('/<a title="播放mv" href="\/mv\?id=(.*)"><i class="icn u-icn u-icn-2"><\/i><\/a>/', $data, $match);
		$mvId = $match[1] ?? '';
		
		$this->setId($mvId, MusicType::MV);
		
		return $this;
	}
	
	/**
	 * 加密参数
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	protected function getEncodeData($data)
	{
		$encrypt_key = '0CoJUm6Qyw8W8jud';
		$secretKey   = 'd3ZV7C6hzBYShnUt';
		$encSecKey   = '263459bb3d1f56d8fbf5d48b2d6aa96c730ba20dd8479f2cb79617eb8254b88caafb2123ba5c49832cb003c3bd89be04642606a7ba87a87f64e96684ace6d5150711407d7a19e5b4d04180e6bdf9f8177c7b78eba9e54dbd9a3c4131d53c9706401d9adc0da79258b3e78874b199eef29c189ba636ea7571433cf4e0f34cdf0a';
		
		$params = json_encode($data, JSON_UNESCAPED_UNICODE);
		$params = $this->getAESEncode($params, $encrypt_key);
		$params = $this->getAESEncode($params, $secretKey);
		
		return [
			'params'    => $params,
			'encSecKey' => $encSecKey,
		];
	}
	
	/**
	 * AES加密
	 *
	 * @param string $data   数据
	 * @param string $secret 秘钥
	 *
	 * @return string
	 */
	private function getAESEncode($data, $secret)
	{
		$aes_vi = '0102030405060708';
		
		return openssl_encrypt($data, 'AES-128-CBC', $secret, 0, $aes_vi);
	}
	
	/**
	 * get music url
	 *
	 * @return null|string
	 */
	public function getMusicUrl() : ?string
	{
		$url = 'http://music.163.com/song/media/outer/url?id=' . $this->getId() . '.mp3';
		
		$res = $this->interceptedLocationUrl($url);
		
		return strpos($res, '404') === FALSE ? $res : '';
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
		$url  = 'http://music.163.com/api/song/media?id=' . $this->getId();
		$data = $this->get($url);
		$data = json_decode($data, TRUE);
		
		return $data['lyric'] ?? NULL;
	}
	
	/**
	 * get mv url
	 *
	 * @return null|string
	 */
	public function getMvUrl() : ?string
	{
		$this->songId2mvId();
		$data = $this->getMvInfo();
		
		return $data['url'] ?? NULL;
	}
	
	/**
	 * get music info
	 *
	 * @return null|array
	 */
	public function getMusicInfo() : ?array
	{
		$url  = 'http://music.163.com/api/song/detail/?ids=[' . $this->getId() . ']';
		$data = $this->curlGet($url);
		$data = json_decode($data, TRUE);
		$data = current($data['songs'] ?? []);
		
		if($data['name'] == NULL)
		{
			$data['name'] = $data['bMusic']['name'] ?? NULL;
		}
		
		return [
			'title'  => $data['name'] ?? NULL,
			'album'  => $data['album']['name'] ?? NULL,
			'singer' => $data['artists'][0]['name'] ?? NULL,
			'pic'    => $data['album']['picUrl'] ?? NULL,
		];
	}
	
	/**
	 * get mv info
	 *
	 * @return null|array
	 */
	public function getMvInfo() : ?array
	{
		$url  = 'http://music.163.com/api/mv/detail?id=' . $this->getId(MusicType::MV);
		$data = $this->get($url);
		$data = json_decode($data, TRUE);
		$data = $data['data'] ?? [];
		
		return [
			'title'  => $data['name'] ?? NULL,
			'artist' => $data['artistName'] ?? NULL,
			'pic'    => $data['cover'] ?? NULL,
			'url'    => empty($data['brs']) ? NULL : $data['brs'][max(array_keys($data['brs']))],
		];
	}
	
	/**
	 * get playlist
	 *
	 * @return null|array
	 */
	public function getPlaylistInfo() : ?array
	{
		$url  = $this->baseUrl . 'playlist/detail';
		$data = $this->getEncodeData([
			'id'     => $this->getId(),
			'ids'    => [$this->getId()],
			'limit'  => 10000,
			'offset' => 0,
		]);
		$data = $this->post($url, $data);
		$data = json_decode($data, TRUE);
		
		$result = $data['result'] ?? [];
		$tracks = $result['tracks'] ?? [];
		
		$lists = [];
		foreach($tracks as $track)
		{
			$lists[] = [
				'song_id' => $track['id'],
				'title'   => $track['name'],
				'singer'  => implode(',', array_column($track['artists'], 'name')),
				'album'   => $track['album']['name'],
			];
		}
		
		return [
			'name'  => $result['name'] ?? '',
			'pic'   => $result['coverImgUrl'] ?? '',
			'lists' => $lists,
		];
	}
	
	/**
	 * get user playlist
	 *
	 * @return null|array
	 */
	public function getUserPlaylist() : ?array
	{
		$uid  = $this->getId(MusicType::USER_PLAYLIST);
		$url  = 'http://music.163.com/api/user/playlist/?offset=0&limit=1001&uid=' . $uid;
		$data = $this->get($url);
		$data = json_decode($data, TRUE);
		
		$playlist = $data['playlist'];
		
		$data = [];
		foreach($playlist as $list)
		{
			$data[] = [
				'id'              => $list['id'],
				'pic'             => $list['coverImgUrl'],
				'name'            => $list['name'],
				'description'     => $list['description'],
				'selfPlaylist'    => $list['userId'] == $uid,
				'ownUserId'       => $list['userId'],
				'musicCount'      => $list['trackCount'],//音乐数
				'playCount'       => $list['playCount'],//播放数
				'subscribedCount' => $list['subscribedCount'],//订阅数
			];
		}
		
		return $data;
	}
	
	/**
	 * search
	 *
	 * @param string $name
	 *
	 * @return array|null
	 */
	public function search($name = '')
	{
		$url  = 'http://music.163.com/api/search/pc?s=' . $name . '&offset=0&limit=10&type=1';
		$data = $this->get($url);
		$data = json_decode($data, TRUE);
		
		return $data['result']['songs'] ?? [];
	}
}
