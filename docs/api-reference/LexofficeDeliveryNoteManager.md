# **LexofficeDeliveryNoteManager Class Documentation**

The `LexofficeDeliveryNoteManager` class provides seamless integration with the LexOffice API for managing delivery notes. This class supports operations such as creating, retrieving, pursuing, and rendering delivery note documents, as well as generating deeplinks for viewing and editing delivery notes.

---

## Table of Contents
1. [Using the LexOffice API Package](#using-the-lexoffice-api-package)
2. [Creating a Delivery Note](#creating-a-delivery-note)
3. [Retrieving a Delivery Note by ID](#retrieving-a-delivery-note-by-id)
4. [Pursuing a Sales Voucher to a Delivery Note](#pursuing-a-sales-voucher-to-a-delivery-note)
5. [Rendering a Delivery Note Document](#rendering-a-delivery-note-document)
6. [Generating Deeplinks for Delivery Notes](#generating-deeplinks-for-delivery-notes)
7. [Example Usage in a Controller](#example-usage-in-a-controller)

---

## Using the LexOffice API Package

To use the LexOffice API package, first import the main class:

```php
use Codersgarden\PhpLexofficeApi\LexofficeDeliveryNoteManager;
```
---

### **1. Creating a Delivery Note**

#### **Method**
```php
$response = $lexofficeDeliveryNoteManager->create(array $data, bool $finalize = false);
```

#### **Parameters**
- `$data` (array): Payload for creating the delivery note.
- `$finalize` (bool): Optional. Finalize the delivery note (`false` by default).

#### **Example**
```php
$deliveryNoteData = [
    'archived' => false,
    'voucherDate' => '2023-02-22T00:00:00.000+01:00',
    'address' => [
        'name' => 'Bike & Ride GmbH & Co. KG',
        'street' => 'Musterstraße 42',
        'city' => 'Freiburg',
        'zip' => '79112',
        'countryCode' => 'DE',
    ],
    'lineItems' => [
        [
            'type' => 'custom',
            'name' => 'Abus Kabelschloss Primo 590',
            'quantity' => 2,
            'unitName' => 'Stück',
            'unitPrice' => [
                'currency' => 'EUR',
                'netAmount' => 13.4,
                'taxRatePercentage' => 19,
            ],
        ],
    ],
    'taxConditions' => [
        'taxType' => 'net',
    ],
    'shippingConditions' => [
        'shippingType' => 'delivery',
        'shippingDate' => '2023-02-23T00:00:00.000+01:00',
    ],
    'title' => 'Delivery Note Title',
    'introduction' => 'Introduction text for the delivery note',
    'deliveryTerms' => 'Lieferung frei Haus.',
    'remark' => 'Closing remarks for the delivery note',
];

$response = $lexofficeDeliveryNoteManager->create($deliveryNoteData);
```

---

### **2. Retrieving a Delivery Note by ID**

#### **Method**
```php
$response = $lexofficeDeliveryNoteManager->find(string $deliveryNoteId);
```

#### **Parameters**
- `$deliveryNoteId` (string): The ID of the delivery note.

#### **Example**
```php
$response = $lexofficeDeliveryNoteManager->find('6bb05596-e2df-488b-9548-34b58077f294');
```

---

### **3. Pursuing a Sales Voucher to a Delivery Note**

#### **Method**
```php
$response = $lexofficeDeliveryNoteManager->pursueToDeliveryNote(string $precedingSalesVoucherId, array $data = []);
```

#### **Parameters**
- `$precedingSalesVoucherId` (string): The ID of the preceding sales voucher.
- `$data` (array): Payload for the new delivery note.

#### **Example**
```php
$deliveryNoteData = [
    'voucherDate' => '2023-02-22T00:00:00.000+01:00',
    'address' => [
        'name' => 'Bike & Ride GmbH & Co. KG',
        'street' => 'Musterstraße 42',
        'city' => 'Freiburg',
        'zip' => '79112',
        'countryCode' => 'DE',
    ],
];

$response = $lexofficeDeliveryNoteManager->pursueToDeliveryNote('e9066f04-8cc7-4616-93f8-ac9ecc8479c8', $deliveryNoteData);
```

---

### **4. Rendering a Delivery Note Document**

#### **Method**
```php
$response = $lexofficeDeliveryNoteManager->renderDocument(string $deliveryNoteId);
```

#### **Parameters**
- `$deliveryNoteId` (string): The ID of the delivery note to render.

#### **Example**
```php
$response = $lexofficeDeliveryNoteManager->renderDocument('6bb05596-e2df-488b-9548-34b58077f294');
```

---

### **5. Generating Deeplinks for Delivery Notes**

#### **View Deeplink**
```php
$url = $lexofficeDeliveryNoteManager->getViewDeeplink('6bb05596-e2df-488b-9548-34b58077f294');
```

#### **Edit Deeplink**
```php
$url = $lexofficeDeliveryNoteManager->getEditDeeplink('6bb05596-e2df-488b-9548-34b58077f294');
```

---

## Example Usage in a Controller

Below is a complete example demonstrating how to use the `LexofficeDeliveryNoteManager` class within a Laravel controller:

```php
<?php

namespace App\Http\Controllers;

use Codersgarden\PhpLexofficeApi\LexofficeDeliveryNoteManager;

class HomeController extends Controller
{
    protected $lexofficeDeliveryNoteManager;

    public function __construct(LexofficeDeliveryNoteManager $lexofficeDeliveryNoteManager)
    {
        $this->LexofficeDeliveryNoteManager = $lexofficeDeliveryNoteManager;
    }

    public function index()
    {
        // Create a Delivery Note
        $deliveryNoteData = [
            'archived' => false,
            'voucherDate' => '2023-02-22T00:00:00.000+01:00',
            'address' => [
                'name' => 'Bike & Ride GmbH & Co. KG',
                'street' => 'Musterstraße 42',
                'city' => 'Freiburg',
                'zip' => '79112',
                'countryCode' => 'DE',
            ],
            'lineItems' => [
                [
                    'type' => 'custom',
                    'name' => 'Abus Kabelschloss Primo 590',
                    'quantity' => 2,
                    'unitName' => 'Stück',
                    'unitPrice' => [
                        'currency' => 'EUR',
                        'netAmount' => 13.4,
                        'taxRatePercentage' => 19,
                    ],
                ],
            ],
            'taxConditions' => [
                'taxType' => 'net',
            ],
            'shippingConditions' => [
                'shippingType' => 'delivery',
                'shippingDate' => '2023-02-23T00:00:00.000+01:00',
            ],
            'title' => 'Delivery Note Title',
            'introduction' => 'Introduction text for the delivery note',
            'deliveryTerms' => 'Lieferung frei Haus.',
            'remark' => 'Closing remarks for the delivery note',
        ];

        $createResponse = $this->LexofficeDeliveryNoteManager->create($deliveryNoteData);
        dd($createResponse);

        // Retrieve a Delivery Note by ID
        $findResponse = $this->LexofficeDeliveryNoteManager->find('6bb05596-e2df-488b-9548-34b58077f294');
        dd($findResponse);

        // Render a Delivery Note Document
        $renderResponse = $this->LexofficeDeliveryNoteManager->renderDocument('6bb05596-e2df-488b-9548-34b58077f294');
        dd($renderResponse);
    }
}
