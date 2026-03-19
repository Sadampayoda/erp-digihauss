<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait Loggable
{
    protected function logInfo(string $message, array $context = [])
    {
        Log::channel($this->logChannel())->info(
            $this->formatMessage($message),
            $this->formatContext($context)
        );
    }

    protected function logWarning(string $message, array $context = [])
    {
        Log::channel($this->logChannel())->warning(
            $this->formatMessage($message),
            $this->formatContext($context)
        );
    }

    protected function logError(string $message, array $context = [])
    {
        Log::channel($this->logChannel())->error(
            $this->formatMessage($message),
            $this->formatContext($context)
        );
    }

    protected function logStart(string $process, array $context = [])
    {
        $this->logInfo("START: {$process}", $context);
    }

    protected function logSuccess(string $process, array $context = [])
    {
        $this->logInfo("SUCCESS: {$process}", $context);
    }

    protected function logFailed(string $process, \Throwable $e, array $context = [])
    {
        $this->logError("FAILED: {$process}", array_merge($context, [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]));
    }

    protected function formatMessage(string $message): string
    {
        return "[APP] {$message}";
    }

    protected function formatContext(array $context): array
    {
        return array_merge([
            'time' => now()->toDateTimeString(),
            'class' => static::class,
            'user_id' => auth()->id() ?? null,
        ], $context);
    }

    protected function logChannel(): string
    {
        return 'daily'; 
    }
}
