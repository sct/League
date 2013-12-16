<?php

namespace sct\League\League;

class League
{
	/**
	 * Name of the League
	 * 
	 * @var string
	 */
	public $name;

	/**
	 * Name of the Queue Type
	 * 
	 * @var string
	 */
	public $queue;

	/**
	 * Name of the Tier
	 * 
	 * @var string
	 */
	public $tier;

	/**
	 * Timestamp
	 * 
	 * @var string
	 */
	public $timestamp;

	/**
	 * Array containing LeagueEntry objects
	 * 
	 * @var array
	 */
	private $entries = array();

	/**
	 * Create instance of the League object
	 * 
	 * @param integer $summonerId Summoner ID
	 * @param array $league     API Response
	 */
	public function __construct($summonerId, $league)
	{
		$league = $league[$summonerId];
		$this->name = $league['name'];
		$this->queue = $league['queue'];
		$this->tier = $league['tier'];
		$this->timestamp = $league['timestamp'];
		$this->loadEntries($league['entries']);
	}

	/**
	 * Get the list of entries
	 * 
	 * @return array
	 */
	public function getEntries()
	{
		return $this->entries;
	}

	/**
	 * Load the entries into the entries array
	 * 
	 * @param array $entries
	 */
	private function loadEntries($entries)
	{
		foreach ($entries as $entry) {
			array_push($this->entries, new LeagueEntry($entry));
		}
	}
}