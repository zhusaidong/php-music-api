<?php
/**
 * Music
 *
 * @author zhusaidong <zhusaidong@gmail.com>
 */
declare(strict_types = 1);

namespace zhusaidong\phpMusicApi;

class MusicFactory
{
	private $musicObj = NULL;
	
	public function __construct($music = NULL)
	{
		if($music != NULL)
		{
			$this->setMusic($music);
		}
	}
	
	/**
	 * set music
	 *
	 * @param $music
	 *
	 * @return MusicFactory
	 */
	public function setMusic($music) : MusicFactory
	{
		$this->musicObj = new $music;
		
		return $this;
	}
	
	/**
	 * get data
	 *
	 * @param $id
	 * @param $type
	 *
	 * @return array|null
	 */
	private function get($id, $type) : ?array
	{
		if(!$this->musicObj instanceof MusicInterface)
		{
			return NULL;
		}
		
		call_user_func_array([$this->musicObj, 'setType'], [$type]);
		call_user_func_array([$this->musicObj, 'setId'], [$id, $type]);
		
		$infos = [
			MusicType::MUSIC    => [
				'musicUrl',
				'lyricsUrl',
				'lyricsContent',
				'mvUrl',
				'musicInfo',
			],
			MusicType::PLAYLIST => [
				'playlist',
			],
			MusicType::MV       => [
				'mvInfo',
			],
		];
		
		$res = [];
		foreach($infos[$type] ?? [] as $info)
		{
			$data = call_user_func_array([$this->musicObj, 'get' . ucfirst($info)], []);
			if(is_array($data))
			{
				$res = array_merge($res, $data);
			}
			else
			{
				$res[$info] = $data;
			}
		}
		ksort($res);
		
		return $res;
	}
	
	/**
	 * get music info
	 *
	 * @param string $id
	 *
	 * @return array|null
	 */
	public function music($id = '') : ?array
	{
		return $this->get($id, MusicType::MUSIC);
	}
	
	/**
	 * get mv
	 *
	 * @param string $id
	 *
	 * @return array|null
	 */
	public function mv($id = '') : ?array
	{
		return $this->get($id, MusicType::MV);
	}
	
	/**
	 * get playlist
	 *
	 * @param string $id
	 * @param bool   $getSong
	 *
	 * @return array|null
	 */
	public function playlist($id = '', bool $getSong = FALSE) : ?array
	{
		$data = $this->get($id, MusicType::PLAYLIST);
		
		if($getSong)
		{
			foreach($data['lists'] as &$value)
			{
				$value = $this->get($value['song_id'], MusicType::MUSIC);
			}
			unset($value);
		}
		
		return $data;
	}
}
