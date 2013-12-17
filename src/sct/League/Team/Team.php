<?php

namespace sct\League\Team;

class Team
{
	private $teamId;
	private $matchHistory;
	private $messageOfDay;
	private $roster;
	private $teamStatSummary;

	public $name;
	public $tag;
	public $timestamp;
	public $createDate;
	public $lastGameDate;
	public $lastJoinDate;
	public $lastJoinedRankedTeamQueueDate;
	public $modifyDate;
	public $secondLastJoinDate;
	public $thirdLastJoinDate;
	public $status;

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