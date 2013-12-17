<?php

namespace sct\League;

use sct\League\Exception\AuthenticationFailedException;
use sct\League\Exception\SummonerDoesNotExistException;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;

class StatClient
{

    const RIOT_API_1    = "http://prod.api.pvp.net/api/lol/";
    const RIOT_API_2    = "http://prod.api.pvp.net/api/";
    const API_VERSION_1 = "v1.1";
    const API_VERSION_2 = "v2.1";

    /**
     * Riot League of Legends API Key
     *
     * @var string
     */
    private $key;

    /**
     * Region to work in
     *
     * @var string
     */
    private $region;

    /**
     * Instance of Guzzle Client for API Version 1
     *
     * @var object
     */
    private $client;

    /**
     * Instance of Guzzle Client for API Version 2
     *
     * @var object
     */
    private $clientTwo;

    /**
     * Create instance of League object to work with the API
     *
     * @param string $key    API Key
     * @param string $region Region to work in
     */
    public function __construct($key, $region = "na")
    {
        $this->key = $key;
        $this->region = $region;
        $this->client = new Client(self::RIOT_API_1 . $this->region . "/" . self::API_VERSION_1);
        $this->clientTwo = new Client(self::RIOT_API_2 . $this->region . "/" . self::API_VERSION_2);
    }

    /**
     * Returns the current API Key
     *
     * @return string API Key
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Returns the currently in-use Region
     *
     * @return string Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Request a Summoner ID by Name
     *
     * @param string $name Summoner Name
     *
     * @return integer Summoner ID
     */
    public function getSummonerIdByName($name)
    {
        $summoner = $this->getSummonerByName($name);

        return $summoner['id'];
    }

    /**
     * Request a Summoner object by Summoner name
     *
     * @param string $name Summoner Name
     *
     * @return array Summoner Array
     */
    public function getSummonerByName($name)
    {
        try {
            $response = $this->client->get('summoner/by-name/' . $name . "?api_key=" . $this->key)->send();

            return $response->json();
        } catch (ClientErrorResponseException $e) {
            $this->exception($e->getResponse()->getStatusCode());
        }
    }

    /**
     * Get summoner by ID. Option meta field for second level calls. (mastery/runes)
     * 
     * @param  integer $id   Summoner ID
     * @param  string $meta Second level API call
     * 
     * @return array
     */
    public function getSummoner($id, $meta = "")
    {
        try {
            if (isset($meta)) {
                $meta = "/" . $meta;
            }

            $response = $this->client->get('summoner/' . $id . $meta .'?api_key=' . $this->key)->send();

            return $response->json();
        } catch (ClientErrorResponseException $e) {
            $this->exception($e->getResponse()->getStatusCode());
        }
    }

    /**
     * Request Summoner Stats
     *
     * @param integer $summonerId Summoner ID
     * @param string  $type       Type of request. summary/ranked
     *
     * @return array Array of requested data
     */
    public function getSummonerStats($summonerId, $type = "summary")
    {
        try {
            $response = $this->client->get('stats/by-summoner/' . $summonerId . "/" . $type . "?api_key=" . $this->key)->send();

            return $response->json();
        } catch (ClientErrorResponseException $e) {
            $this->exception($e->getResponse()->getStatusCode());
        }
    }

    /**
     * Request Summoner Stats by Summoner Name
     *
     * @param string $name Summoner Name
     * @param string $type Type of Request. summary/ranked
     *
     * @return array Array of requested data
     */
    public function getSummonerStatsByName($name, $type = "summary")
    {
        return $this->getSummonerStats($this->getSummonerIdByName($name), $type);
    }

    /**
     * Request Champions
     *
     * @return array Champion Array
     */
    public function getChampions()
    {
        try {
            $response = $this->client->get('champion?api_key=' .  $this->key)->send();

            return $response->json();
        } catch (ClientErrorResponseException $e) {
            $this->exception($e->getResponse()->getStatusCode());
        }
    }

    /**
     * Request match history from the API using a Summoner ID
     *
     * @param integer $summonerId Summoner ID
     *
     * @return array Match History Array
     */
    public function getMatchHistory($summonerId)
    {
        try {
            $response = $this->client->get('game/by-summoner/' . $summonerId . '/recent?api_key=' . $this->key)->send();

            return $response->json();
        } catch (ClientErrorResponseException $e) {
            $this->exception($e->getResponse()->getStatusCode());
        }
    }

    /**
     * Request Mastery pages from the API using a Summoner ID
     *
     * @param integer $summonerId Summoner ID
     *
     * @return array Mastery Page Array
     */
    public function getSummonerMastery($summonerId)
    {
        return $this->getSummoner($summonerId, "masteries");
    }

    /**
     * Request Rune pages from the API using a Summoner ID
     *
     * @param integer $summonerId Summoner ID
     *
     * @return array Rune Page Array
     */
    public function getSummonerRunes($summonerId)
    {
       return $this->getSummoner($summonerId, "runes");
    }

    /**
     * Request a Summoners league information
     * 
     * @param  integer $summonerId Summoner ID
     * 
     * @return array
     */
    public function getSummonerLeague($summonerId)
    {
        try {
            $response = $this->clientTwo->get('league/by-summoner/' . $summonerId . '?api_key=' . $this->key)->send();

            return $response->json();
        } catch (ClientErrorResponseException $e) {
            $this->exception($e->getResponse()->getStatusCode());
        }
            
    }

    /**
     * Request a summoners team information
     * 
     * @param  integer $summonerId Summoner ID
     * 
     * @return array
     */
    public function getSummonerTeam($summonerId)
    {
        try {
            $response = $this->clientTwo->get('team/by-summoner/' . $summonerId . '?api_key=' . $this->key)->send();

            return $response->json();
        } catch (ClientErrorResponseException $e) {
            $this->exception($e->getResponse()->getStatusCode());
        }
    }

    /**
     * Throw exceptions based on the response code
     * 
     * @param  integer $responseCode Response code returned from request
     * 
     * @return Exception
     */
    private function exception($responseCode)
    {
        switch ($responseCode) {
            case 404:
                throw new SummonerDoesNotExistException('Summoner does not exist');
            case 401:
                throw new AuthenticationFailedException('Failed to authenticate key with API');
            default:
                throw new RuntimeException('An unknown error occured during this request');
        }
    }

}
