<?php
namespace Sooh\DBClasses\Cases\OrdersChk;

/**
 * 上次成功验证后账号数据
 *
 * @author wangning
 */
class AccountLastOk extends \Sooh\DBClasses\KVObj{
//    /**
//     * 
//     * @param type $uid
//     * @return \Sooh\DBClasses\Cases\OrdersChk\AccountLastOk
//     */
//    public static function getCopy($uid) {
//        if($uid===null){
//            return parent::getCopy(null);
//        }else{
//            return parent::getCopy(array('uid'=>$uid));
//        }
//    }
    protected function onInit()
    {
        parent::onInit();
        $this->_tbName = 'tb_accsuccess_{i}';//表名的默认模板
    }

    public static function install()
    {
        $db = static::getCopy(null)->dbWithTablename();
        $db->exec(array('create table if not exists '.$db->kvobjTable().'('
            . 'uid varchar(64) not null,'
            . 'hasError int not null default 0,'
            . 'errors varchar(2000) not null default \'\','
            . 'dtOrderLast int not null default 0,'
            . 'dtUpdate int not null default 0,'
            . 'balance varchar(500) default \'\','
            . 'rowVersion int not null default 0,'
            . 'primary key (uid)'
            . ')'));
    }
}
