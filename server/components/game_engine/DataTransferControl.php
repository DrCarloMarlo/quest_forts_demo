<?php
/**
 * Created by PhpStorm.
 * User: carly
 * Date: 19.02.2022
 * Time: 0:04
 */

class DataTransferControl extends DataTransfer
{
    public function Statistic ($processData) {
        $data = '';

        foreach ($processData as $team) {
            $data .= "Команда " . $team['team_id'] ." на этапе ". $team['stage'] .". Очков ". $team['team_scores'].". <br>";
        }

        return $this->build('server', 'message', $data);
    }

    public function Cookie ($name, $value) {
        $data = [
            'message' => [
                $name => $value
            ]
        ];
        return parent::build('system', 'cookie', $data);
    }

    public function TeamData($data) {
        return parent::build('system', 'putData', $data);
    }

    public function Heartbeat () {
        return parent::build('control', 'heartbeat', null);
    }
}