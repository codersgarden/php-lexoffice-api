<?php

namespace Codersgarden\PhpLexofficeApi;

use GuzzleHttp\Exception\RequestException;

class LexofficeArticleManager extends LexofficeBase
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create an article in the LexOffice API.
     *
     * This method creates a new article in the LexOffice API. The article data must contain
     * certain required fields, and other optional properties can be provided for more detailed
     * specifications of the article.
     *
     * Required Fields:
     * - `title` (string, required): The title of the article (e.g., product or service name).
     * - `type` (string, required): The type of the article. Accepted values are "PRODUCT" or "SERVICE".
     * - `unitName` (string, required): The unit name of the article (e.g., "piece", "hour").
     * - `price` (array, required): The pricing details of the article, containing:
     *   - `netPrice` (number, conditionally required): Required if `leadingPrice` is set to "NET".
     *   - `grossPrice` (number, conditionally required): Required if `leadingPrice` is set to "GROSS".
     *   - `leadingPrice` (string, required): Indicates whether the price is "NET" or "GROSS".
     *   - `taxRate` (number, required): The applicable tax rate for the article (e.g., 0, 7, 19).
     *
     * Optional Fields:
     * - `description` (string, optional): A brief description of the article.
     * - `articleNumber` (string, optional): The user-defined article number.
     * - `gtin` (string, optional): The Global Trade Item Number (GTIN) of the article, validated for formats such as GTIN-8, GTIN-12, GTIN-13, or GTIN-14.
     * - `note` (string, optional): An internal note for the article.
     * - `archived` (boolean, read-only): Indicates if the article is archived. This field is managed by the API and cannot be modified.
     * - `version` (integer, read-only): The version number of the article, which increments with each change to support optimistic locking.
     * - `createdDate` (datetime, read-only): The timestamp when the article was created in the LexOffice system, formatted as RFC 3339/ISO 8601.
     * - `updatedDate` (datetime, read-only): The timestamp when the article was last updated in the LexOffice system, formatted as RFC 3339/ISO 8601.
     * - `organizationId` (uuid, read-only): The unique identifier of the organization the article belongs to.
     *
     * Example of an $articleData array:
     * ```php
     * $articleData = [
     *     'title' => 'Lexware buchhaltung Premium 2024',
     *     'type' => 'PRODUCT',
     *     'unitName' => 'Download-Code',
     *     'articleNumber' => 'LXW-BUHA-2024-001',
     *     'price' => [
     *         'netPrice' => 61.90,
     *         'leadingPrice' => 'NET',
     *         'taxRate' => 19
     *     ]
     * ];
     * ```
     *
     * @param array $articleData An associative array representing the article data to be created.
     *                           Example structure provided above.
     * @return array An array containing:
     *               - 'success' (bool): Indicates whether the request was successful.
     *               - 'data' (array|null): Contains the response data from the API on success.
     *               - 'status' (int|null): HTTP status code returned by the API on failure.
     *               - 'error' (string|null): Error message if the request fails.
     */
    public function create(array $articleData)
    {
        try {
            $response = $this->client->post('articles', [
                'json' => $articleData,
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
     * Retrieve an article by its ID from the LexOffice API.
     *
     * This method fetches an article from the LexOffice API using the provided article ID.
     * The request sends a GET request to the API endpoint and retrieves the article's details.
     * The response includes key properties of the article, such as its title, description,
     * type, article number, GTIN, notes, unit name, and price details.
     *
     * Expected Response:
     * On success, this method returns the full details of the article with the specified ID.
     * On failure, it returns an error message and the HTTP status code.
     *
     * Example usage:
     * ```php
     * $articleId = 'f5d5e4c2-e20a-11ee-9cde-7789c0d1fa1c';
     * $response = $lexofficeArticles->find($articleId);
     * if ($response['success']) {
     *     // Handle successful response
     *     print_r($response['data']);
     * } else {
     *     // Handle error
     *     echo 'Error: ' . $response['error'];
     * }
     * ```
     *
     * @param string $articleId The unique identifier of the article to be retrieved.
     * @return array An array containing:
     *               - 'success' (bool): Indicates whether the request was successful.
     *               - 'data' (array|null): Contains the article data from the API on success.
     *               - 'status' (int|null): HTTP status code returned by the API on failure.
     *               - 'error' (string|null): Error message if the request fails.
     */
    public function find(string $articleId)
    {
        try {
            $response = $this->client->get("articles/{$articleId}");

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
     * Update an existing article in the LexOffice API.
     *
     * This method updates an existing article in the LexOffice API using the provided
     * article ID and updated article data. The request sends a PUT request with the
     * article data in the body. The API response contains the updated article details
     * and versioning information.
     *
     * Required Fields:
     * - `title` (string, required): The title of the article (e.g., product or service name).
     * - `type` (string, required): The type of the article. Accepted values are "PRODUCT" or "SERVICE".
     * - `unitName` (string, required): The unit name of the article (e.g., "piece", "hour").
     * - `price` (array, required): The pricing details of the article, containing:
     *   - `netPrice` (number, conditionally required): Required if `leadingPrice` is set to "NET".
     *   - `grossPrice` (number, conditionally required): Required if `leadingPrice` is set to "GROSS".
     *   - `leadingPrice` (string, required): Indicates whether the price is "NET" or "GROSS".
     *   - `taxRate` (number, required): The applicable tax rate for the article (e.g., 0, 7, 19).
     *
     * Optional Fields:
     * - `description` (string, optional): A brief description of the article.
     * - `articleNumber` (string, optional): The user-defined article number.
     * - `gtin` (string, optional): The Global Trade Item Number (GTIN) of the article, validated for formats such as GTIN-8, GTIN-12, GTIN-13, or GTIN-14.
     * - `note` (string, optional): An internal note for the article.
     * - `version` (integer, required): The version number of the article. This field is used to manage optimistic locking, ensuring the article is not modified elsewhere simultaneously.
     *
     * Example of an $articleData array for updating:
     * ```php
     * $articleData = [
     *     'title' => 'Lexware buchhaltung Premium 2024',
     *     'description' => 'Updated description',
     *     'type' => 'PRODUCT',
     *     'unitName' => 'Download-Code',
     *     'articleNumber' => 'LXW-BUHA-2024-001',
     *     'gtin' => '9783648170632',
     *     'note' => 'Internal note',
     *     'price' => [
     *         'netPrice' => 61.90,
     *         'grossPrice' => 73.66,
     *         'leadingPrice' => 'NET',
     *         'taxRate' => 19
     *     ],
     *     'version' => 1
     * ];
     * ```
     *
     * @param string $articleId The unique identifier of the article to be updated.
     * @param array $articleData An associative array representing the updated article data.
     *                           Example structure provided above.
     * @return array An array containing:
     *               - 'success' (bool): Indicates whether the request was successful.
     *               - 'data' (array|null): Contains the response data from the API on success.
     *               - 'status' (int|null): HTTP status code returned by the API on failure.
     *               - 'error' (string|null): Error message if the request fails.
     */
    public function update(string $articleId, array $articleData)
    {
        try {
            $response = $this->client->put("articles/{$articleId}", [
                'json' => $articleData,
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
     * Delete an article by its ID in the LexOffice API.
     *
     * This method deletes an article identified by the given article ID from the LexOffice API.
     * The request sends a DELETE request to the API endpoint. On successful deletion, 
     * a success message is returned. If the article does not exist or an error occurs, 
     * an error message and the relevant HTTP status code are returned.
     *
     * Example usage:
     * ```php
     * $articleId = 'eb46d328-e1dc-11ee-8444-2fadfc15a567';
     * $response = $lexofficeArticles->delete($articleId);
     * if ($response['success']) {
     *     echo $response['message'];
     * } else {
     *     echo 'Error: ' . $response['error'];
     * }
     * ```
     *
     * @param string $articleId The unique identifier of the article to be deleted.
     * @return array An array containing:
     *               - 'success' (bool): Indicates whether the request was successful.
     *               - 'message' (string|null): A success message if the deletion was successful.
     *               - 'status' (int|null): HTTP status code returned by the API on failure.
     *               - 'error' (string|null): Error message if the request fails.
     */
    public function delete(string $articleId)
    {
        try {
            $response = $this->client->delete("articles/{$articleId}");

            return [
                'success' => true,
                'message' => 'Article deleted successfully.',
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
     * Retrieve all articles or filter articles from the LexOffice API.
     *
     * This method fetches all articles or applies filters based on the
     * provided criteria, enabling precise control over the results returned
     * by the LexOffice API. The filtering mechanism supports a variety of 
     * parameters such as `articleNumber`, `gtin`, and `type`.
     *
     * The filters use a logical AND operation, meaning that multiple filters
     * will narrow down the search results further. Only the provided filters
     * are applied, and unsupported or missing filters will be ignored.
     *
     * Supported filters:
     * - `articleNumber`: Filters by the specified article number.
     * - `gtin`: Filters by the given Global Trade Item Number (GTIN).
     * - `type`: Filters articles by type, such as PRODUCT or SERVICE.
     *
     * Example filters:
     * - ['articleNumber' => 'LXW-BUHA-2024-001']
     * - ['type' => 'PRODUCT']
     *
     * Pagination is handled by default and the response may include metadata
     * about total pages, elements, and sorting information.
     *
     * @param array $filters Associative array of filter parameters as key-value pairs.
     *                       Supported keys include 'articleNumber', 'gtin', and 'type'.
     * @return array Response array with 'success' status, 'data' containing articles,
     *               or 'error' with error details in case of a failure.
     */
    public function all(array $filters = [])
    {
        try {
            $queryParams = http_build_query($filters);
            $response = $this->client->get("articles?" . $queryParams);

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
}
