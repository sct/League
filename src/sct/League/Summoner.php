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
    private $client;

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
     * Create a new instance of the Summoner class
     *
     * @param string $name   Summoner Name
     * @param string $region Region to search in
     * @param string $key    League API Key
     * @param boolean $preload Flag to preload stat data
     */
    public function __construct($name, $region, $key, $preload = false)
    {
        $this->client = new StatClient($key, $region);

        try {
            $summoner              = $this->client->getSummonerByName($name);
            $this->id              = $summoner['id'];
            $this->name            = $summoner['name'];
            $this->profileIconId   = $summoner['profileIconId'];
            $this->summonerLevel   = $summoner['summonerLevel'];
            $this->revisionDate    = $summoner['revisionDate'];

            if ($preload) {
                $this->preloadStats();
            }
        } catch (SummonerDoesNotExistException $e) {
            return null;
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
        return $this->client->getSummonerStats($this->id, "ranked");
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
            Champions::loadChampions($this->client->getRegion(), $this->client->getKey());
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
        $matchHistory = $this->client->getMatchHistory($this->id);

        return $matchHistory;
    }

    /**
     * Request all masteries for this summoner
     *
     * @return Array Array of Masteries
     */
    public function getMasteries()
    {
        $masteries = $this->client->getSummonerMastery($this->id);

        return $masteries;
    }

    /**
     * Request all runes for this summoner
     *
     * @return Array Array of Runes
     */
    public function getRunes()
    {
        $runes = $this->client->getSummonerRunes($this->id);

        return $runes;
    }

    /**
     * Returns a League object with the summoners league information
     * 
     * @return object
     */
    public function getLeague()
    {
        return new League($this->id, $this->client->getSummonerLeague($this->id));
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
        $teams = $this->client->getSummonerTeam($this->id);

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
        $stats = $this->client->getSummonerStats($this->id, "summary");
        $this->stats = array();
        foreach ($stats['playerStatSummaries'] as $gametype) {
            $this->stats[$gametype['playerStatSummaryType']] = new GameType($this, $gametype);
        }
    }
}
