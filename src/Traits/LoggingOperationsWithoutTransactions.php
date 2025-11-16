<?php 


namespace PixelApp\Traits;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;


trait LoggingOperationsWithoutTransactions
{
    /*
    |--------------------------------------------------------------------------
    | Prepare Logging Context
    |--------------------------------------------------------------------------
    */
    protected function prepareLoggingContext(
        array $loggingContext = [],
        string $operationName = "operation",
        bool $appendRequestDataToLog = true,
        bool $appendLoggedUserKeyToLog = true
    ): array {

        $loggingContext["operation"] = $operationName;

        if ($appendLoggedUserKeyToLog && Auth::check()) {
            $loggingContext['user_id'] = Auth::id();
        }

        if ($appendRequestDataToLog) {
            $loggingContext['request'] = request()->all();
        }

        return $loggingContext;
    }


    /*
    |--------------------------------------------------------------------------
    | Helper to Execute Callback
    |--------------------------------------------------------------------------
    */
    protected function executeCallback(callable $callback, array $args = []): mixed
    {
        return call_user_func($callback, ...$args);
    }


    /*
    |--------------------------------------------------------------------------
    | 1) Log only when operation fails
    |--------------------------------------------------------------------------
    */
    public function logOnFailureOnly(
        callable $callback,
        array $args = [],
        string $operationName = "operation",
        ?string $loggingFailingMsg = null,
        array $loggingContext = [],
        bool $appendRequestDataToLog = true,
        bool $appendLoggedUserKeyToLog = true
    ): mixed {

        try {

            return $this->executeCallback($callback, $args);

        } catch (Exception $e) {

            $context = $this->prepareLoggingContext(
                $loggingContext,
                $operationName,
                $appendRequestDataToLog,
                $appendLoggedUserKeyToLog
            );

            Log::error(
                $loggingFailingMsg ?? $e->getMessage(),
                $context
            );

            return Response::error($e->getMessage());
        }
    }


    /*
    |--------------------------------------------------------------------------
    | 2) Log only when operation succeeds
    |--------------------------------------------------------------------------
    */
    public function logOnSuccessOnly(
        callable $callback,
        array $args = [],
        string $operationName = "operation",
        ?string $loggingSuccessMsg = null,
        array $loggingContext = [],
        bool $appendRequestDataToLog = true,
        bool $appendLoggedUserKeyToLog = true
    ): mixed {

        $result = $this->executeCallback($callback, $args);

        $context = $this->prepareLoggingContext(
            $loggingContext,
            $operationName,
            $appendRequestDataToLog,
            $appendLoggedUserKeyToLog
        );

        Log::info(
            $loggingSuccessMsg ?? "Operation succeeded",
            $context
        );

        return $result;
    }


    /*
    |--------------------------------------------------------------------------
    | 3) Log Start + Success + Fail (full operation lifecycle)
    |--------------------------------------------------------------------------
    */
    public function logOperationWithStatus(
        callable $callback,
        array $args = [],
        string $operationName = "operation",
        ?string $loggingStartingMsg = null,
        ?string $loggingSuccessMsg = null,
        ?string $loggingFailingMsg = null,
        array $loggingContext = [],
        bool $appendRequestDataToLog = true,
        bool $appendLoggedUserKeyToLog = true,
        bool $logStart = true,
        bool $logSuccess = true,
        bool $logFailure = true
    ): mixed {

        // Prepare context once for efficiency
        $context = $this->prepareLoggingContext(
            $loggingContext,
            $operationName,
            $appendRequestDataToLog,
            $appendLoggedUserKeyToLog
        );

        /*
        | Log starting (if enabled)
        */
        if ($logStart) {
            Log::info(
                $loggingStartingMsg ?? "Operation started",
                $context
            );
        }

        /*
        | Try execute
        */
        try {

            $result = $this->executeCallback($callback, $args);

            /*
            | Log success (if enabled)
            */
            if ($logSuccess) {
                Log::info(
                    $loggingSuccessMsg ?? "Operation succeeded",
                    $context
                );
            }

            return $result;

        } catch (Exception $e) {

            /*
            | Log failure (if enabled)
            */
            if ($logFailure) {
                Log::error(
                    $loggingFailingMsg ?? $e->getMessage(),
                    $context
                );
            }

            return Response::error($e->getMessage());
        }
    }
}

// trait ReadingOperationsLogging
// {
 
//     /*
//     |--------------------------------------------------------------------------
//     | Helpers to Prepare Logging Context
//     |--------------------------------------------------------------------------
//     */

//     protected function prepareLoggingContext(
//         array $context = [],
//         bool $appendRequest = true,
//         bool $appendUser = true,
//     ): array {
//         if ($appendUser && Auth::check())
//         {
//             $context['user_id'] = Auth::id();
//         }

//         if ($appendRequest)
//         {
//             $context['request'] = request()->all();
//         }

//         return $context;
//     }


//     /*
//     |--------------------------------------------------------------------------
//     | Core Executor with Fail Logging Only
//     |--------------------------------------------------------------------------
//     */

//     public function logOnFail(
//         callable $callback ,
//         array $args = [] ,
//         ?string $loggingFailingMsg = null ,
//         array $loggingContext = [] ,
//         bool $appendRequestDataToLog = true ,
//         bool $appendLoggedUserKeyToLog = true
//     ): mixed {

//         try {
//             return call_user_func($callback, ...$args);

//         } catch (Exception $e) {

//             $loggingContext = $this->prepareLoggingContext($loggingContext, $appendRequestDataToLog, $appendLoggedUserKeyToLog);

//             Log::error(
//                 $loggingFailingMsg ?? $e->getMessage(),
//                 $loggingContext
//             );

//             return Response::error($e->getMessage());
//         }
//     } 

//     /*
//     |--------------------------------------------------------------------------
//     | Public API: logOnRead (logs start + success)
//     |--------------------------------------------------------------------------
//     */

//     public function logOnRead(
//         callable $callback,
//         string $message,
//         array $args = [],
//         array $loggingContext = [],
//     ): mixed {

//         $loggingContext = $this->prepareLoggingContext($loggingContext);

//         Log::info($message . " - operation started ", $loggingContext);

//         $result = call_user_func($callback, ...$args);
 
//         Log::info( $message . " - operation finished ", $loggingContext);

//         return $result;
//     }


//     /*
//     |--------------------------------------------------------------------------
//     | Public API: logOnReadAndFail
//     |--------------------------------------------------------------------------
//     */

//     public function logOnReadAndFail(
//         string $readingMessage,
//         callable $callback,
//         array $args = [],
//         ?string $loggingFailingMsg  = null,
//         array $loggingContext  = [],
//     ): mixed {

//         Log::info($readingMessage , $this->prepareLoggingContext($loggingContext ));

//         return $this->logOnFail(
//             callback: $callback,
//             args: $args,
//             loggingFailingMsg : $loggingFailingMsg  ?? "Failed while $readingMessage",
//             loggingContext : $loggingContext ,
//         );
//     } 
// }