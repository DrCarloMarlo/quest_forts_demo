<?php
/**
 * Created by PhpStorm.
 * User: carly
 * Date: 17.02.2022
 * Time: 20:31
 */

class Engine
{
    use ProcessData;
    use flowProcess;

    private $gameData;
    private $messageData;
    private $dataTransfer;
    private $dataTransferControl;
    protected $cashBaseResource;

    public function __construct()
    {
        $this->gameData = null;
        $this->messageData = null;
        $this->cashBase = null;

        $this->initEngine();
    }

    public function initEngine()
    {
        if (START_MODE === 'debug') $this->setOn();
        //if (cacheBase === 'redis')
        $this->cashBaseResource = new Predis\Client();
        if (MODE_ENGINE === 'default') {
            if ($this->gameData === null) $this->gameData = new GameData();
            if ($this->messageData === null) $this->messageData = new MessageData();
            if ($this->dataTransfer === null) $this->dataTransfer = new DataTransfer();
            if ($this->dataTransferControl === null) $this->dataTransferControl = new DataTransferControl();
        }
        //TODO: запуск выгрузки данных из БД (отложен до создания сайта)
    }

    /*-----------------------------------------------------------------------------------------------------------------

    ---------------------------------------------ENGINE GAME LOGIC-----------------------------------------------------

    -----------------------------------------------------------------------------------------------------------------*/

    public function beforeAuth() {
        $commands = [];
        if ($this->getStatus() !== false) {
            $gameGreetings = $this->getServerMessage('nonAuthStartMessage');
            array_push($commands, $this->createBasicCommand('private', $gameGreetings));
        } else {
            $gameGreetings = $this->getServerMessage('gameNotStart', [$this->gameData->getTitle(), $this->gameData->getFormatStartDate()]);
            array_push($commands, $this->createBasicCommand('private', $gameGreetings));
        }
        return $commands;
    }

    public function activationAccount($user_id)
    {
        $commands = [];
        if ($this->getStatus() === false) {
            $gameNotStart =$this->getServerMessage('gameNotStart', [$this->gameData->getTitle(), $this->gameData->getFormatStartDate()]);
            array_push($commands, $this->createBasicCommand('private', $gameNotStart));
            return $commands;
        } else {
            $teamID = $this->identificateGroupAttach($user_id);
            if ($teamID !== false) {
                if ($this->getTeamActive($teamID) === false) {
                    if (START_MODE !== 'debug') {
                        if (abs($this->gameData->getStartDate() - time()) < STARTOFFSET) {
                            $this->setTeamActive($teamID);
                        } else {
                            $messageActivationLoss = $this->messageData->getServerMessage('activationTimeLoss');
                            array_push($commands, $this->createGroupCommand($teamID, $messageActivationLoss));
                            return $commands;
                        }
                    } else {
                        $this->setTeamActive($teamID);
                    }
                }
                $cookie = $this->dataTransferControl->Cookie('playerID', $user_id);
                $gameGreetings = $this->getServerMessage('gameGreetings', [$this->gameData->getTitle()]);
                $staticData = $this->dataTransfer->Static(
                    $this->getTeamStage($teamID),
                    $this->getTeamStartTimeStage($teamID),
                    $this->getTeamValidEnter($teamID),
                    $this->gameData->getStaticStageData($this->getTeamStage($teamID)),
                    $this->gameData->getStageStartItem($this->getTeamStage($teamID)),
                    $this->getTeamInventory($teamID)
                );;

                array_push($commands, $this->createBasicCommand('authorize', $teamID));
                array_push($commands, $this->createBasicCommand('private', $cookie));
                array_push($commands, $this->createBasicCommand('private', $staticData));
                array_push($commands, $this->createBasicCommand('private', $gameGreetings));
                return $commands;
            }
        }
        return false;
    }

    public function stageAnswer($userAnswer, $teamID)
    {
        $commands = [];
        if ($this->checkTeamInGame($teamID)) {
            if ($this->checkRepeatAnswer($userAnswer, $teamID)) {
                if ($this->validateAnswer($userAnswer, $this->getTeamStage($teamID)) !== false) {
                    $this->addTeamValidEnter($teamID, $userAnswer, $this->gameData->getPointsAnswer());
                    $count_valid = $this->getTeamValidCount($teamID);
                    if ($count_valid == $this->gameData->getCodesStageCount($this->getTeamStage($teamID))) {
                        // STAGE COMPLETE - NEXT STAGE CREATE
                        $commands = $this->processingNextStage($teamID, 'completeStage');
                    } else {
                        // ENTER VALID ANSWER
                        $messageAnswerValid = $this->getServerMessage('validCode', [$userAnswer, $count_valid]);
                        array_push($commands, $this->createGroupCommand($teamID, $messageAnswerValid));
                    }
                } else {
                    // ENTER INVALID ANSWER
                    $messageAnswerInvalid = $this->getServerMessage('wrongCode');
                    array_push($commands, $this->createGroupCommand($teamID, $messageAnswerInvalid));
                }
            } else {
                // ENTER REPEAT ANSWER
                $messageAnswerRepeat = $this->getServerMessage('repeatCode', [$userAnswer]);
                array_push($commands, $this->createGroupCommand($teamID, $messageAnswerRepeat));
            }
            return $commands;
        }
        return false;
    }

    public function chatTeamMessage($message, $teamID, $userID)
    {
        $commands = [];
        if (strlen($message) > 3) {
            $messageChat = $this->createChatMessage($message, $this->getPlayerName($teamID, $userID));
            array_push($commands, $this->createGroupCommand($teamID, $messageChat));
        }
        return $commands;
    }

    public function mainTimer()
    {
        $commands = [];
        $data = $this->processData;

        foreach ($data as $key => $team) {
            if ($this->checkTeamInGame($key)) {
                $timeOnStage = time() - $this->getTeamStartTimeStage($key);
                // CREATE NEXT TIMER ITEM
                if ($this->getTeamNextItemTimer($key) === null) {
                    $this->createNextPrompt($key, $this->gameData->getStagePromptItem($this->getTeamStage($key)));
                }

                if ($timeOnStage > $this->gameData->getTimeLimit($team['stage'])) {
                    // STAGE TIME OVER - NEXT STAGE CREATE
                    $commands = $this->processingNextStage($key, 'lossStageTime');
                } else {
                    if ($this->getTeamNextItemTimer($key) !== null) {
                        $timeOnStage = time() - $this->getTeamStartTimeStage($key);
                        // SEND NEXT TIMER ITEM
                        if ($timeOnStage >= $this->getTeamNextItemTimer($key)) {
                            $this->addTeamInventoryItem($key);
                            $itemsSecureAvailable = $this->getServerMessage('secureItemAvailable');
                            $itemsData = $this->dataTransfer->Items($this->getTeamNextItem($key));
                            array_push($commands, $this->createGroupCommand($key, $itemsSecureAvailable));
                            array_push($commands, $this->createGroupCommand($key, $itemsData));
                            $this->resetTeamNextItemTimer($key);
                            $this->createNextPrompt($key, $this->gameData->getStagePromptItem($this->getTeamStage($key)));
                        }
                    }
                }
            }
        }
        return $commands;
    }

    public function waitTimer()
    {
        print_r('TINK');
        if ($this->gameData->getStartDate() - time() <= 0) {
            $this->setOn();
            $this->logSystem('Начало игры! WaitTimer initiate start game!');
        }
    }

    public function checkTeamInGame($teamID)
    {
        if ($this->getTeamActive($teamID) !== false
            && $this->getCompleteGame($teamID) === false) return true;
        return false;
    }

    public function processingNextStage($teamID, $message)
    {
        $command = [];
        if ($this->nextStage($teamID, $this->gameData->getStageCount())) {
            $lossStageTime = $this->getServerMessage($message);
            $staticData = $this->dataTransfer->Static(
                $this->getTeamStage($teamID),
                $this->getTeamStartTimeStage($teamID),
                $this->getTeamValidEnter($teamID),
                $this->gameData->getStaticStageData($this->getTeamStage($teamID)),
                $this->gameData->getStageStartItem($this->getTeamStage($teamID)),
                $this->getTeamInventory($teamID)
            );
            array_push($command, $this->createGroupCommand($teamID, $lossStageTime));
            array_push($command, $this->createGroupCommand($teamID, $staticData));
            return $command;
        } else {
            $endGame = $this->getServerMessage('completeGame', [$this->getTeamScoreSum($teamID)]);
            array_push($command, $this->createGroupCommand($teamID, $endGame));
            return $command;
        }
    }

    /**
     * Returns true or falls based on the check for the presence of the code from $user_input in the $codes string
     *
     * @return Response|boolean
     */
    public function validateAnswer($user_input, $stage)
    {
        $codesArray = explode('|', $this->gameData->getCodesStageData($stage));

        //перебор верный ли ответ
        foreach ($codesArray as $code) {
            if ($code === $user_input) {
                return true;
            }
        }
        return false;
    }

    public function getStatistic()
    {
        $commands = [];
        $statisticMessage = $this->dataTransferControl->Statistic($this->processData);
        array_push($commands, $this->createBasicCommand('private', $statisticMessage));
        return $commands;
    }

    public function changeNick($user_nick, $teamID, $userID)
    {
        $commands = [];
        if ($this->setPlayerName($teamID, $userID, $user_nick)) {
            $message = $this->getServerMessage('changeNickname', [$user_nick]);
            array_push($commands, $this->createBasicCommand('private', $message));
        } else {
            $message = $this->getServerMessage('changeNicknameFails');
            array_push($commands, $this->createBasicCommand('private', $message));
        }
        return $commands;
    }

    public function createChatMessage($userEnter, $initiator)
    {
        //TODO validator-filter for text message
        $message = $initiator . ": " . $userEnter;
        return $this->dataTransfer->Message('server', $message);
    }

    public function getServerMessage($messageName, $params = null)
    {
        $messageParams = $this->messageData->getMessageByName($messageName);

        if ($messageParams['params']) {
            $message = $this->createDynamycMessage($messageParams['message'], $params);
            return $this->dataTransfer->Message($messageParams['type'], $message);
        } else {
            $message = $this->createStaticMessage($messageParams['message']);
            return $this->dataTransfer->Message($messageParams['type'], $message);
        }
    }

    private function createStaticMessage($message)
    {
        return 'Сервер: ' . $message;
    }

    private function createDynamycMessage($message, $param)
    {
        if (isset($param)) {
            $template = array_map(function ($n) {
                return '{param[' . $n . ']}';
            }, range(0, count($param) - 1));
            return str_replace($template, $param, $message);
        } else {
            return false;
        }
    }

    public function getProcessData()
    {
        $commands = [];
        $dataTransfer = $this->dataTransferControl->TeamData($this->processData);
        array_push($commands, $this->createBasicCommand('putData', $dataTransfer));
        return $commands;
    }

    /*-----------------------------------------------------------------------------------------------------------------

    ---------------------------------------ENGINE GAME FORMAT COMMANDS DATA--------------------------------------------

    -----------------------------------------------------------------------------------------------------------------*/

    public function createBasicCommand($command, $data)
    {
        return [
            'action' => $command,
            'data' => $data,
        ];
    }

    public function createGroupCommand($teamID, $data)
    {
        return [
            'action' => 'group',
            'teamID' => $teamID,
            'data' => $data,
        ];
    }
}