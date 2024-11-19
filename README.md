## LexOffice API Package for Laravel

A Laravel package designed for seamless interaction with LexOffice APIs, allowing developers to manage contacts and other related data with ease. The package leverages popular tools such as Guzzle for HTTP requests and integrates with Laravel's built-in HTTP client.

---

### Table of Contents

1. [Installation](#installation)
2. [Requirements](#requirements)
3. [Configuration](#configuration)
4. [Usage](#usage)
   - [Creating a Contact](#creating-a-contact)
   - [Updating a Contact](#updating-a-contact)
   - [Retrieving a Contact](#retrieving-a-contact)
   - [Retrieving All Contacts](#retrieving-all-contacts)
   - [Deleting a Contact](#deleting-a-contact)
5. [Advanced Usage](#advanced-usage)
6. [Testing](#testing)
7. [Contributing](#contributing)
8. [License](#license)

---

### Installation

You can install the package via Composer:

```bash
composer require codersgarden/php-lexoffice-api
```

After installation, add the service provider to your `config/app.php` file (if you are not using auto-discovery):

```php
'providers' => [
    // Other service providers...
    Codersgarden\PhpLexofficeApi\LexofficeServiceProvider::class,
];
```

### Requirements

- **PHP**: >=7.4
- **Laravel**: 9.x, 10.x, 11.x
- **Dependencies**:
  - guzzlehttp/guzzle: ^7.0
  - illuminate/http: ^9.0|^10.0|^11.0

#### Development Dependencies

- mockery/mockery: ^1.0
- orchestra/testbench: ^6.0
- phpunit/phpunit: ^9.0

### Configuration

To customize configuration values, publish the package's configuration file using the following command:

```bash
php artisan vendor:publish --provider="Codersgarden\PhpLexofficeApi\LexofficeServiceProvider" --tag=config
```

This will create a `config/lexoffice.php` file in your Laravel application where you can set your configuration options:

#### `config/lexoffice.php`

```php
return [
    'base_uri' => env('LEXOFFICE_BASE_URI', 'https://api.lexoffice.io/v1/'),
    'api_token' => env('LEXOFFICE_API_TOKEN', ''),
    'timeout' => env('LEXOFFICE_TIMEOUT', 30), // Request timeout in seconds
];
```

#### Setting Environment Variables

You should add the following environment variables to your `.env` file to configure your LexOffice API settings:

```env
LEXOFFICE_BASE_URI=https://api.lexoffice.io/v1/
LEXOFFICE_API_TOKEN=your_token_here
LEXOFFICE_TIMEOUT=30
```

- **`LEXOFFICE_BASE_URI`**: Base URI for the LexOffice API. The default is `https://api.lexoffice.io/v1/`.
- **`LEXOFFICE_API_TOKEN`**: Your LexOffice API token. This is required for authentication.
- **`LEXOFFICE_TIMEOUT`**: Request timeout in seconds. Default is 30 seconds.

---

### Usage

#### Importing the Class

```php
use Codersgarden\PhpLexofficeApi\LexofficeContactManager;
```

#### Example Usage in a Controller

```php
class LexofficeController extends Controller
{
    protected $lexofficeContactManager;

    public function __construct(LexofficeContactManager $lexofficeContactManager)
    {
        $this->lexofficeContactManager = $lexofficeContactManager;
    }

    public function index()
    {
        // Example data for creating a contact
        $contactData = [
            "roles" => [
                "customer" => new \stdClass()
            ],
            "company" => [
                "name" => "Test Company",
                "contactPersons" => [
                    [
                        "lastName" => "Test",
                        "firstName" => "Test",
                    ]
                ]
            ],
            "addresses" => [
                "billing" => [
                    [
                        "street" => "Test Street",
                        "zip" => "12345",
                        "city" => "Test City",
                        "countryCode" => "DE"
                    ]
                ],
                "shipping" => []
            ],
            "emailAddresses" => [
                "business" => ["test@t.de"]
            ],
            "phoneNumbers" => [
                "business" => ["0123456789"]
            ],
            "note" => "Test Note",
            "version" => 0,
            "archived" => false
        ];

        // Create a new contact
        $response = $this->lexofficeContactManager->create($contactData);
        dd($response);
    }
}
```

---

### API Methods

#### Creating a Contact

```php
$contactData = [/* your contact data here */];
$response = $lexofficeContactManager->create($contactData);
```

#### Updating a Contact

```php
$contactId = 'your-contact-id';
$updateData = [/* updated contact data */];
$response = $lexofficeContactManager->update($contactId, $updateData);
```

#### Retrieving a Contact

```php
$contactId = 'your-contact-id';
$response = $lexofficeContactManager->show($contactId);
```

#### Retrieving All Contacts

```php
$response = $lexofficeContactManager->get();
```

#### Deleting a Contact

```php
$contactId = 'your-contact-id';
$response = $lexofficeContactManager->delete($contactId);
```

---

### Advanced Usage

You can customize the behavior of the `Guzzle` client by modifying the configuration file located at `config/lexoffice.php`. This can include setting custom headers, base URIs, or timeouts as needed.

### Testing

To run tests, use:

```bash
vendor/bin/phpunit
```

Make sure to include any necessary configuration values in your `.env` file for testing purposes.

### Contributing

Thank you for considering contributing! Please feel free to submit a pull request or open an issue if you find a bug or want to request a new feature.

### License

This package is open-source software licensed under the [MIT license](LICENSE).

---

This updated documentation includes instructions for configuring the `lexoffice.php` file and setting relevant environment variables. Let me know if there's anything else you'd like to add or modify!