```markdown
# LexofficeContactManager

## Description
The `LexofficeContactManager` class provides methods for managing contacts through the LexOffice API using Guzzle for HTTP requests. This class enables you to create, update, retrieve, and delete contacts with ease.

## Methods

### `create(array $contactData)`
**Description**: Creates a new contact in the LexOffice API.

**Parameters**:
- `$contactData` (array) — The contact data to be sent to the API.

**Returns**: 
- `array` — Response data from the API.

**Example**:
```php
$contactData = [
    "roles" => [
        "customer" => new \stdClass()
    ],
    "company" => [
        "name" => "Example Company",
        "contactPersons" => [
            [
                "lastName" => "Doe",
                "firstName" => "John",
            ]
        ]
    ]
];
$response = $lexofficeContactManager->create($contactData);
```

---

### `update(string $contactId, array $contactData)`
**Description**: Updates an existing contact in the LexOffice API.

**Parameters**:
- `$contactId` (string) — The ID of the contact to update.
- `$contactData` (array) — The updated contact data.

**Returns**:
- `array` — Response data from the API.

**Example**:
```php
$contactId = 'your-contact-id';
$updateData = [
    "company" => [
        "name" => "Updated Company Name",
    ]
];
$response = $lexofficeContactManager->update($contactId, $updateData);
```

---

### `show(string $contactId)`
**Description**: Retrieves a contact by ID from the LexOffice API.

**Parameters**:
- `$contactId` (string) — The ID of the contact to retrieve.

**Returns**:
- `array` — Response data from the API.

**Example**:
```php
$contactId = 'your-contact-id';
$response = $lexofficeContactManager->show($contactId);
```

---

### `get()`
**Description**: Retrieves all contacts from the LexOffice API.

**Returns**:
- `array` — Response data from the API.

**Example**:
```php
$response = $lexofficeContactManager->get();
```

---

### `delete(string $contactId)`
**Description**: Deletes a contact by ID from the LexOffice API.

**Parameters**:
- `$contactId` (string) — The ID of the contact to delete.

**Returns**:
- `array` — Response data from the API.

**Example**:
```php
$contactId = 'your-contact-id';
$response = $lexofficeContactManager->delete($contactId);
```