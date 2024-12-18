<?php

namespace App\Logger;

use Illuminate\Support\Facades\DB;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

/**
 * Class MySQLLoggingHandler
 *
 * Example logging:
 *     Log::channel('db_logger')->emergency('Action log debug test', ['my-string' => 'log me', 'run']);
 *     Log::channel('db_logger')->alert('Action log debug test', ['my-string' => 'log me', 'run']);
 *     Log::channel('db_logger')->critical('Action log debug test', ['my-string' => 'log me', 'run']);
 *     Log::channel('db_logger')->error('Action log debug test', ['my-string' => 'log me', 'run']);
 *     Log::channel('db_logger')->warning('Action log debug test', ['my-string' => 'log me', 'run']);
 *     Log::channel('db_logger')->notice('Action log debug test', ['my-string' => 'log me', 'run']);
 *     Log::channel('db_logger')->info('Action log debug test', ['my-string' => 'log me', 'run']);
 *     Log::channel('db_logger')->debug('Action log debug test', ['my-string' => 'log me', 'run']);
 *
 * @package App\Logger
 *
 * @property string $table
 */
class MySQLLoggingHandler extends AbstractProcessingHandler
{
    /**
     *
     * Reference:
     * https://github.com/markhilton/monolog-mysql/blob/master/src/Logger/Monolog/Handler/MysqlHandler.php
     */
    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
        $this->table = 'backend_error_logs';
        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        $data = array(
            'message' => $record['message'],
            'context' => json_encode($record['context']),
            'level' => $record['level'],
            'level_name' => $record['level_name'],
            'channel' => $record['channel'],
            'record_datetime' => $record['datetime']->format('Y-m-d H:i:s'),
            'extra' => json_encode($record['extra']),
            'formatted' => $record['formatted'],
            'remote_addr' => (array_key_exists('REMOTE_ADDR', $_SERVER)) ? $_SERVER['REMOTE_ADDR'] : null,
            'user_agent' => (array_key_exists('HTTP_USER_AGENT', $_SERVER)) ? $_SERVER['HTTP_USER_AGENT'] : null,
            'created_at' => date("Y-m-d H:i:s"),
        );

        DB::table($this->table)->insert($data);
    }
}
