# LexofficeOrderConfirmationManager

This document explains the `LexofficeOrderConfirmationManager` class, its methods, and how to use them with examples. It is part of the Codersgarden `PhpLexofficeApi` package for interacting with LexOffice APIs.

---

## Class Overview

The `LexofficeOrderConfirmationManager` class provides the following functionalities:

1. **Create an Order Confirmation**  
2. **Retrieve an Order Confirmation by ID**  
3. **Render an Order Confirmation as a PDF**  
4. **Pursue a Sales Voucher to an Order Confirmation**  
5. **Generate Deeplinks to Order Confirmation**  

---

## Namespace

To use this class, include the namespace:
```php
use Codersgarden\PhpLexofficeApi\LexofficeOrderConfirmationManager;
```

---

## Methods Overview

### 1. **Create an Order Confirmation**
**Signature:**  
```php
public function create(array $orderData): array
```

**Description:**  
Creates an order confirmation in draft mode.

#### Parameters:
- **`$orderData`** *(array, required)*:  
  Contains all the required and optional fields for creating an order confirmation:
  - `voucherDate` *(string, required)*: ISO 8601 date format.
  - `address` *(array, required)*: Address details including `name`, `street`, `city`, etc.
  - `lineItems` *(array, required)*: List of items with `type`, `name`, `unitPrice`, etc.
  - `totalPrice` *(array, required)*: Pricing details.
  - `taxConditions` *(array, required)*: Tax settings like `taxType`.
  - `shippingConditions` *(array, required)*: Shipping details such as `shippingDate` and `shippingType`.
  - Optional fields: `title`, `introduction`, `remark`, `deliveryTerms`.

#### Example Controller Code:
```php
use Codersgarden\PhpLexofficeApi\LexofficeOrderConfirmationManager;

$orderData = [
    'voucherDate' => '2023-02-22T00:00:00.000+01:00',
    'address' => [
        'name' => 'Bike & Ride GmbH',
        'street' => 'Musterstraße 42',
        'city' => 'Freiburg',
        'zip' => '79112',
        'countryCode' => 'DE'
    ],
    'lineItems' => [
        [
            'type' => 'material',
            'name' => 'Bike Lock',
            'quantity' => 2,
            'unitName' => 'Piece',
            'unitPrice' => [
                'currency' => 'EUR',
                'netAmount' => 13.4,
                'taxRatePercentage' => 19
            ]
        ]
    ],
    'totalPrice' => ['currency' => 'EUR'],
    'taxConditions' => ['taxType' => 'net'],
    'shippingConditions' => [
        'shippingDate' => '2023-04-22T00:00:00.000+02:00',
        'shippingType' => 'delivery'
    ]
];

$manager = new LexofficeOrderConfirmationManager();
$response = $manager->create($orderData);
print_r($response);
```

---

### 2. **Retrieve an Order Confirmation**
**Signature:**  
```php
public function find(string $orderId): array
```

**Description:**  
Retrieves an order confirmation by its unique ID.

#### Parameters:
- **`$orderId`** *(string, required)*: The ID of the order confirmation.

#### Example Controller Code:
```php
$orderId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';

$manager = new LexofficeOrderConfirmationManager();
$response = $manager->find($orderId);
print_r($response);
```

---

### 3. **Render an Order Confirmation as a PDF**
**Signature:**  
```php
public function renderDocument(string $orderId): array
```

**Description:**  
Generates the PDF document for the given order confirmation.

#### Parameters:
- **`$orderId`** *(string, required)*: The ID of the order confirmation.

#### Example Controller Code:
```php
$orderId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';

$manager = new LexofficeOrderConfirmationManager();
$response = $manager->renderDocument($orderId);
print_r($response);
```

---

### 4. **Pursue a Sales Voucher to an Order Confirmation**
**Signature:**  
```php
public function pursue(string $precedingSalesVoucherId, array $orderData): array
```

**Description:**  
Creates a new order confirmation based on an existing sales voucher.

#### Parameters:
- **`$precedingSalesVoucherId`** *(string, required)*: ID of the preceding sales voucher.
- **`$orderData`** *(array, required)*: Data to create the order confirmation (same as `create` method).

#### Example Controller Code:
```php
$precedingSalesVoucherId = 'a1234567-bc89-41d0-a45f-67e89f012345';
$orderData = [
    'voucherDate' => '2023-03-01T00:00:00.000+01:00',
    // Additional required fields
];

$manager = new LexofficeOrderConfirmationManager();
$response = $manager->pursue($precedingSalesVoucherId, $orderData);
print_r($response);
```

---

### 5. **Generate Deeplinks to an Order Confirmation**
**Signature:**  
```php
public function getDeeplink(string $orderId, string $type = 'view'): string
```

**Description:**  
Generates a deeplink for viewing or editing the order confirmation.

#### Parameters:
- **`$orderId`** *(string, required)*: The ID of the order confirmation.
- **`$type`** *(string, optional)*: `'view'` or `'edit'`. Default is `'view'`.

#### Example Controller Code:
```php
$orderId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';

$manager = new LexofficeOrderConfirmationManager();
$viewLink = $manager->getDeeplink($orderId, 'view');
$editLink = $manager->getDeeplink($orderId, 'edit');

echo "View Link: $viewLink\n";
echo "Edit Link: $editLink\n";
```

---

## Expected Responses

### Success
```json
{
  "success": true,
  "data": {
    "id": "e9066f04-8cc7-4616-93f8-ac9ecc8479c8",
    "resourceUri": "https://api.lexoffice.io/v1/order-confirmations/66196c43-bfee-baf3-4335-d610367059db",
    "createdDate": "2023-06-29T15:15:09.447+02:00",
    "updatedDate": "2023-06-29T15:15:09.447+02:00",
    "version": 1
  }
}
```

### Error
```json
{
  "success": false,
  "status": 400,
  "error": "Invalid voucher data provided."
}
```

---

## Notes
- Use this class with the `PhpLexofficeApi` package's base configuration.
- Always validate data before sending it to the API.
