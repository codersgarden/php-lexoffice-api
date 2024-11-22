# LexofficeInvoiceManager Class Documentation

The `LexofficeInvoiceManager` class provides seamless integration with the LexOffice API for managing invoices. This class allows you to create, retrieve, render, and generate deeplinks for invoices.

---

## Features

- Create invoices in draft or finalized (open) states.
- Retrieve detailed information about a specific invoice.
- Render invoices as PDF documents.
- Generate view and edit deeplinks for invoices.

---

## Prerequisites

Ensure the LexOffice API base class `LexofficeBase` is correctly configured and your API key is added to the `.env` file.

---

## Importing the Class

Include the following class in your code:

```php
use CodersGarden\PhpLexofficeApi\LexofficeInvoiceManager;
```

---

## Methods

### **1. Create an Invoice**

This method creates an invoice in the LexOffice system. Invoices can be created in draft mode by default or finalized with the `finalize` parameter.

**Usage Example**:

```php
$invoiceData = [
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
            'name' => 'Energieriegel Testpaket',
            'quantity' => 1,
            'unitName' => 'Stück',
            'unitPrice' => [
                'currency' => 'EUR',
                'netAmount' => 5,
                'taxRatePercentage' => 0,
            ],
        ],
    ],
    'totalPrice' => [
        'currency' => 'EUR',
    ],
    'taxConditions' => [
        'taxType' => 'net',
    ],
    'title' => 'Rechnung',
    'introduction' => 'Ihre bestellten Positionen stellen wir Ihnen hiermit in Rechnung',
    'remark' => 'Vielen Dank für Ihren Einkauf',
];

$response = $lexofficeInvoiceManager->create($invoiceData, true); // Pass true to finalize
```

**Parameters**:

- `array $data`: The payload containing invoice details.
- `bool $finalize`: (Optional) Finalizes the invoice if set to `true`.

**Returns**:
- `success`: Boolean indicating the request's success.
- `data`: Contains the invoice details on success.
- `error`: Error message on failure.

---

### **2. Retrieve an Invoice**

Fetch detailed information about a specific invoice using its ID.

**Usage Example**:

```php
$invoiceId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
$response = $lexofficeInvoiceManager->find($invoiceId);
```

**Parameters**:

- `string $invoiceId`: The unique ID of the invoice.

**Returns**:
- `success`: Boolean indicating the request's success.
- `data`: Contains the invoice details on success.
- `error`: Error message on failure.

---

### **3. Render an Invoice Document (PDF)**

Trigger the rendering of a PDF for a specific invoice. The returned `documentFileId` can be used with the Files Endpoint to download the PDF.

**Usage Example**:

```php
$invoiceId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
$response = $lexofficeInvoiceManager->renderDocument($invoiceId);
```

**Parameters**:

- `string $invoiceId`: The unique ID of the invoice.

**Returns**:
- `success`: Boolean indicating the request's success.
- `data`: Contains the `documentFileId` on success.
- `error`: Error message on failure.

---

### **4. Generate View Deeplink**

Generate a URL to directly view the invoice in LexOffice.

**Usage Example**:

```php
$invoiceId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
$viewUrl = $lexofficeInvoiceManager->getViewDeeplink($invoiceId);
echo "View URL: " . $viewUrl;
```

**Parameters**:

- `string $invoiceId`: The unique ID of the invoice.

**Returns**:
- A string containing the URL to view the invoice.

---

### **5. Generate Edit Deeplink**

Generate a URL to directly edit the invoice in LexOffice.

**Usage Example**:

```php
$invoiceId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
$editUrl = $lexofficeInvoiceManager->getEditDeeplink($invoiceId);
echo "Edit URL: " . $editUrl;
```

**Parameters**:

- `string $invoiceId`: The unique ID of the invoice.

**Returns**:
- A string containing the URL to edit the invoice.

---

## Example Controller

Below is a complete example demonstrating how to use the `LexofficeInvoiceManager` class within a Laravel controller:

```php
<?php

namespace App\Http\Controllers;

use CodersGarden\PhpLexofficeApi\LexofficeInvoiceManager;

class InvoiceController extends Controller
{
    protected $lexofficeInvoiceManager;

    public function __construct()
    {
        $this->lexofficeInvoiceManager = new LexofficeInvoiceManager();
    }

    public function index()
    {
        // Create an Invoice
        $invoiceData = [
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
                    'name' => 'Energieriegel Testpaket',
                    'quantity' => 1,
                    'unitName' => 'Stück',
                    'unitPrice' => [
                        'currency' => 'EUR',
                        'netAmount' => 5,
                        'taxRatePercentage' => 0,
                    ],
                ],
            ],
            'totalPrice' => [
                'currency' => 'EUR',
            ],
            'taxConditions' => [
                'taxType' => 'net',
            ],
            'title' => 'Rechnung',
            'introduction' => 'Ihre bestellten Positionen stellen wir Ihnen hiermit in Rechnung',
            'remark' => 'Vielen Dank für Ihren Einkauf',
        ];

        $response = $this->lexofficeInvoiceManager->create($invoiceData, true);
        dd($response);

        // Find an Invoice
        $invoiceId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
        $invoice = $this->lexofficeInvoiceManager->find($invoiceId);
        dd($invoice);

        // Render Invoice PDF
        $pdfResponse = $this->lexofficeInvoiceManager->renderDocument($invoiceId);
        dd($pdfResponse);

        // Generate View Deeplink
        $viewUrl = $this->lexofficeInvoiceManager->getViewDeeplink($invoiceId);
        echo "View Invoice URL: " . $viewUrl;

        // Generate Edit Deeplink
        $editUrl = $this->lexofficeInvoiceManager->getEditDeeplink($invoiceId);
        echo "Edit Invoice URL: " . $editUrl;
    }
}
```

---

## Notes

- Always validate your data before sending it to the API.
- Finalizing an invoice requires setting the `finalize` parameter to `true` when calling the `create` method.
- To download an invoice PDF, use the `renderDocument` method to get the `documentFileId`, then utilize the Files Endpoint.
