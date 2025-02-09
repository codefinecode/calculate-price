<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Psr\Log\LoggerInterface;

class ExceptionListener
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        // Логируем ошибку
        $this->logger->error($exception->getMessage(), [
            'exception' => $exception,
        ]);

        $response = new JsonResponse([
            'error' => $exception->getMessage(),
        ], $this->getStatusCode($exception));

        $event->setResponse($response);
    }

    private function getStatusCode(\Throwable $exception): int
    {
        return match (true) {
            $exception instanceof ValidationFailedException => 422,
            default => 400
        };
    }
}