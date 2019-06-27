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
	 * @var array $ids id list
	 */
	protected $ids = [];
	
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
		
		return $this->ids[$type] ?? 0;
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
		$this->ids[$type] = $id;
		
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
