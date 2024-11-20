# Getting Started

## Description
The LexOffice API Package for Laravel provides an easy-to-use interface for interacting with the LexOffice API. It allows Laravel developers to manage contacts and other data seamlessly using familiar tools like Guzzle and Laravel's built-in HTTP client capabilities.

## Installation

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

## Requirements

- **PHP**: >=7.3
- **Laravel**: 8.x, 9.x, 10.x, 11.x
- **Dependencies**:
  - guzzlehttp/guzzle: ^7.0
  - "illuminate/http": "^7.0|^8.0|^9.0|^10.0|^11.0"

### Development Dependencies

- mockery/mockery: ^1.0
- orchestra/testbench: ^6.0
- phpunit/phpunit: ^9.0

---

## Configuration

To customize configuration values, publish the package's configuration file using the following command:

```bash
php artisan vendor:publish --provider="Codersgarden\PhpLexofficeApi\LexofficeServiceProvider" --tag=config
```

This will create a `config/lexoffice.php` file in your Laravel application where you can set your configuration options:

### `config/lexoffice.php`

```php
return [
    'base_uri' => env('LEXOFFICE_BASE_URI', 'https://api.lexoffice.io/v1/'),
    'api_token' => env('LEXOFFICE_API_TOKEN', ''),
    'timeout' => env('LEXOFFICE_TIMEOUT', 30), // Request timeout in seconds
];
```

### Setting Environment Variables

You should add the following environment variables to your `.env` file to configure your LexOffice API settings:

```env
LEXOFFICE_BASE_URI=https://api.lexoffice.io/v1/
LEXOFFICE_API_TOKEN=your_token_here
LEXOFFICE_TIMEOUT=30
```

- **`LEXOFFICE_BASE_URI`**: Base URI for the LexOffice API. The default is `https://api.lexoffice.io/v1/`.
- **`LEXOFFICE_API_TOKEN`**: Your LexOffice API token. This is required for authentication.
- **`LEXOFFICE_TIMEOUT`**: Request timeout in seconds. Default is 30 seconds.
```
