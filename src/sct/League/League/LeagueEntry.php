<?php

namespace sct\League\League;

class LeagueEntry
{
	public $isFreshBlood;
	public $isHotStreak;
	public $isInactive;
	public $isVeteran;
	public $lastPlayed;
	public $leagueName;
	public $leaguePoints;
	public $losses;
	public $playerOrTeamId;
	public $playerOrTeamName;
	public $queueType;
	public $rank;
	public $tier;
	public $timeUntilDecay;
	public $wins;

	public $miniSeries;

	public function __construct($properties)
	{
		foreach ($properties as $key => $value) {
			if (empty($value)) {
				$value = 0;
			}
			$this->{$key} = $value;
		}
	}
}