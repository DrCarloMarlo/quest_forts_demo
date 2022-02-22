<?php
/**
 * Created by PhpStorm.
 * User: carly
 * Date: 01.02.2022
 * Time: 21:53
 */

class Control
{
    private $users;

    public function __construct() {
        $this->users = [];
    }

    public function executeCommands($commands, $connection = null) {
        if ($commands !== false) {
            foreach ($commands as $command) {
                switch ($command['action']) {
                    case 'putData':
                        $connection->send($command['data']);
                        break;
                    case 'private':
                        $client = $this->users[$connection->userID];
                        $client->send($command['data']);
                        break;
                    case 'group':
                        foreach ($this->users as $user) {
                            if ($user->teamID === $command['teamID']) {
                                $user->send($command['data']);
                            }
                        }
                        break;
                }
            }
        }
    }
}