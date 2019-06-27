<?php
/**
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

namespace zhusaidong\phpMusicApi\Musics;

class NetEaseYun1Music extends NetEaseYunMusic
{
	/**
	 * get music url
	 *
	 * @return null|string
	 */
	public function getMusicUrl() : ?string
	{
		$url  = $this->baseUrl . 'song/enhance/player/url';
		$data = $this->getEncodeData([
			'ids' => [$this->getId()],
			'br'  => 320000,
		]);
		$data = $this->post($url, $data, []);
		
		$data = json_decode($data, TRUE);
		$data = $data['data'] ?? [];
		$data = current($data);
		$url  = $data['url'] ?? NULL;
		
		return $url;
	}
	
	/**
	 * get lyrics content
	 *
	 * @return null|string
	 */
	public function getLyricsContent() : ?string
	{
		$url  = 'http://music.163.com/api/song/lyric?os=pc&lv=1&tv=1&id=' . $this->getId();
		$data = $this->get($url);
		$data = json_decode($data, TRUE);
		
		return $data['lrc']['lyric'] ?? NULL;
	}
	
	/**
	 * get music info
	 *
	 * @return null|array
	 */
	public function getMusicInfo() : ?array
	{
		$url  = 'https://music.163.com/song?id=' . $this->getId();
		$data = $this->curlGet($url);
		
		preg_match(/* @lang=text */ '/<img src="(.*)" class="j-img" data-src="(?<pic>.*)">[\s\S]*<div class="tit">[\s\S]*<em class="f-ff2">(?<title>.*)<\/em>[\s\S]*<\/div>[\s\S]*<\/div>[\s\S]*<p class="des s-fc4">歌手：<span title="(.*)"><[span|a]* class="s-fc7"(.*)>(?<singer>.*)<\/[span|a]*><\/span><\/p>[\s\S]*<p class="des s-fc4">所属专辑：<a href="(.*)" class="s-fc7">(?<album>.*)<\/a><\/p>/iUs', $data, $match);
		
		return [
			'title'  => $match['title'] ?? NULL,
			'album'  => $match['album'] ?? NULL,
			'singer' => $match['singer'] ?? NULL,
			'pic'    => $match['pic'] ?? NULL,
		];
	}
}
