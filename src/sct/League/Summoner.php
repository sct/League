<?php

namespace sct\League;

use sct\League\Common\GameType;
use sct\League\League\League;
use sct\League\Team\Team;
use sct\League\Exception\SummonerDoesNotExistException;
use sct\League\Exception\ChampionDoesNotExistException;

class Summoner
{
    /**
     * Instance of the Client object. Used for base API calls.
     *
     * @var object
     */
    public static $client;

    /**
     * Summoner Id
     *
     * @var integer
     */
    private $id;

    /**
     * Summoner Name
     *
     * @var string
     */
    private $name;

    /**
     * Summoner Profile Icon ID
     *
     * @var integer
     */
    private $profileIconId;

    /**
     * Summoner Level
     *
     * @var integer
     */
    private $summonerLevel;

    /**
     * Summoner Revision Date
     *
     * @var integer
     */
    private $revisionDate;

    /**
     * Array to store gametype stats. Can be preloaded through the constructor
     * 
     * @var array
     */
    public $stats;

    /**
     * Array to store team information
     * 
     * @var array
     */
    public $teams;

    /**
     * Summoner Factory
     *
     * Creates summoner objects. You can request multiple summoners by passing an array
     * in for the $summoner value. 
     *
     * Usage: Summoner::factory(array("Dyrus", "Xpecial"), "na", "api_key", true)
     *
     * Returns an array of summoners if multiple summoners requests. Returns just a
     * single summoner object is only one summoner is requested.
     * 
     * @param  string/array  $summoner String or array of summoners
     * @param  string  $region   Region
     * @param  string  $key      API Key
     * @param  boolean $preload  Set to true to preload stat values for summoners
     * @return array/Summoner            Returns an array or summoner
     */
    public static function factory($summoner, $region, $key, $preload = false)
    {
        if (empty($client)) {
            self::$client = new StatClient($key, $region);
        }

        if (is_array($summoner)) {
            $summoners = array();
            $sArray = self::$client->getSummonerByName($summoner);
            foreach ($sArray as $name => $sum) {
                $summoners[$name] = new Summoner($sum, $preload);
            }

            return $summoners;
        } else {
            return new Summoner(self::$client->getSummonerByName($summoner), $preload);
        }
    }

    /**
     * Summoner constructor. Maps properties to class fields
     * @param array  $properties Summoner properties
     * @param boolean $preload    Preload summoner stats
     */
    public function __construct($properties, $preload = false)
    {
        foreach ($properties as $key => $value) {
            $this->{$key} = $value;
        }

        if ($preload) {
            $this->preloadStats();
        }
    }

    /**
     * Returns the Summoner ID
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the Summoner Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the profile icon ID
     * 
     * @return integer
     */
    public function getProfileIconId()
    {
        return $this->profileIconId;
    }

    /**
     * Returns the Summoner level
     *
     * @return integer
     */
    public function getSummonerLevel()
    {
        return $this->summonerLevel;
    }

    /**
     * Returns the revision date
     * 
     * @return integer
     */
    public function getRevisionDate()
    {
        return $this->revisionDate;
    }

    /**
     * Returns the raw Stats response from the API
     *
     * @param string $type Type of stats to request. (summary or ranked)
     *
     * @return Array Array of the stats object
     */
    public function getStats()
    {
        if (empty($this->stats)) {
            $this->preloadStats();
        }

        return $this->stats;
    }

    /**
     * Gets the stats for the requested gametype.
     *
     * @param GameType $gametype GameType to request stats for
     *
     * @return Array
     */
    public function getStatsForGameType($gametype = GameType::Unranked)
    {
        if (empty($this->stats)) {
            $this->preloadStats();
        }

        if (array_key_exists($gametype, $this->stats)) {
            return $this->stats[$gametype];
        } else {
            return null;
        }
    }

    /**
     * Gets full array of ranked stats
     *
     * @return Array
     */
    public function getRankedStats()
    {
        return self::$client->getSummonerStats($this->id, "ranked");
    }

    /**
     * Return Array of requested champions ranked stats
     *
     * @param integer $name Champion ID
     *
     * @return Array Array of Champions ranked stats
     */
    public function getStatsForChampion($id)
    {
        $stats = $this->getRankedStats();

        foreach ($stats['champions'] as $champion) {
            if (isset($champion['id']) && $champion['id'] == $id) {
                return $champion;
            }
        }

        throw new ChampionDoesNotExistException('Champion not in summoners history');
    }

    /**
     * Requests stats for a champion by name. Uses the Champion class to make sure the champion
     * exists and is valid. Then calls the champion up by ID.
     *
     * @param string $name Champion Name
     *
     * @return array Champion stats
     */
    public function getStatsForChampionByName($name)
    {
        if (!Champions::isLoaded()) {
            Champions::loadChampions(self::$client->getRegion(), self::$client->getKey());
        }

        try {
            $champion = Champions::getChampion($name);

            return $this->getStatsForChampion($champion->getId());
        } catch (ChampionDoesNotExistException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Request the current match history. Max 10 results.
     * @return array Match History
     */
    public function getMatchHistory()
    {
        $matchHistory = self::$client->getMatchHistory($this->id);

        return $matchHistory;
    }

    /**
     * Request all masteries for this summoner
     *
     * @return Array Array of Masteries
     */
    public function getMasteries()
    {
        $masteries = self::$client->getSummonerMastery($this->id);

        return $masteries;
    }

    /**
     * Request all runes for this summoner
     *
     * @return Array Array of Runes
     */
    public function getRunes()
    {
        $runes = self::$client->getSummonerRunes($this->id);

        return $runes;
    }

    /**
     * Returns a League object with the summoners league information
     * 
     * @return object
     */
    public function getLeague()
    {
        return new League($this->id, self::$client->getSummonerLeague($this->id));
    }

    /**
     * Returns the teams array. Loads teams from the API if not already loaded
     * 
     * @return array
     */
    public function getTeams()
    {
        if (empty($this->teams)) {
            $this->loadTeams();
        }
        
        return $this->teams;
    }

    /**
     * Loads teams from the API
     */
    public function loadTeams()
    {
        $teams = self::$client->getSummonerTeam($this->id);

        $this->teams = array();
        foreach ($teams as $team) {
            array_push($this->teams, new Team($team));
        }
    }

    /**
     * Preloads the stats array with gametype data
     */
    private function preloadStats()
    {
        $stats = self::$client->getSummonerStats($this->id, "summary");
        $this->stats = array();
        foreach ($stats['playerStatSummaries'] as $gametype) {
            $this->stats[$gametype['playerStatSummaryType']] = new GameType($this, $gametype);
        }
    }
}
