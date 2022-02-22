<?php
/**
 * Created by PhpStorm.
 * User: carly
 * Date: 21.12.2021
 * Time: 2:04
 */

define('DIR',  __DIR__);
define('COMPONENTS',  DIR.'/components');
define('ENGINE',  DIR.'/components/game_engine');
define('SERVICES',  DIR.'/components/services');
define('CONFIG',  DIR.'/config');

include_once CONFIG . '/config.php';
include_once CONFIG . '/config_engine.php';

spl_autoload_register(function ($class) {
    if (file_exists(COMPONENTS . '/' . $class . '.php')) {
        require_once COMPONENTS . '/' . $class . '.php';
    }
    if (file_exists(SERVICES . '/' . $class . '.php')) {
        require_once SERVICES . '/' . $class . '.php';
    }
    if (file_exists(ENGINE . '/' . $class . '.php')) {
        require_once ENGINE . '/' . $class . '.php';
    }
});

require_once DIR. '/vendor/autoload.php';
Predis\Autoloader::register();
use Workerman\Worker;
use Workerman\Timer;

$core = new Core();

$serverAction = new Commands();
$controlAction = new Control();

$ws_worker = new Worker("websocket://" . websocket_IP . ":" . websocket_port);

$ws_worker->onWorkerStart = function() use (&$serverAction, &$core)
{
    $waitTimer = null;

    //----MAIN GAME TIMER----
    Timer::add(1, function () use (&$serverAction, &$core, &$waitTimer) {
        if (false) {
            if ($waitTimer === null) {
                $waitTimer = Timer::add(5, function () use(&$core) {
                    $core->commutatorTimers('waitTimer');
                });
            }
        } else {
            Timer::del($waitTimer);

            //Check user stage times, send data
            $commands = $core->commutatorTimers('mainTimer');
            if ($commands !== false) {
                $serverAction->executeCommands($commands);
            }
        }
    });
    //----SYSTEM TIMER----
    Timer::add(10, function () use (&$core) {

    });
};

$ws_worker->onMessage = function($connection, $data) use (&$serverAction, &$core) {


    //добавляем идентификатор команды и пользователя
    if ($connection->keyAuth) {
        $transmitting = new Transmitting($connection->clientID);
    } else {
        $transmitting = new Transmitting();
    }
    $transmitting->setTransmitJSON($data);

    //GameEngine logic
    $commands = $core->commutator($transmitting);
    $serverAction->executeCommands($commands, $connection, $transmitting);
};

$ws_worker->onConnect = function($connection) use (&$serverAction, &$core)
{
    Timer::add(closeConnTime, function () use (&$serverAction, &$core) {
        foreach ($serverAction->getUsers() as $user) {
            if ($user->keyAuth === false) {
                $user->send($core->systemMessage('terminateAuthConnMessage'));
                $user->close("auth timeout and close");
            }
        }
    });
    $connection->onWebSocketConnect = function($connection) use (&$serverAction, &$core)
    {
        $transmitting = new Transmitting();

        if (isset($_COOKIE['playerID'])) {
            $transmitting->setTransmit('authorize', ['user_id' => $_COOKIE['playerID']]);
            $commands = $core->commutator($transmitting);

            $serverAction->executeCommands($commands, $connection, $transmitting);
        } else {
            $connection->keyAuth = false;

            $transmitting->setTransmit('beforeAuth');
            $commands = $core->commutator($transmitting);

            $serverAction->executeCommands($commands, $connection, $transmitting);
        }
    };
};

$ws_worker->onClose = function($connection) use(&$serverAction)
{
    $serverAction->removeUser($connection);
};

$inner_ws_worker = new Worker("websocket://" . adminTCP_IP . ":" . adminTCP_port);

$inner_ws_worker->onMessage = function($connection, $data) use (&$controlAction, &$core) {
    $transmitting = new Transmitting();
    $transmitting->setTransmitJSON($data);

    $commands = $core->Commutator($transmitting);
    $controlAction->executeCommands($commands, $connection);
    //запрос данных о игре
    //массовое сообщение
    //командное сообщение
    //индивидуальная рассылка сообщений
    //перезапись параметров игры
};

Worker::runAll();