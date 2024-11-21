# LexofficeFileManager Class Documentation

The `LexofficeFileManager` class provides methods to interact with the LexOffice Files API, allowing you to upload files, download files, and generate deeplinks for viewing uploaded files.

---

## Features

- Upload files (PDF, JPG, PNG, XML) to LexOffice.
- Download files using their `fileId`.
- Generate deeplinks for uploaded files to view them in the LexOffice UI.

---

## Table of Contents

1. [Installation and Setup](#installation-and-setup)
2. [Class Methods](#class-methods)
   - [uploadFile()](#uploadfile)
   - [downloadFile()](#downloadfile)
   - [getFileViewDeeplink()](#getfileviewdeeplink)
3. [Example Usage in a Controller](#example-usage-in-a-controller)
4. [Available Upload Types and Formats](#available-upload-types-and-formats)

---

## Installation and Setup

Before using the `LexofficeFileManager` class, ensure you have installed the LexOffice API package. Import the class in your controller or service:

```php
use Codersgarden\PhpLexofficeApi\LexofficeFileManager;
```

---

## Class Methods

### `uploadFile()`

Uploads a file to LexOffice.

#### Parameters:
- `string $filePath`: Path to the file to be uploaded.
- `string $type`: The upload type (`voucher`).

#### Return:
- On success:
  ```php
  [
      'success' => true,
      'data' => [
          'id' => '8118c402-1c70-4da1-a9f1-a22f480cc623',
      ],
  ]
  ```
- On failure:
  ```php
  [
      'success' => false,
      'status' => 406,
      'error' => 'Error message',
  ]
  ```

#### Usage Example:
```php
$fileManager = new LexofficeFileManager();
$filePath = '/path/to/file.pdf';
$type = 'voucher';

$response = $fileManager->uploadFile($filePath, $type);
if ($response['success']) {
    echo 'File uploaded successfully. File ID: ' . $response['data']['id'];
} else {
    echo 'Error: ' . $response['error'];
}
```

---

### `downloadFile()`

Downloads a file from LexOffice using its `fileId`.

#### Parameters:
- `string $fileId`: The ID of the file to download.

#### Return:
- On success:
  ```php
  [
      'success' => true,
      'data' => <binary file content>,
  ]
  ```
- On failure:
  ```php
  [
      'success' => false,
      'status' => 404,
      'error' => 'File not found',
  ]
  ```

#### Usage Example:
```php
$fileManager = new LexofficeFileManager();
$fileId = '8118c402-1c70-4da1-a9f1-a22f480cc623';

$response = $fileManager->downloadFile($fileId);
if ($response['success']) {
    file_put_contents('/path/to/save/file.pdf', $response['data']);
    echo 'File downloaded successfully.';
} else {
    echo 'Error: ' . $response['error'];
}
```

---

### `getFileViewDeeplink()`

Generates a view deeplink for the uploaded file.

#### Parameters:
- `string $fileId`: The ID of the file.

#### Return:
- The URL to view the file:
  ```php
  https://app.lexoffice.de/permalink/files/view/{fileId}
  ```

#### Usage Example:
```php
$fileManager = new LexofficeFileManager();
$fileId = '8118c402-1c70-4da1-a9f1-a22f480cc623';

$viewUrl = $fileManager->getFileViewDeeplink($fileId);
echo 'View URL: ' . $viewUrl;
```

---

## Example Usage in a Controller

Here's a complete example using the `LexofficeFileManager` in a Laravel controller:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Codersgarden\PhpLexofficeApi\LexofficeFileManager;

class FilesController extends Controller
{
    protected $lexofficeFileManager;

    public function __construct()
    {
        $this->lexofficeFileManager = new LexofficeFileManager();
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,png,xml|max:5120',
            'type' => 'required|in:voucher',
        ]);

        $filePath = $request->file('file')->getRealPath();
        $type = $request->input('type');

        $response = $this->lexofficeFileManager->uploadFile($filePath, $type);

        if ($response['success']) {
            return response()->json([
                'message' => 'File uploaded successfully.',
                'fileId' => $response['data']['id'],
            ], 202);
        }

        return response()->json([
            'message' => 'File upload failed.',
            'error' => $response['error'],
        ], $response['status'] ?? 500);
    }

    public function downloadFile($fileId)
    {
        $response = $this->lexofficeFileManager->downloadFile($fileId);

        if ($response['success']) {
            $filePath = storage_path("app/{$fileId}.pdf");
            file_put_contents($filePath, $response['data']);
            return response()->download($filePath)->deleteFileAfterSend(true);
        }

        return response()->json([
            'message' => 'File download failed.',
            'error' => $response['error'],
        ], $response['status'] ?? 500);
    }

    public function getFileViewDeeplink($fileId)
    {
        $viewUrl = $this->lexofficeFileManager->getFileViewDeeplink($fileId);
        return response()->json(['viewUrl' => $viewUrl]);
    }
}
```

---

## Available Upload Types and Formats

| Upload Type | File Formats       | Max File Size | Description                              |
|-------------|--------------------|---------------|------------------------------------------|
| `voucher`   | PDF, JPG, PNG, XML | 5 MB          | Upload voucher images for bookkeeping.   |
