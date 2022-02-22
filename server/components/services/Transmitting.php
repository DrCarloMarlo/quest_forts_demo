<?php
/**
 * Created by PhpStorm.
 * User: carly
 * Date: 12.01.2022
 * Time: 17:51
 */

class Transmitting
{
    public $action;
    public $data;

    public function __construct(array $sender = []) {
        $this->action = null;
        $this->data = [];
        $this->initSender($sender);
    }

    private function initSender($sender) {
        if (count($sender) > 0) {
            print_r($sender);
            $this->data = $sender;
        };
    }

    public function setTransmitJSON (string $data) {
        $decode = $this->decodeJSON($data);
        $this->action = $decode['action'];
        if(isset($decode['data'])) $this->data = array_merge($this->data, $decode['data']);
        print_r($this->data);
    }

    public function setTransmit ($action, array $data = null) {
        $this->action = $action;
        $this->data = $data;
    }

    public function getProperty (string $property) {
        if(isset($this->data[$property])) {
            return $this->data[$property];
        } else {
            return false;
        }
    }

    private function decodeJSON ($data) {
        return json_decode($data, true, 4);
    }
}