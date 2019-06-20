<?php
/**
 * BaseMusic
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */
declare(strict_types = 1);

namespace zhusaidong\phpMusicApi;

class BaseMusic
{
	use MusicCurl;
	/**
	 * @var string $type
	 */
	protected $type = MusicType::MUSIC;
	/**
	 * @var mixed $songId
	 */
	protected $songId = '';
	/**
	 * @var string $mvId
	 */
	protected $mvId = '';
	/**
	 * @var string $playlistId
	 */
	protected $playlistId = '';
	/**
	 * @var string|array $host
	 */
	public static $host = '';
	
	/**
	 * BaseMusic constructor.
	 *
	 * @param string|null $songId
	 * @param string|null $type
	 */
	public function __construct(?string $songId = '', ?string $type = MusicType::MUSIC)
	{
		$this->type = $type;
		$this->setId($songId, $type);
	}
	
	/**
	 * get id
	 *
	 * @param string|null $type
	 *
	 * @return mixed|string
	 */
	public function getId(?string $type = NULL)
	{
		$type == NULL and $type = $this->type;
		switch($type)
		{
			case MusicType::MUSIC:
				return $this->songId;
			case MusicType::PLAYLIST:
				return $this->playlistId;
			case MusicType::MV:
				return $this->mvId;
			default:
				return $this->songId;
		}
	}
	
	/**
	 * set id
	 *
	 * @param string      $id
	 * @param string|null $type
	 *
	 * @return $this
	 */
	public function setId(?string $id, ?string $type = NULL)
	{
		$type == NULL and $type = $this->type;
		switch($type)
		{
			case MusicType::MUSIC:
				$this->songId = $id;
				break;
			case MusicType::PLAYLIST:
				$this->playlistId = $id;
				break;
			case MusicType::MV:
				$this->mvId = $id;
				break;
			default:
				$this->songId = $id;
				break;
		}
		
		return $this;
	}
	
	/**
	 * @param string $type
	 *
	 * @return BaseMusic
	 */
	public function setType(string $type) : BaseMusic
	{
		$this->type = $type;
		
		return $this;
	}
}
