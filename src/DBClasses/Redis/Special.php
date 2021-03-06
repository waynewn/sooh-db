<?php
namespace Sooh\DBClasses\Redis;

/**
 * Redis 专用功能库
 *
 * @author wangning
 */
class Special extends Cmd{
    /**
     * 
     * @param array $arrConnIni
     * @return \Sooh\DBClasses\Myisam\Special
     */
    public static function getInstance($arrConnIni)
    {
        $conn = \Sooh\DBClasses::getConn($arrConnIni);
        $guid = 'redis@'.$conn->guid;
        if(!isset(\Sooh\DBClasses::$pool[$guid])){
            \Sooh\DBClasses::$pool[$guid] = new Special();
            \Sooh\DBClasses::$pool[$guid]->connection = $conn;
        }
        return \Sooh\DBClasses::$pool[$guid];
    }
    public function getStr($key,$where=null)
    {
        $this->connection->getConnection();
        $allkeys = $this->fmtObj($key,$where);
        if(sizeof($allkeys)==1){
                return $this->exec(array(['get',$allkeys[0]]));
        }else{
                $ret = array();
                foreach($allkeys as $k){
                        $ret[$k]=$this->exec(array(['get',$allkeys[0]]));
                }
                return $ret;
        }
    }
    
    public function setStr($val,$key,$where=null)
    {
        $this->connection->getConnection();
        $allkeys = $this->fmtObj($key,$where);
        if(is_array($val)){
                $val = json_encode($val);
        }
        return $this->exec(array(['get',$allkeys[0],$val]));
    }
	
    public function setExpire($secondsExpired,$key,$where=null)
    {
        $this->connection->getConnection();
        $allkeys = $this->fmtObj($key,$where);
        return $this->exec(array(['expireAt',$allkeys[0],time()+$secondsExpired]));
    }
    public function dropKey($key,$where=null)
    {
        $this->connection->getConnection();
        $allkeys = $this->fmtObj($key,$where);
        if(sizeof($allkeys)==1){
                $this->exec(array(['delete',$allkeys[0]]));
        }else{
            $tmp=array();
            foreach ($allkeys as $k){
                $tmp[]=array('delete',$k);
            }
            $this->exec($tmp);
        }
    }
}
