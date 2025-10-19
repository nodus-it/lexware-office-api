<?php

namespace Nodus\LexwareOfficeApi\Testing;

use Nodus\LexwareOfficeApi\Utils\LexwareOfficeConnector;
use PHPUnit\Framework\TestCase;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

/**
 * Base test case for Lexware Office API tests
 */
abstract class LexwareOfficeTestCase extends TestCase
{
    protected LexwareOfficeConnector $connector;
    protected MockClient $mockClient;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->mockClient = new MockClient();
        $this->connector = new LexwareOfficeConnector();
        $this->connector->withMockClient($this->mockClient);
    }

    /**
     * Mock a successful API response
     */
    protected function mockSuccessResponse(array $data = [], int $status = 200): MockResponse
    {
        return MockResponse::make($data, $status);
    }

    /**
     * Mock an error response
     */
    protected function mockErrorResponse(string $message = 'API Error', int $status = 400, array $errors = []): MockResponse
    {
        return MockResponse::make([
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    /**
     * Mock a paginated response
     */
    protected function mockPaginatedResponse(array $items, int $page = 1, int $size = 25, int $totalPages = 1): MockResponse
    {
        return MockResponse::make([
            'content' => $items,
            'first' => $page === 1,
            'last' => $page === $totalPages,
            'number' => $page - 1, // API uses 0-based page numbers
            'numberOfElements' => count($items),
            'size' => $size,
            'totalElements' => count($items) * $totalPages,
            'totalPages' => $totalPages,
        ]);
    }

    /**
     * Mock a validation error response
     */
    protected function mockValidationErrorResponse(array $errors): MockResponse
    {
        return MockResponse::make([
            'message' => 'Validation failed',
            'errors' => $errors,
        ], 422);
    }

    /**
     * Mock a not found response
     */
    protected function mockNotFoundResponse(string $resource = 'Resource'): MockResponse
    {
        return MockResponse::make([
            'message' => "{$resource} not found",
        ], 404);
    }

    /**
     * Mock an unauthorized response
     */
    protected function mockUnauthorizedResponse(): MockResponse
    {
        return MockResponse::make([
            'message' => 'Unauthorized',
        ], 401);
    }

    /**
     * Mock a rate limit response
     */
    protected function mockRateLimitResponse(): MockResponse
    {
        return MockResponse::make([
            'message' => 'Rate limit exceeded',
        ], 429);
    }

    /**
     * Assert that a request was sent
     */
    protected function assertRequestSent(string $method, string $url): void
    {
        $this->mockClient->assertSent(function ($request) use ($method, $url) {
            return $request->getMethod() === strtoupper($method) && 
                   str_contains($request->getUri(), $url);
        });
    }

    /**
     * Assert that no requests were sent
     */
    protected function assertNoRequestsSent(): void
    {
        $this->mockClient->assertNothingSent();
    }

    /**
     * Assert that a specific number of requests were sent
     */
    protected function assertRequestCount(int $count): void
    {
        $this->mockClient->assertSentCount($count);
    }

    /**
     * Get sample article data
     */
    protected function getSampleArticleData(): array
    {
        return [
            'id' => 'article-123',
            'title' => 'Sample Article',
            'articleNumber' => 'ART-001',
            'description' => 'Sample article description',
            'unitName' => 'Stück',
            'unitPrice' => [
                'currency' => 'EUR',
                'netAmount' => 19.99,
                'grossAmount' => 23.79,
                'taxRatePercentage' => 19.0,
            ],
            'taxRatePercentage' => 19.0,
            'version' => 1,
        ];
    }

    /**
     * Get sample contact data
     */
    protected function getSampleContactData(): array
    {
        return [
            'id' => 'contact-123',
            'organizationName' => 'Sample Company',
            'person' => [
                'salutation' => 'Herr',
                'firstName' => 'Max',
                'lastName' => 'Mustermann',
            ],
            'addresses' => [
                'billing' => [
                    'street' => 'Musterstraße 1',
                    'zip' => '12345',
                    'city' => 'Musterstadt',
                    'countryCode' => 'DE',
                ],
            ],
            'version' => 1,
        ];
    }

    /**
     * Get sample invoice data
     */
    protected function getSampleInvoiceData(): array
    {
        return [
            'id' => 'invoice-123',
            'organizationId' => 'org-123',
            'createdDate' => '2023-01-01T00:00:00.000+01:00',
            'updatedDate' => '2023-01-01T00:00:00.000+01:00',
            'version' => 1,
            'language' => 'de',
            'archived' => false,
            'voucherStatus' => 'draft',
            'voucherNumber' => 'RE-001',
            'voucherDate' => '2023-01-01T00:00:00.000+01:00',
            'address' => [
                'contactId' => 'contact-123',
                'name' => 'Max Mustermann',
                'street' => 'Musterstraße 1',
                'zip' => '12345',
                'city' => 'Musterstadt',
                'countryCode' => 'DE',
            ],
            'lineItems' => [
                [
                    'id' => 'line-1',
                    'type' => 'custom',
                    'name' => 'Sample Item',
                    'description' => 'Sample item description',
                    'quantity' => 1,
                    'unitName' => 'Stück',
                    'unitPrice' => [
                        'currency' => 'EUR',
                        'netAmount' => 19.99,
                        'grossAmount' => 23.79,
                        'taxRatePercentage' => 19.0,
                    ],
                    'lineItemAmount' => 23.79,
                ],
            ],
            'totalPrice' => [
                'currency' => 'EUR',
                'totalNetAmount' => 19.99,
                'totalGrossAmount' => 23.79,
                'totalTaxAmount' => 3.80,
            ],
            'taxAmounts' => [
                [
                    'taxRatePercentage' => 19.0,
                    'taxAmount' => 3.80,
                    'netAmount' => 19.99,
                ],
            ],
        ];
    }
}