<?php
/**
 * Skeleton
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */
namespace zhusaidong\phpMusicApi\Musics;

use zhusaidong\phpMusicApi\BaseMusic;
use zhusaidong\phpMusicApi\MusicInterface;

class SkeletonMusic extends BaseMusic implements MusicInterface
{
	/**
	 * get music url
	 *
	 * @return string
	 */
	public function getMusicUrl() : ?string
	{
		return NULL;
	}
	
	/**
	 * get lyrics url
	 *
	 * @return string
	 */
	public function getLyricsUrl() : ?string
	{
		return NULL;
	}
	
	/**
	 * get lyrics content
	 *
	 * @return string
	 */
	public function getLyricsContent() : ?string
	{
		return NULL;
	}
	
	/**
	 * get mv url
	 *
	 * @return string
	 */
	public function getMvUrl() : ?string
	{
		return NULL;
	}
	
	/**
	 * get music info
	 *
	 * @return array
	 */
	public function getMusicInfo() : ?array
	{
		return [
			'title'  => '',
			'album'  => '',
			'singer' => '',
			'pic'    => '',
		];
	}
	
	/**
	 * get mv info
	 *
	 * @return array
	 */
	public function getMvInfo() : ?array
	{
		return NULL;
	}
	
	/**
	 * get playlist
	 *
	 * @return array
	 */
	public function getPlaylist() : ?array
	{
		return NULL;
	}
}
