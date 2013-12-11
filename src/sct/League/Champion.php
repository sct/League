<?php

namespace sct\League;

class Champion {
	private $id;
	private $name;
	private $active;
	private $botEnabled;
	private $botMmEnabled;
	private $ranks = array();
	private $rankedPlayEnabled;

	public function __construct($id, $name)
	{
		$this->id = $id;
		$this->name = $name;

		return $this;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setActive($active)
	{
		$this->active = $active;

		return $this;
	}

	public function getActive()
	{
		return $this->active;
	}

	public function setBotEnabled($botEnabled)
	{
		$this->botEnabled = $botEnabled;

		return $this;
	}

	public function getBotEnabled()
	{
		return $this->botEnabled;
	}

	public function setBotMmEnabled($botMmEnabled)
	{
		$this->botMmEnabled = $botMmEnabled;

		return $this;
	}

	public function getBotMmEnabled()
	{
		return $this->botMmEnabled;
	}

	public function setRanks($attackRank, $defenseRank, $magicRank, $difficultyRank)
	{
		$this->ranks = array("attackRank" => $attackRank, "defenseRank" => $defenseRank,
						"magicRank" => $magicRank, "difficultyRank" => $difficultyRank);

		return $this;
	}

	public function getRanks()
	{
		return $this->ranks;
	}

	public function setRankedPlayEnabled($rankedPlayEnabled)
	{
		$this->rankedPlayEnabled = $rankedPlayEnabled;

		return $this;
	}

	public function getRankedPlayEnabled()
	{
		return $this->rankedPlayEnabled;
	}

}