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
		
		$lyric = $data['lyric'] ?? NULL;
		
		return $lyric;
	}
	
	/**
	 * get mv url
	 *
	 * @return null|string
	 */
	public function getMvUrl() : ?string
	{
		$url = $this->baseUrl . 'song/enhance/play/mv/url';
		$this->songId2mvId();
		$data = $this->getEncodeData([
			'id' => $this->getId(MusicType::MV),
			'r'  => 1080,
		]);
		$data = $this->post($url, $data);
		
		$data = json_decode($data, TRUE);
		$url  = $data['data']['url'] ?? NULL;
		
		return $url;
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
		$url  = $this->baseUrl . 'song/enhance/play/mv/url';
		$data = $this->getEncodeData([
			'id' => $this->getId(),
			'r'  => 1080,
		]);
		$data = $this->post($url, $data);
		
		$data = json_decode($data, TRUE);
		
		return $data['data'];
	}
	
	/**
	 * get playlist
	 *
	 * @return null|array
	 */
	public function getPlaylist() : ?array
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
}
