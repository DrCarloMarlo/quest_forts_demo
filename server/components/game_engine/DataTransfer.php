<?php
/**
 * Created by PhpStorm.
 * User: carly
 * Date: 18.02.2022
 * Time: 23:24
 */

class DataTransfer
{
    public function build ($type, $action, $data) {
        $transmitting = [
            'timestamp' => time(),
            'type' => $type,
            'action' => $action,
            'data' => $data
        ];
        return json_encode($transmitting);
    }

    public function Message($type, $data){
        return $this->build($type, 'message', $data);
    }

    /**
     * Returns an array to fill in the game screen fields based on game progress data
     *
     * @return string JSON
     */
    public function Static ($stage, $stageStartTime, $valid_enter, $stageData, $itemData, $itemPromptData) {
        $data = [
            'stage' => $stage,
            'stageStartTime' => $stageStartTime,
            'valid_enter' => $valid_enter,
            'stageData' => $stageData,
            'itemData' => $itemData,
            'itemPromptData' => $itemPromptData
        ];
        return $this->build('system', 'static', $data);
    }

    public function Items ($items) {
        $data = [
            'items' => $items,
        ];
        return $this->build('system', 'items', $data);
    }
}