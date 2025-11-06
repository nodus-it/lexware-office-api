# Lexware Office API Package

[![Tests](https://github.com/nodus-it/lexware-office-api/workflows/Tests/badge.svg)](https://github.com/nodus-it/lexware-office-api/actions/workflows/tests.yml)
[![Coverage](https://github.com/nodus-it/lexware-office-api/workflows/Coverage/badge.svg)](https://github.com/nodus-it/lexware-office-api/actions/workflows/coverage.yml)
[![License](https://poser.pugx.org/nodus-it/lexware-office-api/license)](https://packagist.org/packages/nodus-it/lexware-office-api)
[![Latest Stable Version](https://poser.pugx.org/nodus-it/lexware-office-api/v/stable)](https://packagist.org/packages/nodus-it/lexware-office-api)
[![Total Downloads](https://poser.pugx.org/nodus-it/lexware-office-api/downloads)](https://packagist.org/packages/nodus-it/lexware-office-api)
[![PHP Version Require](https://poser.pugx.org/nodus-it/lexware-office-api/require/php)](https://packagist.org/packages/nodus-it/lexware-office-api)

A comprehensive PHP package for interacting with the Lexware Office API, built on top of the powerful Saloon HTTP client. This package provides a clean, object-oriented interface for managing articles, contacts, invoices, and other entities in Lexware Office.

## Features

- 🚀 **Modern Architecture**: Built with PHP 8.1+ features and best practices
- 🔧 **Extensible Design**: Easy to add new API endpoints and entities
- 📊 **Comprehensive Data Handling**: Type-safe data objects with validation
- 🔄 **Automatic Pagination**: Built-in support for paginated API responses
- ⚡ **Rate Limiting**: Automatic rate limiting to comply with API limits
- 🧪 **Testing Support**: Comprehensive testing utilities and mock responses
- 📝 **Rich Documentation**: Extensive PHPDoc and inline documentation
- 🛡️ **Error Handling**: Centralized exception handling with detailed error information

## Installation

You can install the package via composer:

```bash
composer require nodus-it/lexware-office-api
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="lexware-office"
```

Set your API token in your `.env` file (available at https://app.lexoffice.de/addons/public-api):

```env
LEXWARE_OFFICE_API_TOKEN=your-token-here
```

## Configuration

The package provides the following configuration options:

```env
# Authentication
LEXWARE_OFFICE_API_TOKEN=your-token-here

# API timeouts
LEXWARE_OFFICE_CONNECT_TIMEOUT=30
LEXWARE_OFFICE_REQUEST_TIMEOUT=30

# Rate limiting
# Cache store used by the rate limiter (Laravel cache store name)
LEXWARE_OFFICE_RATE_LIMIT_STORE=file
```

## Usage

### Basic Usage

```php
use Nodus\LexwareOfficeApi\Utils\LexwareOfficeConnector;
use Nodus\LexwareOfficeApi\Resources\ArticleResource;

// Create connector
$connector = new LexwareOfficeConnector();

// Create resource
$articles = new ArticleResource($connector);

// Get all articles
$allArticles = $articles->all();

// Get specific article
$article = $articles->get('article-id');

// Create new article
$newArticle = $articles->create(ArticleData::from([
    'title' => 'New Product',
    'articleNumber' => 'PROD-001',
    'description' => 'Product description',
    'unitName' => 'Stück',
    'price' => [
        'currency' => 'EUR',
        'netAmount' => 19.99,
        'grossAmount' => 23.79,
        'taxRatePercentage' => 19.0,
    ],
]));
```

### Using the Resource Factory

```php
use Nodus\LexwareOfficeApi\Utils\ResourceFactory;
use Nodus\LexwareOfficeApi\Utils\LexwareOfficeConnector;

$connector = new LexwareOfficeConnector();

// Create resources using factory
$articles = ResourceFactory::create('articles', $connector);
$contacts = ResourceFactory::create('contacts', $connector);
$invoices = ResourceFactory::create('invoices', $connector);
```

### Advanced Filtering

```php
use Nodus\LexwareOfficeApi\Data\Enums\ArticleType;

// Filter articles by type
$serviceArticles = $articles->findByType(ArticleType::SERVICE);

// Filter by article number
$specificArticles = $articles->findByArticleNumber('PROD-001');

// Filter by GTIN
$gtinArticles = $articles->findByGtin('1234567890123');

// Complex filtering
$filteredArticles = $articles->all(
    filterType: ArticleType::PRODUCT,
    filterArticleNumber: 'PROD-*'
);
```

### Error Handling

```php
use Nodus\LexwareOfficeApi\Exceptions\LexwareOfficeException;

try {
    $article = $articles->get('non-existent-id');
} catch (LexwareOfficeException $e) {
    if ($e->isValidationError()) {
        $errors = $e->getValidationErrors();
        // Handle validation errors
    } elseif ($e->isClientError()) {
        // Handle client errors (4xx)
    } elseif ($e->isServerError()) {
        // Handle server errors (5xx)
    }
    
    echo "Error: " . $e->getMessage();
    echo "Status: " . $e->getStatusCode();
}
```

### Pagination

```php
// Iterate through all pages
foreach ($articles->all() as $article) {
    echo $article->title . "\n";
}

// Manual pagination
$paginator = $articles->all();
$firstPage = $paginator->items(); // Get items from first page

if ($paginator->hasNextPage()) {
    $nextPage = $paginator->nextPage();
}
```

## Testing

The package includes comprehensive testing utilities:

```php
use Nodus\LexwareOfficeApi\Tests\LexwareOfficeTestCase;

class ArticleTest extends LexwareOfficeTestCase
{
    public function test_can_get_article()
    {
        // Mock successful response
        $this->mockClient->addResponse(
            $this->mockSuccessResponse($this->getSampleArticleData())
        );
        
        $articles = new ArticleResource($this->connector);
        $article = $articles->get('article-123');
        
        $this->assertEquals('Sample Article', $article->title);
        $this->assertRequestSent('GET', 'articles/article-123');
    }
    
    public function test_handles_validation_errors()
    {
        $this->mockClient->addResponse(
            $this->mockValidationErrorResponse([
                'title' => ['Title is required']
            ])
        );
        
        $this->expectException(LexwareOfficeException::class);
        
        $articles = new ArticleResource($this->connector);
        $articles->create(ArticleData::from([]));
    }
}
```

## Architecture

### Base Classes

- **BaseResource**: Template pattern for all API resources
- **BaseRequest**: Abstract base for all API requests with common functionality
- **BaseData**: Enhanced data objects with validation and transformation
- **LexwareOfficeException**: Centralized error handling

### Request Types

- **BaseGetRequest**: For retrieving single entities
- **BaseListRequest**: For retrieving collections with pagination
- **BaseCreateRequest**: For creating new entities
- **BaseUpdateRequest**: For updating existing entities
- **BaseDeleteRequest**: For deleting entities

### Data Traits

- **HasTimestamps**: Adds created/updated timestamp handling
- **HasVersioning**: Adds version control for optimistic locking

## Extending the Package

### Adding New Resources

1. Create a new resource class extending `BaseResource`:

```php
class ContactResource extends BaseResource
{
    protected function getEndpoint(): string
    {
        return 'contacts';
    }
    
    protected function getRequestNamespace(): string
    {
        return 'Nodus\\LexwareOfficeApi\\Requests\\Contacts';
    }
    
    protected function getDataClass(): string
    {
        return ContactData::class;
    }
}
```

2. Register the resource in the factory:

```php
ResourceFactory::register('contacts', ContactResource::class);
```

### Creating Request Classes

```php
class GetContactRequest extends BaseGetRequest
{
    protected function resolveEndpoint(): string
    {
        return "contacts/{$this->id}";
    }
}
```

### Creating Data Classes

```php
class ContactData extends BaseData
{
    use HasTimestamps;
    use HasVersioning;
    
    public string $id;
    public ?string $organizationName;
    public ?PersonData $person;
    public ?AddressData $addresses;
}
```

## API Coverage

Currently implemented:
- ✅ Articles (complete)

Planned:
- 🔄 Contacts
- 🔄 Invoices
- 🔄 Vouchers
- 🔄 Quotations
- 🔄 Credit Notes
- 🔄 Order Confirmations
- 🔄 Delivery Notes
- 🔄 Recurring Templates
- 🔄 Payment Conditions
- 🔄 Countries
- 🔄 Files
- 🔄 Profile
- 🔄 Event Subscriptions

## Development & Testing

This package uses GitHub Actions for continuous integration and testing:

### Automated Testing
- **Multi-PHP Version Testing**: Tests run on PHP 8.1, 8.2, and 8.3
- **Dependency Matrix**: Tests with both `prefer-lowest` and `prefer-stable` dependencies
- **Code Coverage**: Comprehensive coverage reporting with Codecov integration
- **Code Quality**: Automated checks with PHPCS, PHPStan, and PHPMD
- **Security Audits**: Regular dependency vulnerability scanning

### Running Tests Locally

```bash
# Install dependencies
composer install

# Run tests
vendor/bin/phpunit

# Run tests with coverage
vendor/bin/phpunit --coverage-html coverage

# Run code quality checks (if tools are installed)
vendor/bin/phpcs --standard=PSR12 src/ tests/
vendor/bin/phpstan analyse src/ --level=5
vendor/bin/phpmd src/ text cleancode,codesize,controversial,design,naming,unusedcode
```

### Continuous Integration

The project includes several GitHub Actions workflows:

- **Tests**: Runs on every push and pull request
- **Coverage**: Generates and uploads coverage reports
- **Security**: Weekly dependency security audits
- **Release**: Automated releases for tagged versions

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

### Development Guidelines

1. Follow PSR-12 coding standards
2. Write comprehensive tests for new features
3. Update documentation for API changes
4. Ensure all CI checks pass before submitting PR

## License

The MIT License (MIT). Please see [License File](LICENCE) for more information.
