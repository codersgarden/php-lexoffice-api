# Usage

## Importing the Class

To use the LexOffice API package, first import the main class:

```php
use Codersgarden\PhpLexofficeApi\LexofficeContactManager;
```

## Example Usage in a Controller

Here's a basic example of using the package in a Laravel controller:

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

        // Output the response
        dd($response);
    }
}
```

## Other Usage Examples

### Creating a Contact

```php
$contactData = [/* your contact data here */];
$response = $lexofficeContactManager->create($contactData);
```

### Updating a Contact

```php
$contactId = 'your-contact-id';
$updateData = [/* updated contact data */];
$response = $lexofficeContactManager->update($contactId, $updateData);
```

### Retrieving a Contact

```php
$contactId = 'your-contact-id';
$response = $lexofficeContactManager->show($contactId);
```

### Retrieving All Contacts

```php
$response = $lexofficeContactManager->get();
```

### Deleting a Contact

```php
$contactId = 'your-contact-id';
$response = $lexofficeContactManager->delete($contactId);
```