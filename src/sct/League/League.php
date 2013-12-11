<?php

namespace sct\League;

use Guzzle\Http\Client;

class League {

	/**
	 * Riot League of Legends API Key
	 * 
	 * @var string
	 */
	private $key;

	/**
	 * Region to work in
	 * 
	 * @var string
	 */
	private $region;

	/**
	 * Instance of Guzzle Client
	 * 
	 * @var Object
	 */
	private $client;

	/**
	 * Create instance of League object to work with the API
	 * 
	 * @param string $key    API Key
	 * @param string $region Region to work in
	 */
	public function __construct($key, $region = "na")
	{
		$this->key = $key;
		$this->region = $region;
		$this->client = new Client("http://prod.api.pvp.net/api/lol/" . $this->region . "/v1.1/");
	}

	/**
	 * Returns the current API Key
	 * 
	 * @return string API Key
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 * Returns the currently in-use Region
	 * 
	 * @return string Region
	 */
	public function getRegion()
	{
		return $this->region;
	}

	/**
	 * Request a Summoner ID by Name
	 * 
	 * @param  string $name Summoner Name
	 * 
	 * @return integer       Summoner ID
	 */
	public function getSummonerIdByName($name)
	{
		$summoner = $this->getSummonerByName($name);

		return $summoner['id'];
	}

	/**
	 * Request a Summoner object by Summoner name
	 * 
	 * @param  string $name Summoner Name
	 * 
	 * @return Array       Summoner Array
	 */
	public function getSummonerByName($name)
	{
		$request = $this->client->get('summoner/by-name/' . $name . "?api_key=" . $this->key);
		return $request->send()->json();
	}

	/**
	 * Request Summoner Stats
	 * 
	 * @param  integer $summonerId Summoner ID
	 * @param  string $type       Type of request. summary/ranked
	 * 
	 * @return Array             Array of requested data
	 */
	public function getSummonerStats($summonerId, $type = "summary")
	{
		$request = $this->client->get('stats/by-summoner/' . $summonerId . "/" . $type . "?api_key=" . $this->key);
		return $request->send()->json();
	}

	/**
	 * Request Summoner Stats by Summoner Name
	 * 
	 * @param  string $name Summoner Name
	 * @param  string $type Type of Request. summary/ranked
	 * 
	 * @return Array       Array of requested data
	 */
	public function getSummonerStatsByName($name, $type = "summary")
	{
		return $this->getSummonerStats($this->getSummonerIdByName($name), $type);
	}

	/**
	 * Request Champions
	 * 
	 * @return Array Champion Array
	 */
	public function getChampions()
	{
		$request =  $this->client->get('champion?api_key=' .  $this->key);
		return $request->send()->json();
	}

}