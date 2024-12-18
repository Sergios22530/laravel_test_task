<?php

namespace App\Logger\Traits;

use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

trait LoggerTrait
{
    /**
     * Log Error to Database
     * @param Exception | Throwable $exception
     * @param $message
     * @param array $additionalData
     */
    public function logError($exception, $message, array $additionalData = [])
    {
        try {
            Log::channel('db_logger')->critical($message . ' ' . self::class,
                array_merge([
                    'code' => @$exception->getCode(),
                    'line' => @$exception->getLine(),
                    'message' => @$exception->getMessage(),
                    'trace' => @$exception->getTraceAsString()
                ], $additionalData)
            );
        } catch (Exception|Throwable $exception) {
            Log::channel('db_logger')->critical('The logger is down(), think about it - https://laravel.com/docs/', [
                'message' => $message,
                'failed_logger_data' => $exception
            ]);
        }
    }

    /**
     * Log Error to Database static
     * @param Exception | Throwable $exception
     * @param $message
     * @param array $additionalData
     */
    public static function logErrorStatic($exception, $message, array $additionalData = [])
    {
        try {
            Log::channel('db_logger')->critical($message . ' ' . self::class,
                array_merge([
                    'code' => @$exception->getCode(),
                    'line' => @$exception->getLine(),
                    'message' => @$exception->getMessage(),
                    'trace' => @$exception->getTraceAsString()
                ], $additionalData)
            );
        } catch (Exception|Throwable $exception) {
            Log::channel('db_logger')->critical('The logger is down(), think about it - https://laravel.com/docs/', [
                'message' => $message,
                'failed_logger_data' => $exception
            ]);
        }
    }

}
