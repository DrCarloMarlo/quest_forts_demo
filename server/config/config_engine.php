<?php
/**
 * Created by PhpStorm.
 * User: carly
 * Date: 27.01.2022
 * Time: 20:29
 */

//Engine mode that determines whether to use built-in test data or load working ones from the database
define('MODE_ENGINE', 'default');

//Key game defines the game data key in the database, not required by default
define('KEYGAME', '');

/*Mode debug|standart, in debug mode disable checks:
game start time (server will start the game immediately)*/
define('START_MODE', 'debug');

/*User activation timeout in the system:
After the start of gaming activity and exceeding the limit, activation in automatic mode is not possible*/
define('STARTOFFSET', 20);

//DB sign for cache base, so far only redis|disable
define('cacheBase', 'redis');