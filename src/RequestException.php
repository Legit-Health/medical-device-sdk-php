<?php

namespace LegitHealth\MedicalDevice;

use Exception;

final class RequestException extends Exception
{
    public function __construct(
        string $message,
        public readonly ?int $statusCode = null,
        public readonly ?array $content = null
    ) {
        parent::__construct($message);
    }
}
