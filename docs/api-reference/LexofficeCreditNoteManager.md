# LexofficeCreditNoteManager Class Documentation

The `LexofficeCreditNoteManager` class provides seamless integration with the LexOffice API for managing credit notes. This class supports operations such as creating, retrieving, finalizing, pursuing to credit notes, rendering PDF documents, and generating deeplinks for credit notes.

---

## Features

- **Create Credit Notes**: Supports draft or finalized credit note creation.
- **Retrieve Credit Notes**: Fetch detailed information of a specific credit note.
- **Pursue to Credit Note**: Convert a preceding sales voucher into a credit note.
- **Render Credit Note Document**: Trigger PDF rendering of a credit note.
- **Generate Deeplinks**: Direct links to view or edit credit notes.

---

## Table of Contents

1. [Import Class File](#import-class-file)
2. [Creating a Credit Note](#creating-a-credit-note)
3. [Retrieving a Credit Note](#retrieving-a-credit-note)
4. [Pursuing a Sales Voucher to a Credit Note](#pursuing-a-sales-voucher-to-a-credit-note)
5. [Rendering a Credit Note Document (PDF)](#rendering-a-credit-note-document-pdf)
6. [Generating Deeplinks for a Credit Note](#generating-deeplinks-for-a-credit-note)
7. [Example Usage in a Laravel Controller](#example-usage-in-a-laravel-controller)

---

## Import Class File

To use this class, ensure that the LexOffice API package is installed and include the `LexofficeCreditNoteManager` class in your Laravel controller or service.

```php
use Codersgarden\PhpLexofficeApi\LexofficeCreditNoteManager;
```

---

## Creating a Credit Note

### Method: `create(array $data, bool $finalize = false)`

This method creates a new credit note in draft or finalized mode.

**Example:**
```php
$creditNoteData = [
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
    'totalPrice' => ['currency' => 'EUR'],
    'taxConditions' => ['taxType' => 'net'],
    'title' => 'Credit Note Title',
    'introduction' => 'Introduction text for the credit note',
    'remark' => 'Closing remarks for the credit note',
];

$response = $lexofficeCreditNoteManager->create($creditNoteData, true); // Pass true to finalize
```

**Return Value:**
- `'success'`: Indicates success (`true`/`false`).
- `'data'`: Created credit note details on success.
- `'error'`: Error message if creation fails.

---

## Retrieving a Credit Note

### Method: `find(string $creditNoteId)`

Fetch detailed information of a credit note by its ID.

**Example:**
```php
$creditNoteId = '66bc42a2-cbab-4532-9f90-83bcda139002';
$response = $lexofficeCreditNoteManager->find($creditNoteId);
```

**Return Value:**
- `'success'`: Boolean indicating the success.
- `'data'`: Contains credit note details.
- `'error'`: Error message on failure.

---

## Pursuing a Sales Voucher to a Credit Note

### Method: `pursueToCreditNote(string $precedingSalesVoucherId, array $data, bool $finalize = false)`

Convert a preceding sales voucher to a credit note.

**Example:**
```php
$precedingSalesVoucherId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
$creditNoteData = [
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
    'totalPrice' => ['currency' => 'EUR'],
    'taxConditions' => ['taxType' => 'net'],
];

$response = $lexofficeCreditNoteManager->pursueToCreditNote($precedingSalesVoucherId, $creditNoteData, true); // Finalized
```

**Return Value:**
- `'success'`: Boolean indicating the success.
- `'data'`: Created credit note details.
- `'error'`: Error message on failure.

---

## Rendering a Credit Note Document (PDF)

### Method: `renderDocument(string $creditNoteId)`

Triggers rendering of a credit note's PDF document.

**Example:**
```php
$creditNoteId = '66bc42a2-cbab-4532-9f90-83bcda139002';
$response = $lexofficeCreditNoteManager->renderDocument($creditNoteId);
```

**Return Value:**
- `'success'`: Boolean indicating the success.
- `'data'`: Contains `documentFileId`.
- `'error'`: Error message on failure.

---

## Generating Deeplinks for a Credit Note

### Methods:
1. **View Deeplink:** `getViewDeeplink(string $creditNoteId): string`
2. **Edit Deeplink:** `getEditDeeplink(string $creditNoteId): string`

**Example:**
```php
$creditNoteId = '66bc42a2-cbab-4532-9f90-83bcda139002';
$viewUrl = $lexofficeCreditNoteManager->getViewDeeplink($creditNoteId);
$editUrl = $lexofficeCreditNoteManager->getEditDeeplink($creditNoteId);

echo "View Credit Note: " . $viewUrl;
echo "Edit Credit Note: " . $editUrl;
```

---

## Example Usage in a Laravel Controller

Below is an example of how to use the `LexofficeCreditNoteManager` class in a Laravel controller:

```php
<?php

namespace App\Http\Controllers;

use Codersgarden\PhpLexofficeApi\LexofficeCreditNoteManager;

class HomeController extends Controller
{
    protected $lexofficeCreditNoteManager;

    public function __construct()
    {
        $this->LexofficeCreditNoteManager = new LexofficeCreditNoteManager();
    }

    public function index()
    {
        $creditNoteId = '66bc42a2-cbab-4532-9f90-83bcda139002';

        // 1. Find Credit Note
        $response = $this->LexofficeCreditNoteManager->find($creditNoteId);

        // 2. Render Credit Note PDF
        $response = $this->LexofficeCreditNoteManager->renderDocument($creditNoteId);

        // 3. Generate Deeplinks
        $viewUrl = $this->LexofficeCreditNoteManager->getViewDeeplink($creditNoteId);
        $editUrl = $this->LexofficeCreditNoteManager->getEditDeeplink($creditNoteId);

        dd($response, $viewUrl, $editUrl);
    }
}
```