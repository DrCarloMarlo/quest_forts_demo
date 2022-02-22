<?php
/**
 * Created by PhpStorm.
 * User: carly
 * Date: 21.02.2022
 * Time: 18:00
 */

trait flowProcess
{
    private $flowOn = false;
    private $flowPause = false;

    public function getStatus()
    {
        return $this->flowOn;
    }

    public function setOn()
    {
        if ($this->flowPause === true) {
            $this->flowResume();
        }
        return $this->flowOn = true;
    }

    public function setPause()
    {
        $this->flowPause = true;
        $this->flowOn = false;
        $this->setCache($this->processData);
    }

    public function flowResume()
    {
        return json_decode($this->cashBaseResource->get('processCache'));
    }

    public function getCache()
    {
        $this->cashBaseResource->get('processCache');
    }

    public function setCache($data)
    {
        $this->cashBaseResource->set('processCache', json_encode($data));
    }

    public function delCache()
    {
        $this->cashBaseResource->del('processCache');
    }
}