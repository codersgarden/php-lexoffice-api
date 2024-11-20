# LexofficeContactManager Documentation

The `LexofficeContactManager` class provides a set of methods for interacting with LexOffice's contacts API. It allows you to create, update, retrieve, delete, and filter contacts using the Guzzle HTTP client.

---

## Table of Contents

- [Using the LexOffice API Package](#using-the-lexoffice-api-package)
- [Creating a Contact](#creating-a-contact)
- [Retrieving a Contact by ID](#retrieving-a-contact-by-id)
- [Updating a Contact](#updating-a-contact)
- [Deleting a Contact](#deleting-a-contact)
- [Retrieving All Contacts with Filters](#retrieving-all-contacts-with-filters)

---

### Using the LexOffice API Package

To use the LexOffice API package, first import the main class:

```php
use Codersgarden\PhpLexofficeApi\LexofficeContactManager;
```

---

### Creating a Contact

Creates a new contact in LexOffice using the provided data.

#### Example Usage

```php
$contactData = [
    "roles" => [
        "customer" => new \stdClass() // Assigns the 'customer' role
    ],
    "company" => [
        "name" => "Test Company",
        "taxNumber" => null,
        "vatRegistrationId" => null,
        "allowTaxFreeInvoices" => false,
        "contactPersons" => [
            [
                "lastName" => "Doe",
                "firstName" => "John",
                "primary" => true, // Setting as primary contact person
                "emailAddress" => "john.doe@example.com",
                "phoneNumber" => "1234567890"
            ]
        ]
    ],
    "addresses" => [
        "billing" => [
            [
                "street" => "Test Street",
                "zip" => "12345",
                "city" => "Test City",
                "countryCode" => "DE" // ISO 3166 alpha-2 country code
            ]
        ],
        "shipping" => []
    ],
    "emailAddresses" => [
        "business" => ["business@example.com"]
    ],
    "phoneNumbers" => [
        "business" => ["0123456789"]
    ],
    "note" => "This is a test contact note.",
    "version" => 0 // Version set to 0 for creation
];

$response = $lexofficeContactManager->create($contactData);
dd($response); // Output response for debugging
```

#### Method Signature

```php
public function create(array $contactData): array
```

#### Parameters

- `array $contactData`: An associative array containing the data for the contact.

#### Response

- `success`: `true` if the request was successful, `false` otherwise.
- `data`: The response data from the LexOffice API on success.
- `status`: The HTTP status code on failure.
- `error`: The error message on failure.

---

### Retrieving a Contact by ID

Retrieves a contact by its unique identifier.

#### Example Usage

```php
$contactId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8'; // Example contact ID
$response = $lexofficeContactManager->find($contactId);
dd($response);
```

#### Method Signature

```php
public function find(string $contactId): array
```

#### Parameters

- `string $contactId`: The unique identifier of the contact.

#### Response

- `success`: `true` if the request was successful, `false` otherwise.
- `data`: The contact data from the LexOffice API on success.
- `status`: The HTTP status code on failure.
- `error`: The error message on failure.

---

### Updating a Contact

Updates an existing contact in LexOffice.

#### Example Usage

```php
$updatedContactData = [
    "roles" => [
        "customer" => new \stdClass() // Retaining 'customer' role
    ],
    "company" => [
        "name" => "Updated Company Name",
        "taxNumber" => "123456789",
        "vatRegistrationId" => "DE123456789",
        "allowTaxFreeInvoices" => true,
        "contactPersons" => [
            [
                "lastName" => "Smith",
                "firstName" => "Jane",
                "primary" => true,
                "emailAddress" => "jane.smith@example.com",
                "phoneNumber" => "0987654321"
            ]
        ]
    ],
    "addresses" => [
        "billing" => [
            [
                "street" => "Updated Street",
                "zip" => "54321",
                "city" => "Updated City",
                "countryCode" => "DE"
            ]
        ],
        "shipping" => []
    ],
    "emailAddresses" => [
        "business" => ["updated@example.com"]
    ],
    "phoneNumbers" => [
        "business" => ["9876543210"]
    ],
    "note" => "Updated contact note.",
    "version" => 1 // Incremented version for updating
];

$contactId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8'; // Example contact ID
$response = $lexofficeContactManager->update($contactId, $updatedContactData);
dd($response);
```

#### Method Signature

```php
public function update(string $contactId, array $contactData): array
```

#### Parameters

- `string $contactId`: The unique identifier of the contact to be updated.
- `array $contactData`: The data to update for the contact.

#### Response

- `success`: `true` if the request was successful, `false` otherwise.
- `data`: The updated contact data from the LexOffice API on success.
- `status`: The HTTP status code on failure.
- `error`: The error message on failure.

---

### Deleting a Contact

Deletes a contact by its unique identifier.

#### Example Usage

```php
$contactId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8'; // Example contact ID
$response = $lexofficeContactManager->delete($contactId);
dd($response);
```

#### Method Signature

```php
public function delete(string $contactId): array
```

#### Parameters

- `string $contactId`: The unique identifier of the contact.

#### Response

- `success`: `true` if the request was successful, `false` otherwise.
- `data`: The response data from the LexOffice API on success.
- `status`: The HTTP status code on failure.
- `error`: The error message on failure.

---

### Retrieving All Contacts with Filters

Retrieves all contacts or applies filters.

#### Example Usage

```php
$filters = [
    'name' => 'Aureus' // Example filter for contact name
];
$response = $lexofficeContactManager->all($filters);
dd($response);
```

#### Method Signature

```php
public function all(array $filters = []): array
```

#### Parameters

- `array $filters`: Optional. An associative array of filter parameters.

#### Response

- `success`: `true` if the request was successful, `false` otherwise.
- `data`: The contacts data from the LexOffice API on success.
- `status`: The HTTP status code on failure.
- `error`: The error message on failure.
```
