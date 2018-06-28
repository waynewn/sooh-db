<?php
namespace Sooh\DBClasses\Myisam;

/**
 * mysql 专属操作封装类
 *
 * @author wangning
 */
class Special extends Broker{
    /**
     * 
     * @param array $arrConnIni
     * @return \Sooh\DBClasses\Myisam\Special
     */
    public static function getInstance($arrConnIni)
    {
        $conn = \Sooh\DBClasses::getConn($arrConnIni);
        $guid = 'mysql@'.$conn->guid;
        if(!isset(\Sooh\DBClasses::$pool[$guid])){
            \Sooh\DBClasses::$pool[$guid] = new Special();
            \Sooh\DBClasses::$pool[$guid]->connection = $conn;
        }
        return \Sooh\DBClasses::$pool[$guid];
    }
    public function addLog($obj,$fields)
    {
        $this->connection->getConnection();
        $this->_lastCmd = 'INSERT DELAYED into '
            .$this->fmtObj($obj, $this->connection->dbName)
            .' set '.$this->buildFieldsForUpdate($fields,null);
        $this->exec(array($this->_lastCmd));
        $this->skipErrorLog(null);
        $insertId = mysqli_insert_id($this->connection->connected);
        return true;
    }
    
    public function resetAutoIncrement($obj,$newStartVal=1)
    {
        $this->_lastCmd= 'alter table '.$this->fmtObj($obj, $this->connection->dbName).' AUTO_INCREMENT='.$newStartVal;
        $this->exec(array($this->_lastCmd));
    }
}
