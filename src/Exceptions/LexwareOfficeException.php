<?php

namespace Nodus\LexwareOfficeApi\Exceptions;

use Exception;
use Saloon\Http\Response;

/**
 * Base exception for Lexware Office API errors
 */
class LexwareOfficeException extends Exception
{
    public function __construct(
        string $message,
        public readonly ?Response $response = null,
        public readonly ?array $errors = null,
        int $code = 0,
        ?Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Create exception from API response
     */
    public static function fromResponse(Response $response): self
    {
        $data = $response->json();
        
        return new self(
            message: $data['message'] ?? 'Unknown API error',
            response: $response,
            errors: $data['errors'] ?? null,
            code: $response->status()
        );
    }

    /**
     * Get the HTTP status code
     */
    public function getStatusCode(): ?int
    {
        return $this->response?->status();
    }

    /**
     * Get the response body
     */
    public function getResponseBody(): ?array
    {
        return $this->response?->json();
    }

    /**
     * Check if this is a client error (4xx)
     */
    public function isClientError(): bool
    {
        $status = $this->getStatusCode();
        return $status >= 400 && $status < 500;
    }

    /**
     * Check if this is a server error (5xx)
     */
    public function isServerError(): bool
    {
        $status = $this->getStatusCode();
        return $status >= 500;
    }

    /**
     * Check if this is a validation error
     */
    public function isValidationError(): bool
    {
        return $this->getStatusCode() === 422 || !empty($this->errors);
    }

    /**
     * Get validation errors
     */
    public function getValidationErrors(): array
    {
        return $this->errors ?? [];
    }
}