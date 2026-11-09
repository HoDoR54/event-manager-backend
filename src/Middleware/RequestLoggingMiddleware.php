<?php

declare(strict_types=1);

namespace HponeTuakNyi\EventManagerBackend\Middleware;

use HponeTuakNyi\EventManagerBackend\Support\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

final class RequestLoggingMiddleware implements MiddlewareInterface
{
    private const BODY_LIMIT = 2000;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $start = microtime(true);
        $method = $request->getMethod();
        $path = $request->getUri()->getPath();
        $body = (string) $request->getBody();
        $request->getBody()->rewind();

        Logger::info('Request received', [
            'method' => $method,
            'path' => $path,
            'contentType' => $request->getHeaderLine('Content-Type'),
            'body' => $body === '' ? null : $this->truncate($body),
        ]);

        try {
            $response = $handler->handle($request);
        } catch (Throwable $e) {
            Logger::error('Request failed with unhandled exception', [
                'method' => $method,
                'path' => $path,
                'exception' => $e::class,
                'message' => $e->getMessage(),
                'file' => $e->getFile() . ':' . $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'durationMs' => $this->elapsedMs($start),
            ]);
            throw $e;
        }

        Logger::info('Request completed', [
            'method' => $method,
            'path' => $path,
            'status' => $response->getStatusCode(),
            'durationMs' => $this->elapsedMs($start),
        ]);

        return $response;
    }

    private function truncate(string $value): string
    {
        return strlen($value) > self::BODY_LIMIT
            ? substr($value, 0, self::BODY_LIMIT) . '...[truncated]'
            : $value;
    }

    private function elapsedMs(float $start): float
    {
        return round((microtime(true) - $start) * 1000, 2);
    }
}
