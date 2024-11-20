# LexofficeArticleManager Class Documentation

The `LexofficeArticleManager` class provides seamless integration with the LexOffice API for managing articles. This class supports operations such as creating, retrieving, updating, and deleting articles, as well as retrieving filtered lists of articles.

---

## Table of Contents
1. [Using the LexOffice API Package](#using-the-lexoffice-api-package)
2. [Creating an Article](#creating-an-article)
3. [Retrieving an Article by ID](#retrieving-an-article-by-id)
4. [Updating an Article](#updating-an-article)
5. [Deleting an Article](#deleting-an-article)
6. [Retrieving All Articles with Filters](#retrieving-all-articles-with-filters)
7. [Example Usage in a Controller](#example-usage-in-a-controller)

---

## Using the LexOffice API Package

To use the LexOffice API package, first import the main class:

```php
use Codersgarden\PhpLexofficeApi\LexofficeArticleManager;
```

---

### Creating an Article

```php
$articleData = [
    'title' => 'Lexware buchhaltung Premium 2024',
    'description' => 'Monatsabonnement. Mehrplatzsystem zur Buchhaltung. Produkt vom Marktführer. PC Aktivierungscode per Email',
    'type' => 'PRODUCT', // Possible values: 'PRODUCT' or 'SERVICE'
    'articleNumber' => 'LXW-BUHA-2024-001',
    'gtin' => '9783648170632', // Optional: Global Trade Item Number
    'note' => 'Internal note for reference',
    'unitName' => 'Download-Code', // Example: 'piece', 'hour', etc.
    'price' => [
        'netPrice' => 61.90, // Required if 'leadingPrice' is 'NET'
        'grossPrice' => 73.66, // Required if 'leadingPrice' is 'GROSS'
        'leadingPrice' => 'NET', // Possible values: 'NET' or 'GROSS'
        'taxRate' => 19 // Example tax rates: 0, 7, or 19
    ]
];
$response = $lexofficeArticleManager->create($articleData);
```

**Description:**
- Required fields: `title`, `type`, `unitName`, `price` (with `netPrice` or `grossPrice`, `leadingPrice`, and `taxRate`).
- Optional fields include `description`, `articleNumber`, `gtin`, and `note`.

---

### Retrieving an Article by ID

```php
$articleId = 'eb46d328-e1dc-11ee-8444-2fadfc15a567'; // Example ID
$response = $lexofficeArticleManager->find($articleId);
```

**Description:**
- Retrieves details of an article based on its unique identifier.

---

### Updating an Article

```php
$updatedArticleData = [
    'title' => 'Updated Lexware buchhaltung Premium 2024',
    'description' => 'Updated description for the product',
    'type' => 'PRODUCT',
    'unitName' => 'Download-Code',
    'articleNumber' => 'LXW-BUHA-2024-001',
    'gtin' => '9783648170632',
    'note' => 'Updated internal note',
    'price' => [
        'netPrice' => 65.00,
        'grossPrice' => 77.35,
        'leadingPrice' => 'NET',
        'taxRate' => 19
    ],
    'version' => 1 // Current version of the article
];
$response = $lexofficeArticleManager->update($articleId, $updatedArticleData);
```

**Description:**
- Updates an article using its ID and new data. Make sure to include the current `version` to manage optimistic locking.

---

### Deleting an Article

```php
$articleId = 'eb46d328-e1dc-11ee-8444-2fadfc15a567'; // Example ID
$response = $lexofficeArticleManager->delete($articleId);
```

**Description:**
- Deletes an article based on its ID. Returns a success message upon successful deletion.

---

### Retrieving All Articles with Filters

```php
$filters = [
    'type' => 'PRODUCT', // Example filter to only get 'PRODUCT' type articles
    'gtin' => '9783648170632'
];
$response = $lexofficeArticleManager->all($filters);
```

**Description:**
- Retrieves all articles that match the specified filters. Supported filters include `type`, `articleNumber`, and `gtin`.

---

## Example Usage in a Controller

Below is a complete example demonstrating how to use the `LexofficeArticleManager` class within a Laravel controller:

```php
<?php

namespace App\Http\Controllers;

use Codersgarden\PhpLexofficeApi\LexofficeArticleManager;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $lexofficeArticleManager;

    public function __construct(LexofficeArticleManager $lexofficeArticleManager)
    {
        $this->LexofficeArticleManager = $lexofficeArticleManager;
    }

    public function index()
    {
        // 1. Create an Article
        $articleData = [
            'title' => 'Lexware buchhaltung Premium 2024',
            'description' => 'Monatsabonnement. Mehrplatzsystem zur Buchhaltung. Produkt vom Marktführer. PC Aktivierungscode per Email',
            'type' => 'PRODUCT', // Possible values: 'PRODUCT' or 'SERVICE'
            'articleNumber' => 'LXW-BUHA-2024-001',
            'gtin' => '9783648170632', // Optional: Global Trade Item Number
            'note' => 'Internal note for reference',
            'unitName' => 'Download-Code', // Example: 'piece', 'hour', etc.
            'price' => [
                'netPrice' => 61.90, // Required if 'leadingPrice' is 'NET'
                'grossPrice' => 73.66, // Required if 'leadingPrice' is 'GROSS'
                'leadingPrice' => 'NET', // Possible values: 'NET' or 'GROSS'
                'taxRate' => 19 // Example tax rates: 0, 7, or 19
            ]
        ];
        $createResponse = $this->LexofficeArticleManager->create($articleData);
        dd($createResponse);

        // 2. Retrieve an Article by ID
        $articleId = 'eb46d328-e1dc-11ee-8444-2fadfc15a567'; // Example ID
        $findResponse = $this->LexofficeArticleManager->find($articleId);
        dd($findResponse);

        // 3. Update an Article
        $updatedArticleData = [
            'title' => 'Updated Lexware buchhaltung Premium 2024',
            'description' => 'Updated description for the product',
            'type' => 'PRODUCT',
            'unitName' => 'Download-Code',
            'articleNumber' => 'LXW-BUHA-2024-001',
            'gtin' => '9783648170632',
            'note' => 'Updated internal note',
            'price' => [
                'netPrice' => 65.00,
                'grossPrice' => 77.35,
                'leadingPrice' => 'NET',
                'taxRate' => 19
            ],
            'version' => 1 // Current version of the article
        ];
        $updateResponse = $this->LexofficeArticleManager->update($articleId, $updatedArticleData);
        dd($updateResponse);

        // 4. Delete an Article
        $deleteResponse = $this->LexofficeArticleManager->delete($articleId);
        dd($deleteResponse);

        // 5. Retrieve All Articles with Optional Filters
        $filters = [
            'type' => 'PRODUCT', // Example filter to only get 'PRODUCT' type articles
            'gtin' => '9783648170632'
        ];
        $allArticlesResponse = $this->LexofficeArticleManager->all($filters);
        dd($allArticlesResponse);
    }
}
