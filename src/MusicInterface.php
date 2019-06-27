<?php
/**
 * @author zhusaidong [zhusaidong@gmail.com]
 */

namespace zhusaidong\phpMusicApi;

interface MusicInterface
{
	/**
	 * get music url
	 *
	 * @return null|string
	 */
	public function getMusicUrl() : ?string;
	
	/**
	 * get lyrics url
	 *
	 * @return null|string
	 */
	public function getLyricsUrl() : ?string;
	
	/**
	 * get lyrics content
	 *
	 * @return null|string
	 */
	public function getLyricsContent() : ?string;
	
	/**
	 * get mv url
	 *
	 * @return null|string
	 */
	public function getMvUrl() : ?string;
	
	/**
	 * get music info
	 *
	 * @return null|array
	 */
	public function getMusicInfo() : ?array;
	
	/**
	 * get mv info
	 *
	 * @return null|array
	 */
	public function getMvInfo() : ?array;
	
	/**
	 * get playlist info
	 *
	 * @return null|array
	 */
	public function getPlaylistInfo() : ?array;
	
	/**
	 * get user playlist
	 *
	 * @return null|array
	 */
	public function getUserPlaylist() : ?array;
}
