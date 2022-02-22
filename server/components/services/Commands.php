<?php
/**
 * Created by PhpStorm.
 * User: carly
 * Date: 01.02.2022
 * Time: 21:53
 */

class Commands
{
    private $users;

    public function __construct() {
        $this->users = [];
    }

    public function getUsers() {
        return $this->users;
    }

    public function addUser($userID, $connection) {
        $this->users[$userID] = $connection;
    }

    public function removeUser($connection) {
        $user = array_search($connection, $this->users);
        unset($this->users[$user]);
    }

    public function getTeamUsers($teamID) {
        $team_users = [];
        foreach ($this->users as $user) {
            if ($user->teamID === $teamID) {
                array_push($team_users, $user) ;
            }
        }
        return $team_users;
    }

    public function executeCommands($commands, $connection = null, $transmitting = null) {
        if ($commands !== false) {
            foreach ($commands as $command) {
                switch ($command['action']) {
                    case 'authorize':
                        $connection->clientID = array("teamID"=>$command['data'], "userID"=>$transmitting->getProperty('user_id'));
                        $connection->keyAuth = true;
                        $this->users[$transmitting->getProperty('user_id')] = $connection;
                        break;
                    case 'private':
                        $connection->send($command['data']);
                        break;
                    case 'group':
                        foreach ($this->users as $user) {
                            if ($user->clientID["teamID"] === $command['teamID']) {
                                $user->send($command['data']);
                            }
                        }
                        break;
                }
            }
        }
    }
}