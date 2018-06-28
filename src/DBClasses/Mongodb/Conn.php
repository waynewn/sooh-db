<?php
namespace Sooh\DBClasses\Mongodb;

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
                \Sooh\Loger::getInstance()->sys_trace("TRACE: Mongo connecting");
                if($this->user!='ignore'&& $this->pass!='ignore'){
                    $str = "{$this->user}:{$this->pass}@";
                }else{
                    $str = '';
                }
                $this->connected=new \MongoDB\Driver\Manager("mongodb://{$str}{$this->server}:{$this->port}");

                if(!$this->connected){
                    throw new \Sooh\DBClasses\DBErr(\Sooh\DBClasses\DBErr::connectError, "connect to mongo-server {$this->server}:{$this->port} failed", "");
                }
                if($this->dbName){
                    $this->change2DB($this->dbName);
                }
            }catch (\Exception $e){
                throw new \Sooh\DBClasses\DBErr(\Sooh\DBClasses\DBErr::connectError, $e->getMessage()." when try connect to {$this->server} by {$this->user}", "");
            }
        }

    }
    public function freeConnHandle()
    {
        if($this->connected){
            //$this->connected->close();
            $this->connected=false;
        }
    }
    public function change2DB($dbName)
    {
        $this->dbNamePre = $this->dbName;
        try{
            if(!$this->connected){
                $this->getConnHandle();
            }
            //$this->connected->selectDB($dbName);
            $this->dbName = $dbName;
        }catch (\ErrorException $e){
            throw new \Sooh\DBClasses\DBErr(\Sooh\DBClasses\DBErr::dbNotExists, $e->getMessage(), "");
        }
    }
    public function restore2DB()
    {
        return $this->change2DB($this->dbNamePre);
    }
}

