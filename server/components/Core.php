<?php
/**
 * Created by PhpStorm.
 * User: carly
 * Date: 30.05.2021
 * Time: 12:43
 */

class Core
{
    private $gameEngine = null;

    public function __construct() {
        if($this->gameEngine === null) $this->gameEngine = new Engine();
    }

    /**
     * The main point of interaction with the game engine, to process messages initiated by the client
     *
     * Passes to the specified function $transmitting
     *
     * return the result of the specified function
     */
    public function commutator ($transmitting) {
        $handlers = array(
            'beforeAuth' => 'beforeAuth',
            'authorize' => 'activationAccount',
            'stage_answer' => 'stageAnswer',
            'chat_message' => 'chatTeamMessage',
            'statistic' => 'getStatistic',
            'change_nick' => 'changeNick',
            'cache_data' => 'updateCache',
            'get_data' => 'getProcessData',
        );
        $function = $handlers[$transmitting->action];
        $handlersParamsValid = $this->validTransmitting($function, $transmitting);
        if($handlersParamsValid === false) {
            return false;
        } else {
            $arguments = [];
            foreach ($handlersParamsValid as $handlersParam) {
                array_push($arguments, $transmitting->getProperty($handlersParam));
            }
            return $this->gameEngine->$function( ...$arguments);
        }
    }

    private function validTransmitting($funcObj, $transmitting) {
        $reflect = new ReflectionMethod('Engine' ,$funcObj);

        $handlersParams = [];
        foreach ($reflect->getParameters() as  $param) {
            if ($transmitting->getProperty($param->getName()) === false) {
                return false;
            } else {
                array_push($handlersParams, $param->getName());
            }
        }
        return $handlersParams;
    }

    /**
     * Workerman Timer interaction point with the game engine
     *
     * Switches a timer function call by its name
     *
     * @return Returns the result of the specified function
     */
    public function commutatorTimers ($handler) {
        $handlers = array(
            'mainTimer' => 'mainTimer',
            'waitTimer' => 'waitTimer',
        );
        $function = $handlers[$handler];

        return $this->gameEngine->$function();
    }

    public function log ($str) {
        $log = date('Y-m-d H:i:s') . ' ' . $str . ' ' . time();
        file_put_contents(__DIR__ . '/log/log.txt', $log . PHP_EOL, FILE_APPEND);
    }

    public function logSystem($str) {
        $log = date('Y-m-d H:i:s') . ' ' . $str . ' ' . time();
        file_put_contents(__DIR__ . '/log/systemLog.txt', $log . PHP_EOL, FILE_APPEND);
    }
}