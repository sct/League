<?php

namespace sct\League;

use Guzzle\Http\Client;

class League {

	private $key;
	private $region;
	private $client;

	public function __construct($key, $region = "na")
	{
		$this->key = $key;
		$this->region = $region;
		$this->client = new Client("http://prod.api.pvp.net/api/lol/" . $this->region . "/v1.1/");

		return $this;
	}

	public function getKey()
	{
		return $this->key;
	}

	public function setKey($key)
	{
		$this->key = $key;
	}

	public function getSummonerIdByName($name)
	{
		$summoner = $this->getSummonerByName($name);

		return $summoner['id'];
	}


	public function getSummonerByName($name)
	{
		$request = $this->client->get('summoner/by-name/' . $name . "?api_key=" . $this->key);
		return $request->send()->json();
	}

	public function getSummonerStats($summonerId, $type = "summary")
	{
		$request = $this->client->get('stats/by-summoner/' . $summonerId . "/" . $type . "?api_key=" . $this->key);
		return $request->send()->json();
	}

	public function getSummonerStatsByName($name, $type = "summary")
	{
		return $this->getSummonerStats($this->getSummonerIdByName($name), $type);
	}

}