<?php

namespace App\Services;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class BaseService
{
    /**
     * @var Logger $logger
     */
    protected $logger;

    /**
     * BaseService constructor.
     * @param $logPath
     * @throws /Exception
     */
    public function __construct($logPath)
    {
        $this->logger = new Logger('Logger');
        $this->logger->pushHandler(new StreamHandler(storage_path('logs/'.$logPath), Logger::WARNING));
    }
}
