<?php

namespace LegitHealth\MedicalDevice;

use Exception;

final class LoginException extends Exception
{
    public function __construct(
        string $message,
        public readonly ?int $statusCode = null
    ) {
        parent::__construct($message);
    }
}
