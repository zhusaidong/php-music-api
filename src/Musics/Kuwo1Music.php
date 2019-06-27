<?php
/**
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

namespace zhusaidong\phpMusicApi\Musics;

class Kuwo1Music extends KuwoMusic
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
			$url  = 'http://player.kuwo.cn/webmusic/st/getNewMuiseByRid?rid=MUSIC_' . $this->getId();
			$data = $this->get($url);
			
			preg_match_all('/<(.*?)>(.*?)<\/(.*?)>/i', $data, $radio_json);
			$data = array_combine($radio_json[1] ?? [], $radio_json[2] ?? []);
			
			$this->songData = $data;
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
		$data = $this->getSongData();
		
		return 'http://' . $data['mp3dl'] . '/resource/' . $data['mp3path'];
	}
	
	/**
	 * get lyrics content
	 *
	 * @return null|string
	 */
	public function getLyricsContent() : ?string
	{
		$url = 'http://m.kuwo.cn/newh5/singles/songinfoandlrc?musicId=' . $this->getId();
		$data = $this->get($url);
		$data = json_decode($data, TRUE);
		
		$lrclist = $data['data']['lrclist'] ?? [];
		
		$lrc = '';
		foreach($lrclist as $val)
		{
			if($val['time'] > 60)
			{
				$time_exp = explode('.', round($val['time'] / 60, 4));
				$minute   = $time_exp[0] < 10 ? '0' . $time_exp[0] : $time_exp[0];
				$sec      = substr($time_exp[1], 0, 2) . '.' . substr($time_exp[1], 2, 2);
				$time     = '[' . $minute . ':' . $sec . ']';
			}
			else
			{
				$time = '[00:' . $val['time'] . ']';
			}
			$lrc .= $time . $val['lineLyric'] . "\n";
		}
		
		return $lrc;
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
			'title'  => $data['name'] ?? NULL,
			'album'  => NULL,
			'singer' => $data['singer'] ?? NULL,
			'pic'    => $data['artist_pic'] ?? NULL,
		];
	}
}
