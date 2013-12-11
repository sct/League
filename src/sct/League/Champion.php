<?php

namespace sct\League;

class Champion {
	/**
	 * Champion ID
	 * 
	 * @var integer
	 */
	private $id;

	/**
	 * Champion Name
	 * 
	 * @var string
	 */
	private $name;

	/**
	 * Indicates if the champion is active.
	 * 
	 * @var boolean
	 */
	private $active;

	/**
	 * Bot enabled flag (for custom games).
	 * 
	 * @var boolean
	 */
	private $botEnabled;

	/**
	 * Bot Match Made enabled flag (for Co-op vs. AI games).
	 * 
	 * @var boolean
	 */
	private $botMmEnabled;

	/**
	 * Array of ranks for each stat type for this champion
	 * 
	 * @var array
	 */
	private $ranks = array();

	/**
	 * Ranked play enabled flag.
	 * 
	 * @var boolean
	 */
	private $rankedPlayEnabled;

	/**
	 * Create a champion instance using an ID and Name
	 * 
	 * @param integer $id   Champion ID
	 * @param string $name Champion Name
	 */
	public function __construct($id, $name)
	{
		$this->id = $id;
		$this->name = $name;

		return $this;
	}

	/**
	 * Returns the Champions ID
	 * 
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Returns the Champions Name
	 * 
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set the champion active boolean
	 * 
	 * @param boolean $active
	 */
	public function setActive($active)
	{
		$this->active = $active;

		return $this;
	}

	/**
	 * Returns the boolean which indicates if a champion is active
	 * 
	 * @return boolean
	 */
	public function getActive()
	{
		return $this->active;
	}

	/**
	 * Set the flag indicating if this champion is enabled as a bot
	 * 
	 * @param boolean $botEnabled
	 */
	public function setBotEnabled($botEnabled)
	{
		$this->botEnabled = $botEnabled;

		return $this;
	}

	/**
	 * Returns the boolean which indicates if this champion can be used as a bot
	 * 
	 * @return boolean
	 */
	public function getBotEnabled()
	{
		return $this->botEnabled;
	}

	/**
	 * Set the flag indicating if this champion can be used as a matchmaking bot
	 * 
	 * @param boolean $botMmEnabled
	 */
	public function setBotMmEnabled($botMmEnabled)
	{
		$this->botMmEnabled = $botMmEnabled;

		return $this;
	}

	/**
	 * Returns the boolean which indicates if this champion can be used as a matchmaking bot
	 * 
	 * @return boolean
	 */
	public function getBotMmEnabled()
	{
		return $this->botMmEnabled;
	}

	/**
	 * Set the stat ranks for this champion
	 * 
	 * @param integer $attackRank     Attack Rank
	 * @param integer $defenseRank    Defense Rank
	 * @param integer $magicRank      Magic Rank
	 * @param integer $difficultyRank Difficulty Rank
	 */
	public function setRanks($attackRank, $defenseRank, $magicRank, $difficultyRank)
	{
		$this->ranks = array("attackRank" => $attackRank, "defenseRank" => $defenseRank,
						"magicRank" => $magicRank, "difficultyRank" => $difficultyRank);

		return $this;
	}

	/**
	 * Returns an Array containing the stat ranks for this champion
	 * 
	 * @return Array
	 */
	public function getRanks()
	{
		return $this->ranks;
	}

	/**
	 * Sets the boolean which indicates if this champion is available in ranked play
	 * 
	 * @param boolean $rankedPlayEnabled
	 */
	public function setRankedPlayEnabled($rankedPlayEnabled)
	{
		$this->rankedPlayEnabled = $rankedPlayEnabled;

		return $this;
	}

	/**
	 * Returns the boolean indicating if this champion is available in ranked play
	 * 
	 * @return boolean
	 */
	public function getRankedPlayEnabled()
	{
		return $this->rankedPlayEnabled;
	}

}