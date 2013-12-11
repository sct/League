"League" PHP API Library
======

A simple PHP library for the official League of Legends API

Still heavily in development. Currently supports getting Summoner data and Champion data. Can also request specific summoner stats for champions or game types.

Requirements
---------
* PHP >=5.3
* Guzzle ~3.7

Using this library
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

And thats it you are good to go. Sadly there is no documentation yet because this is nowhere near complete. Soon!

License
---------
This project is licensed under the MIT License. Feel free to do whatever you want with it.

Todo
---------
* Implement proper exceptions for all API requests
* Implement API calls for active game searches
* Implement API calls for league (ranked) information. (Silver tier lists, and such)
* Implement API calls for team information

*This product is not endorsed, certified or otherwise approved in any way by Riot Games, Inc. or any of its affiliates.*