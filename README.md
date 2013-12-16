"League" PHP API Library
======

A simple PHP library for the official League of Legends API

Still heavily in development. Currently supports getting Summoner data and Champion data. Can also request specific summoner stats for champions or game types.

Requirements
---------
* PHP >=5.3
* PHP Curl Extension

Installation
---------
You can install this library using composer. Learn about composer @ [getcomposer.org](http://getcomposer.org/)

1) Require the package in your composer.json

    "sct/league": "dev-master"

2) Run Composer to install the new requirement

    php composer.phar install
or

    php composer.phar update

Now make sure you are using the composer autoload in your project:

    require 'vendor/autoload.php';

    use sct\League\Summoner;

    $summoner = new Summoner("summoner", "region", "APIKEY");

And thats it you are good to go.

Usage
---------

Getting Summoner Data

    $dyrus = new Summoner("Dyrus", "na", "api key");

    $dyrus->getSummonerLevel();
    $dyrus->getStats();
    $dyrus->getRankedStats();
    $dyrus->getStatsForGameType(GameType::Unranked);
    $dyrus->getStatsForChampionByName("Darius");
    $dyrus->getMatchHistory();
    $dyrus->getMasteries();
    $dyrus->getRunes();

Getting Champion Data

    Champions::loadChampions("na", "api key");

    $anivia = Champions::getChampion("Anivia");

    $anivia->getActive();
    $anivia->getRanks();
    $anivia->getRankedPlayEnabled();


License
---------
This project is licensed under the MIT License. Feel free to do whatever you want with it.

Todo
---------
* Implement API calls for team information

*This product is not endorsed, certified or otherwise approved in any way by Riot Games, Inc. or any of its affiliates.*