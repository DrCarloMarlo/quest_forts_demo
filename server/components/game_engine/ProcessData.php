<?php
/**
 * Created by PhpStorm.
 * User: carly
 * Date: 17.01.2022
 * Time: 19:47
 */

trait ProcessData
{
    //Debug test team data
    private $processData = [
        [
            'team_id' => 'Test_group1',
            'game' => false,
            'players' => [
                'tester1' => [
                    'name' => 'Tester_00'
                ],
                'tester2' => [
                    'name' => 'Tester_01'
                ],
            ],
            'gameInTime' => 0,
            'stage' => 1,
            'stageStartTime' => 0,
            'valid_enter' => "",
            'inventory' => [

            ],
            'nextTimerItem' => null,
            'note_log' => "",
            'team_scores' => 0,
            'gameComplete' => false,
        ],
        [
            'team_id' => 'Test_group2',
            'game' => false,
            'players' => [
                'tester3' => [
                    'name' => 'Tester_02'
                ],
                'tester4' => [
                    'name' => 'Tester_03'
                ],
            ],
            'gameInTime' => 0,
            'stage' => 1,
            'stageStartTime' => 0,
            'valid_enter' => "",
            'inventory' => [

            ],
            'nextTimerItem' => null,
            'note_log' => "",
            'team_scores' => 0,
            'gameComplete' => false,
        ],
    ];

    public function init($teamDATA) {
        foreach ($teamDATA as $key => $team) {
            $this->processData = [
                'team_id' => $key,
                'game' => false,
                'players' => $teamDATA[$team]['players'],
                'gameInTime' => 0,
                'stage' => 1,
                'stageStartTime' => 0,
                'valid_enter' => "",
                'inventory' => [],
                'nextTimerItem' => null,
                'note_log' => "",
                'team_scores' => 0,
                'gameComplete' => false,
            ];
        }
    }

    //-----------BLOCK Team valid enter-------------//

    public function getTeamValidEnter($teamID) {
        return $this->processData[$teamID]['valid_enter'];
    }

    public function addTeamValidEnter($teamID, $newValidEnter, $pointsAnswer) {
        $this->processData[$teamID]['valid_enter'] .= $newValidEnter . '|';
        $this->successTeamScoreSum($teamID, $pointsAnswer);
    }

    public function getTeamValidCount($teamID) {
        return count(explode('|',$this->processData[$teamID]['valid_enter']))-1;//-1 crutch
    }

    //-----------BLOCK Team valid enter-------------//

    public function successTeamScoreSum($teamID, $points) {
        $this->processData[$teamID]['team_scores'] += $points;
    }

    public function getTeamScoreSum($teamID) {
        return $this->processData[$teamID]['team_scores'];
    }

    public function getTeamStage($teamID) {
        return $this->processData[$teamID]['stage'];
    }

    public function getTeamStartTimeStage($teamID) {
        return $this->processData[$teamID]['stageStartTime'];
    }

    public function getTeamNextItem($teamID) {
        return $this->processData[$teamID]['nextTimerItem'];
    }

    public function getTeamNextItemTimer($teamID) {
        if($this->processData[$teamID]['nextTimerItem'] === null) {
            return $this->processData[$teamID]['nextTimerItem'];
        } else {
            return $this->processData[$teamID]['nextTimerItem']['timer'];
        }
    }

    public function resetTeamNextItemTimer($teamID) {
        $this->processData[$teamID]['nextTimerItem'] = null;
    }

    public function addTeamInventoryItem($teamID) {
        array_push($this->processData[$teamID]['inventory'], $this->getTeamNextItem($teamID));
    }

    public function getTeamInventory($teamID) {
        return $this->processData[$teamID]['inventory'];
    }

    public function getPlayerName($teamID, $userID) {
        return $this->processData[$teamID]['players'][$userID]['name'];
    }

    public function setPlayerName($teamID, $userID, $user_input) {
        if (strlen($user_input) >= 3 || strlen($user_input) <= 16){
            $this->processData[$teamID]['players'][$userID]['name'] = $user_input;
            return true;
        } else {
            return false;
        }
    }

    //-----------BLOCK Team Status -------------//

    public function getCompleteGame($teamID) {
        return $this->processData[$teamID]['gameComplete'];
    }

    public function setCompleteGame($teamID) {
        $this->processData[$teamID]['gameComplete'] = true;
    }

    public function getTeamActive($teamID) {
        return $this->processData[$teamID]['game'];
    }

    public function setTeamActive($teamID) {
        $this->processData[$teamID]['game'] = true;
        $this->processData[$teamID]['stageStartTime'] = time();
    }

    /**
     * Updates the data in the $teamData array when going to the next stage
     *
     * @return Response|boolean
     */

    public function nextStage($teamID, $gameStageCount) {
        if($this->processData[$teamID]['stage'] === $gameStageCount) {
            $this->setCompleteGame($teamID);
            return false;
        }

        $this->processData[$teamID]['stage'] = $this->processData[$teamID]['stage']+1;
        $this->processData[$teamID]['stageStartTime'] = time();
        $this->processData[$teamID]['valid_enter'] = '';
        $this->processData[$teamID]['inventory'] = [];
        $this->processData[$teamID]['nextTimerItem'] = null;
        return true;
    }

    public function checkRepeatAnswer($user_input, $teamID) {
        $valid_enter = explode('|',$this->processData[$teamID]['valid_enter']);

        foreach ($valid_enter as $code) {
            if ($user_input === $code) return false;
        }
        return true;
    }

    /**
     * Returns the $commands array key for the given command ID - $commandID
     *
     * @return Response|integer
     */
    public function identificateGroupAttach ($userID) {
        foreach ($this->processData as $key => $team) {
            if (START_MODE !== 'debug') $userID = md5($userID);
            if (array_key_exists($userID, $team['players'])) {
                return $key;
            }
        }
        return false;
    }

    public function createNextPrompt ($groupId, $items) {
        $nextItem = false;
        foreach ($items as $key => $item) {
            //separate item also available team
            if (count($this->processData[$groupId]['inventory']) > 0) {
                foreach ($this->processData[$groupId]['inventory'] as $teamItem) {
                    if ($item['id'] == $teamItem['id']) unset($items[$key]);
                }
            }
        }
        //go on separate array $items
        if (count($items) > 0) {
            foreach ($items as $key => $item) {
                if ($nextItem == null || $nextItem['timer'] > $item['timer']) {
                    $nextItem = $item;
                }
            }
        }
        if ($nextItem !== false) {
            $this->processData[$groupId]['nextTimerItem'] = $nextItem;
        } else {
            $this->processData[$groupId]['nextTimerItem'] = null;
        }
    }
}