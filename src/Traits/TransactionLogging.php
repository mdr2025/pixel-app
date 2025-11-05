<?php

namespace PixelApp\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Throwable;

trait TransactionLogging
{
    /**
     * Execute a callback within a database transaction with logging
     *
     * @param callable $callback The function to execute within the transaction
     * @param string $operation Description of the operation being performed
     * @param array $context Additional context to include in logs
     * @param string|null $logChannel Custom log channel (defaults to transaction)
     * @return mixed
     */
    protected function surroundWithTransaction(callable $callback, string $operation = "operation", array $context = [], ?string $logChannel = null)
    {
        // Determine if logging is enabled
        $isLoggingEnabled = $this->isLoggingEnabled();
        $channel = $logChannel ?? config('logging.transaction_channel', 'transactions');

        // Log the start of the operation if logging is enabled
        if ($isLoggingEnabled) {
            Log::channel($channel)->info("Starting {$operation}", $context);
        }

        DB::beginTransaction();

        try {
            // Execute the callback
            $result = $callback();

            // Commit the transaction
            DB::commit();

            // Log successful completion if logging is enabled
            if ($isLoggingEnabled) {
                Log::channel($channel)->info("Completed {$operation}", array_merge($context, [
                    'status' => 'success',
                    'execution_time' => $this->getExecutionTime(),
                ]));
            }

            return $result;
        } catch (Throwable $e) {
            // Rollback the transaction
            DB::rollBack();

            // Always log errors, regardless of logging setting
            // This ensures critical errors are captured even if normal logging is disabled
            $errorChannel = config('logging.error_channel', $channel);
            Log::channel($errorChannel)->error("Error during {$operation}", array_merge($context, [
                'error' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $this->getFormattedTrace($e),
            ]));

            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => "Failed to {$operation}",
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred',
            ], 500);
        }
    }

    /**
     * Check if transaction logging is enabled via environment configuration
     *
     * @return bool
     */
    private function isLoggingEnabled(): bool
    {
        // Default to true if not specified
        return env('ENABLE_TRANSACTION_LOGGING', true);
    }

    /**
     * Get a formatted stack trace
     *
     * @param Throwable $e
     * @return array
     */
    private function getFormattedTrace(Throwable $e): array
    {
        // Only collect trace if detailed logging is enabled
        if (!env('ENABLE_DETAILED_ERROR_LOGGING', true)) {
            return ['trace_collection_disabled' => true];
        }

        // Get max trace depth from config, default to 10
        $maxTraceDepth = env('MAX_ERROR_TRACE_DEPTH', 10);

        return collect($e->getTrace())
            ->take($maxTraceDepth)
            ->map(function ($frame) {
                return [
                    'file' => $frame['file'] ?? 'unknown',
                    'line' => $frame['line'] ?? 0,
                    'function' => ($frame['class'] ?? '') . ($frame['type'] ?? '') . ($frame['function'] ?? ''),
                ];
            })
            ->toArray();
    }

    /**
     * Get the execution time from start of request
     *
     * @return float
     */
    private function getExecutionTime(): float
    {
        if (!defined('LARAVEL_START')) {
            // If LARAVEL_START is not defined, use alternative timing
            return 0.0;
        }

        return round(microtime(true) - LARAVEL_START, 4);
    }

    /**
     * Log a message conditionally based on environment settings
     *
     * @param string $level Log level (info, error, warning, debug)
     * @param string $message The message to log
     * @param array $context Additional context
     * @param string|null $channel Specific channel to use
     * @return void
     */
    protected function conditionalLog(string $level, string $message, array $context = [], ?string $channel = null): void
    {
        if ($this->isLoggingEnabled()) {
            $useChannel = $channel ?? config('logging.transaction_channel', 'transactions');
            Log::channel($useChannel)->$level($message, $context);
        }
    }
}
