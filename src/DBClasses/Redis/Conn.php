<?php
namespace Sooh\DBClasses\Redis;

class Conn extends \Sooh\DBClasses\Interfaces\Conn {
    /**
     * 
     * @return \Sooh\DBClasses\Interfaces\Conn
     * @throws \Sooh\DBClasses\DBErr
     */
    public function getConnHandle()
    {
        if($this->connected){
            return $this->connected;
        }
        if(!$this->connected){
            try{
                \Sooh2\Misc\Loger::getInstance()->sys_trace("TRACE: Redis connecting");
                $this->connected=new \Redis();
                $this->connected->connect($this->server, $this->port);
                $this->connected->auth($this->pass);

                if(!$this->connected){
                    throw new \Sooh\DBClasses\DBErr(\Sooh\DBClasses\DBErr::connectError, "connect to redis-server {$this->server}:{$this->port} failed", "");
                }
                $this->dbName = $this->dbNameDefault - 0;
                if($this->dbName){
                    $this->connected->select($this->dbName);
                }
                return $this->connected;
            }catch (\Exception $e){
                throw new \Sooh\DBClasses\DBErr(\Sooh\DBClasses\DBErr::connectError, $e->getMessage()." when try connect to {$this->server} by {$this->user}", "");
            }
        }

    }
    public function freeConnHandle()
    {
        if($this->connected){
            $this->connected->close();
            $this->connected=false;
        }
    }
    public function change2DB($dbName)
    {
        try{
            if(!$this->connected){
                $this->getConnHandle();
            }
            $this->connected->select($dbName);
            $this->dbNamePre = $this->dbName;
            $this->dbName = $dbName;
            return $this->dbNamePre;
        }catch (\ErrorException $e){
            throw new \Sooh\DBClasses\DBErr(\Sooh\DBClasses\DBErr::dbNotExists, $e->getMessage(), "");
        }
    }
    public function restore2DB()
    {
        return $this->change2DB($this->dbNamePre);
    }
}

