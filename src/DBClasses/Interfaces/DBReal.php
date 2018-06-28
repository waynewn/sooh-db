<?php
namespace Sooh\DBClasses\Interfaces;

interface DBReal extends DB
{
    public function connect();
    public function disconnect();
}

