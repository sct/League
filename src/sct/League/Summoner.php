<?php

namespace sct\League;

use sct\League\Common\GameType;
use sct\League\Exception\ChampionDoesNotExistException;

class Summoner
{
    /**
     * Instance of the League object. Used for base API calls.
     *
     * @var League
     */
    private $league;

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
     * Summoner revision date represented as a string
     *
     * @var string
     */
    private $revisionDateStr;

    /**
     * Create a new instance of the Summoner class
     *
     * @param string $name   Summoner Name
     * @param string $region Region to search in
     * @param string $key    League API Key
     */
    public function __construct($name, $region, $key)
    {
        $this->league          = new League($key, $region);
        $summoner              = $this->league->getSummonerByName($name);
        $this->id              = $summoner['id'];
        $this->name            = $summoner['name'];
        $this->profileIconId   = $summoner['profileIconId'];
        $this->summonerLevel   = $summoner['summonerLevel'];
        $this->revisionDate    = $summoner['revisionDate'];
        $this->revisionDateStr = $summoner['revisionDateStr'];
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
     * Returns the Summoner level
     *
     * @return integer
     */
    public function getSummonerLevel()
    {
        return $this->summonerLevel;
    }

    /**
     * Returns the raw Stats response from the API
     *
     * @param string $type Type of stats to request. (summary or ranked)
     *
     * @return Array Array of the stats object
     */
    public function getStats($type = "summary")
    {
        return $this->league->getSummonerStats($this->id, $type);
    }

    /**
     * Gets the stats for the requested gametype.
     *
     * @param GameType $gametype GameType to request stats for
     *
     * @return Array
     */
    public function getStatsForGameType($gametype = GameType::AramUnranked5x5)
    {
        $stats = $this->getStats();

        foreach ($stats['playerStatSummaries'] as $summary) {
            if ($summary['playerStatSummaryType'] == $gametype) {
                return $summary;
            }
        }

        return null;
    }

    /**
     * Gets full array of ranked stats
     *
     * @return Array
     */
    public function getRankedStats()
    {
        return $this->getStats("ranked");
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
            Champions::loadChampions($this->league->getRegion(), $this->league->getKey());
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
        $matchHistory = $this->league->getMatchHistory($this->id);

        return $matchHistory;
    }

    /**
     * Request all masteries for this summoner
     *
     * @return Array Array of Masteries
     */
    public function getMasteries()
    {
        $masteries = $this->league->getSummonerMastery($this->id);

        return $masteries;
    }

    /**
     * Request all runes for this summoner
     *
     * @return Array Array of Runes
     */
    public function getRunes()
    {
        $runes = $this->league->getSummonerRunes($this->id);

        return $runes;
    }
}
