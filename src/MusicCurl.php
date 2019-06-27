<?php
/**
 * Music Curl
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

namespace zhusaidong\phpMusicApi;

use zhusaidong\CurLite\Curl;
use zhusaidong\CurLite\Request;

trait MusicCurl
{
	/**
	 * get
	 *
	 * @param string $url url
	 *
	 * @return false|string
	 */
	protected function get(string $url) : string
	{
		return @file_get_contents($url);
	}
	
	/**
	 * curl get
	 *
	 * @param string $url
	 * @param array  $header
	 *
	 * @return string|null
	 */
	protected function curlGet(string $url, array $header = []) : ?string
	{
		$request         = new Request($url);
		$request->header = $header;
		$cl              = new Curl($request);
		$response        = $cl->getResponse();
		if($response->error === FALSE)
		{
			return $response->body;
		}
		else
		{
			return NULL;
		}
	}
	
	/**
	 * post
	 *
	 * @param       $url
	 * @param       $postData
	 * @param array $header
	 *
	 * @return string|null
	 */
	protected function post(string $url, $postData, array $header = []) : ?string
	{
		$request             = new Request($url, Request::METHOD_POST);
		$request->header     = $header;
		$request->postFields = $postData;
		$cl                  = new Curl($request);
		$response            = $cl->getResponse();
		if($response->error === FALSE)
		{
			return $response->body;
		}
		else
		{
			return NULL;
		}
	}
	
	/**
	 * intercepted location url
	 *
	 * @param       $url
	 * @param array $header
	 *
	 * @return string|null
	 */
	protected function interceptedLocationUrl(string $url, array $header = []) : ?string
	{
		$request                 = new Request($url);
		$request->header         = $header;
		$request->followLocation = 0;
		$cl                      = new Curl($request);
		$response                = $cl->getResponse();
		if($response->error === FALSE)
		{
			return $response->location;
		}
		else
		{
			return NULL;
		}
	}
}
