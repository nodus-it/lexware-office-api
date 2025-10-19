# Lexware Office API Package

[![License](https://poser.pugx.org/nodus-it/lexware-office-api/license)](https://packagist.org/packages/nodus-it/lexware-office-api)
[![Latest Stable Version](https://poser.pugx.org/nodus-it/lexware-office-api/v/stable)](https://packagist.org/packages/nodus-it/lexware-office-api)
[![Total Downloads](https://poser.pugx.org/nodus-it/lexware-office-api/downloads)](https://packagist.org/packages/nodus-it/lexware-office-api)

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

The package provides extensive configuration options:

```env
# API Configuration
LEXWARE_OFFICE_API_URL=https://api.lexware.io/v1/
LEXWARE_OFFICE_CONNECT_TIMEOUT=30
LEXWARE_OFFICE_REQUEST_TIMEOUT=30

# Rate Limiting
LEXWARE_OFFICE_RATE_LIMIT_RPS=2
LEXWARE_OFFICE_BURST_LIMIT=10

# Pagination
LEXWARE_OFFICE_PAGE_SIZE=25
LEXWARE_OFFICE_MAX_PAGE_SIZE=250

# Caching (optional)
LEXWARE_OFFICE_CACHE_ENABLED=false
LEXWARE_OFFICE_CACHE_TTL=300

# Logging (optional)
LEXWARE_OFFICE_LOGGING_ENABLED=false
LEXWARE_OFFICE_LOG_LEVEL=info
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
use Nodus\LexwareOfficeApi\Testing\LexwareOfficeTestCase;

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

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

The MIT License (MIT). Please see [License File](LICENCE) for more information.
