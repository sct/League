<?php

namespace sct\League\Common;

class GameType
{
    const AramUnranked5x5 	= "AramUnranked5x5";
    const CoopVsAI 			= "CoopVsAI";
    const OdinUnranked		= "OdinUranked";
    const RankedPremade3x3  = "RankedPremade3x3";
    const RankedPremade5x5  = "RankedPremade5x5";
    const RankedSolo5x5		= "RankedSolo5x5";
    const RankedTeam3x3		= "RankedTeam3x3";
    const RankedTeam5x5		= "RankedTeam5x5";
    const Unranked 			= "Unranked";
    const Unranked3x3		= "Unranked3x3";
    const OneForAll5x5      = "OneForAll5x5";
    const FirstBlood1x1     = "FirstBlood1x1";
    const FirstBlood2x2     = "FirstBlood2x2";

    /**
     * Reference to the summoner this GameType belongs to
     * 
     * @var object
     */
    private $summoner;

    /**
     * GameType
     * 
     * @var string
     */
    private $playerStatSummaryType;

    /**
     * Number of wins
     * 
     * @var integer
     */
    public $wins;

    /**
     * Number of losses
     * 
     * @var integer
     */
    public $losses;

    /**
     * Last modified date as timestamp
     * 
     * @var integer
     */
    public $modifyDate;

    /**
     * Array of aggregated stats for this GameType
     * 
     * @var array
     */
    private $aggregatedStats = array();

    /**
     * Create the GameType object with reference to summoner and stats array
     * 
     * @param object $summoner Summoner Object
     * @param array $stats    Stats Array
     */
    public function __construct(&$summoner, &$properties)
    {
    	//$this->summoner = $summoner;

    	foreach ($properties as $key => $value) {
            if ($key != "aggregatedStats") {
                if (empty($value)) {
                    $value = 0;
                }
                $this->{$key} = $value;
            } else {
                $this->aggregatedStats = new AggregatedStats($value);
            }
        }
    }

    /**
     * Returns the instance of the summoner this GameType belongs to
     * 
     * @return object Summoner
     */
    public function getSummoner()
    {
    	return $this->summoner;
    }

    /**
     * Returns the GameType in string format
     * 
     * @return string GameType
     */
    public function getType()
    {
    	return $this->type;
    }

    /**
     * Returns the number of wins
     * 
     * @return integer
     */
    public function getWins()
    {
        return $this->wins;
    }

    /**
     * Returns the number of losses
     * 
     * @return integer
     */
    public function getLosses()
    {
        return $this->losses;
    }

    /**
     * Returns the last modified date as a timestamp
     * 
     * @return integer
     */
    public function getModifyDate()
    {
        return $this->modifyDate;
    }

    /**
     * Returns the aggregated stats array
     * 
     * @return array
     */
    public function getStats()
    {
        return $this->aggregatedStats;
    }
}
