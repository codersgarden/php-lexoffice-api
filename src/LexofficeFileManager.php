<?php

namespace CodersGarden\PhpLexofficeApi;

use Codersgarden\PhpLexofficeApi\LexofficeBase;
use GuzzleHttp\Exception\RequestException;

class LexofficeFileManager extends LexofficeBase
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Upload a file to LexOffice.
     *
     * This method allows uploading files to LexOffice for bookkeeping or voucher purposes. 
     * The file contents should be sent as binary data, with the type specified (e.g., 'voucher').
     *
     * ### Usage Example:
     * ```php
     * $filePath = '/path/to/file.pdf';
     * $fileType = 'voucher'; // Valid values: voucher
     * $response = $lexofficeFilesManager->uploadFile($filePath, $fileType);
     * if ($response['success']) {
     *     echo "File uploaded successfully. File ID: " . $response['data']['id'];
     * } else {
     *     echo "Error: " . $response['error'];
     * }
     * ```
     *
     * ### Return Value:
     * - `'success'`: Indicates the success of the request.
     * - `'data'`: Contains the file details (`id`) on success.
     * - `'error'`: Error message on failure.
     *
     * @param string $filePath The full path to the file to upload.
     * @param string $fileType The type of the file (e.g., 'voucher').
     * @return array Response array with 'success', 'data', or 'error' details.
     */
    public function uploadFile(string $filePath, string $fileType)
    {
        try {
            $response = $this->client->post('files', [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($filePath, 'r'),
                        'filename' => basename($filePath),
                    ],
                    [
                        'name' => 'type',
                        'contents' => $fileType,
                    ],
                ],
            ]);

            return [
                'success' => true,
                'data' => json_decode($response->getBody()->getContents(), true),
            ];
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $errorData = $response ? json_decode($response->getBody()->getContents(), true) : $e->getMessage();

            return [
                'success' => false,
                'status' => $response ? $response->getStatusCode() : $e->getCode(),
                'error' => $errorData['message'] ?? $e->getMessage(),
            ];
        }
    }

    /**
     * Download a file from LexOffice by its ID.
     *
     * This method retrieves the file's binary data based on the file ID.
     *
     * ### Usage Example:
     * ```php
     * $fileId = '8118c402-1c70-4da1-a9f1-a22f480cc623';
     * $response = $lexofficeFilesManager->downloadFile($fileId);
     * if ($response['success']) {
     *     file_put_contents('/path/to/save/file.pdf', $response['data']);
     *     echo "File downloaded successfully.";
     * } else {
     *     echo "Error: " . $response['error'];
     * }
     * ```
     *
     * ### Return Value:
     * - `'success'`: Indicates the success of the request.
     * - `'data'`: Contains the binary file data on success.
     * - `'error'`: Error message on failure.
     *
     * @param string $fileId The ID of the file to download.
     * @param string $acceptHeader The desired file format (default: '/').
     * @return array Response array with 'success', 'data', or 'error' details.
     */
    public function downloadFile(string $fileId, string $acceptHeader = '*/*')
    {
        try {
            $response = $this->client->get("files/{$fileId}", [
                'headers' => ['Accept' => $acceptHeader],
            ]);

            return [
                'success' => true,
                'data' => $response->getBody()->getContents(),
            ];
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $errorData = $response ? json_decode($response->getBody()->getContents(), true) : $e->getMessage();

            return [
                'success' => false,
                'status' => $response ? $response->getStatusCode() : $e->getCode(),
                'error' => $errorData['message'] ?? $e->getMessage(),
            ];
        }
    }

    /**
     * Generate a deeplink to view the uploaded file.
     *
     * This method provides a permanent URL to view the uploaded file in LexOffice.
     *
     * ### Usage Example:
     * ```php
     * $fileId = '8118c402-1c70-4da1-a9f1-a22f480cc623';
     * $viewUrl = $lexofficeFilesManager->getFileViewDeeplink($fileId);
     * echo "View File URL: " . $viewUrl;
     * ```
     *
     * @param string $fileId The ID of the file.
     * @return string The URL to view the file in LexOffice.
     */
    public function getFileViewDeeplink(string $fileId): string
    {
        return config('lexoffice.base_uri') . "permalink/files/view/{$fileId}";
    }
}
