<?php 

namespace Creational\Singleton;

/**
 * Singleton Design Pattern
 * let you ensure that a class has only one instance while providing a glable access point to this instance
 */

class Singleton
{
    private static $instances = [];

    protected function __construct() { }
    protected function __clone() { }
    protected function __wakeup() { 
        throw new \Exception("Can not unserialize signleton");
    }
    public function getInstance()
    {
        $subclass = static::class;
        if(!isset(self::$instances[$subclass])) {
            self::$instances[$subclass] = new static;
        }
        return self::$instances[$subclass];
    }
}

class Logger extends Singleton
{
    private $fileHandle;

    protected function __construct()
    {
        $this->fileHandle = fopen("php://stduout", "w");
    }

    public static function log(string $message): void
    {
        $logger = static::getInstance();
        $logger->writeLog($message);
    }

    public function writeLog(string $message): void
    {
        $date = date("Y-m-d");
        fwrite($this->fileHandle, "$date : message");
    }
}

Logger::log("Started");

$l1 = Logger::getInstance();
$l2 = Logger::getInstance();

if($l1 === $l2) {
    Logger::log("Logger has a signle instance");
} else {
    Logger::log("Logger are different");
}

Logger::log("Finished");

/*
2018-06-04: Started!
2018-06-04: Logger has a single instance.
2018-06-04: Finished!
*/