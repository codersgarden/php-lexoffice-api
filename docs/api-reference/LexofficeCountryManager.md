# LexofficeCountryManager Class Documentation

The `LexofficeCountryManager` class provides functionality for retrieving all available countries from the LexOffice API. It enables the retrieval of country codes, names (in English and German), and tax classifications.

---

## Table of Contents

1. [Using the LexOffice API Package](#using-the-lexoffice-api-package)
2. [Retrieving All Countries](#retrieving-all-countries)
3. [Example Usage](#example-usage)
4. [Response Structure](#response-structure)
5. [Error Handling](#error-handling)

---

## Using the LexOffice API Package

To use the LexOffice API package, first import the main class:

```php
use CodersGarden\LexOfficeApi\LexofficeCountryManager;
```

---

## Retrieving All Countries

This method retrieves the list of countries known to LexOffice. Each country in the response contains the following details:

- **Country Code** (`countryCode`): ISO 3166 alpha-2 code for the country.
- **Country Name in German** (`countryNameDE`): The country's name in German.
- **Country Name in English** (`countryNameEN`): The country's name in English.
- **Tax Classification** (`taxClassification`): Indicates the country's tax classification (e.g., `de`, `intraCommunity`).

### Method Signature

```php
public function all(): array
```

### Parameters

- None.

### Return Value

- `success`: Indicates whether the request was successful (`true` or `false`).
- `data`: Contains an array of countries on success.
- `status`: The HTTP status code on failure.
- `error`: Error message if the request fails.

---

## Example Usage

### Retrieve All Countries

```php
$lexofficeCountryManager = new LexofficeCountryManager();
$response = $lexofficeCountryManager->all();

if ($response['success']) {
    print_r($response['data']);
} else {
    echo 'Error: ' . $response['error'];
}
```

---

## Response Structure

### Successful Response

On success, the response contains an array of countries with the following structure:

```json
[
    {
        "countryCode": "DE",
        "countryNameDE": "Deutschland",
        "countryNameEN": "Germany",
        "taxClassification": "de"
    },
    {
        "countryCode": "FR",
        "countryNameDE": "Frankreich",
        "countryNameEN": "France",
        "taxClassification": "intraCommunity"
    }
]
```

### Error Response

On failure, the response contains details about the error:

```php
[
    'success' => false,
    'status' => 404,
    'error' => 'The requested resource was not found.'
]
```

---

## Error Handling

If the API request fails, the method returns an error response with the following details:

- `success`: `false`.
- `status`: The HTTP status code of the failed request.
- `error`: A descriptive error message.

### Example

```php
$response = $lexofficeCountryManager->all();

if (!$response['success']) {
    echo 'Error (' . $response['status'] . '): ' . $response['error'];
}
```