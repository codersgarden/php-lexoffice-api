<?php

namespace Codersgarden\PhpLexofficeApi;

use GuzzleHttp\Exception\RequestException;

class LexofficeContactManager extends LexofficeBase
{
    /**
     * Create a new instance of the LexofficeContactManager
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create a contact in the LexOffice API using Guzzle.
     *
     * This method creates a new contact in the LexOffice API using the provided contact data.
     * Contacts can represent customers, vendors, or both, and can include various properties such as
     * addresses, roles, contact persons, email addresses, and phone numbers.
     *
     * ### Required Fields for Creating a Contact:
     * - `version` (integer, required): Must be set to 0 for creating a new contact.
     * - `roles` (object, required): Defines the roles of the contact (e.g., `customer`, `vendor`).
     *   - Example: `'roles' => ['customer' => new \stdClass()]`.
     *   - At least one role must be provided, and it should be set as an empty object.
     * - One of the following is required:
     *   - `company` (object, conditionally required): Must be provided if the contact is a company. Fields include:
     *     - `name` (string, required): Name of the company.
     *     - `taxNumber` (string, optional): Tax number for the company.
     *     - `vatRegistrationId` (string, optional): VAT registration ID.
     *     - `allowTaxFreeInvoices` (boolean, optional): Indicates whether tax-free invoices are allowed.
     *     - `contactPersons` (array, optional): List of contact persons with fields:
     *       - `salutation` (string, optional): Salutation (max 25 characters).
     *       - `firstName` (string, optional): First name.
     *       - `lastName` (string, required): Last name (if provided).
     *       - `primary` (boolean, optional): Whether this is the primary contact person.
     *       - `emailAddress` (string, optional): Email address.
     *       - `phoneNumber` (string, optional): Phone number.
     *   - `person` (object, conditionally required): Must be provided if the contact is an individual. Fields include:
     *     - `salutation` (string, optional): Salutation (max 25 characters).
     *     - `firstName` (string, optional): First name.
     *     - `lastName` (string, required): Last name (must not be empty).
     *
     * ### Optional Fields for Creating a Contact:
     * - `addresses` (object, optional): Contains billing and shipping addresses.
     *   - `billing` (array, optional): List of billing addresses with fields:
     *     - `supplement` (string, optional): Additional address information.
     *     - `street` (string, required): Street and number.
     *     - `zip` (string, required): Zip code.
     *     - `city` (string, required): City name.
     *     - `countryCode` (string, required): ISO 3166 alpha2 country code (e.g., 'DE' for Germany).
     *   - `shipping` (array, optional): List of shipping addresses with the same structure as `billing`.
     * - `xRechnung` (object, optional): Properties related to German public authorities:
     *   - `buyerReference` (string, optional): Customer's Leitweg-ID conforming to the German XRechnung system.
     *   - `vendorNumberAtCustomer` (string, optional): Vendor number used by the customer.
     * - `emailAddresses` (object, optional): Contains lists of email addresses:
     *   - `business` (array, optional): List of business email addresses.
     *   - `office` (array, optional): List of office email addresses.
     *   - `private` (array, optional): List of private email addresses.
     *   - `other` (array, optional): List of other email addresses.
     * - `phoneNumbers` (object, optional): Contains lists of phone numbers:
     *   - `business` (array, optional): List of business phone numbers.
     *   - `office` (array, optional): List of office phone numbers.
     *   - `mobile` (array, optional): List of mobile phone numbers.
     *   - `private` (array, optional): List of private phone numbers.
     *   - `fax` (array, optional): List of fax numbers.
     *   - `other` (array, optional): List of other phone numbers.
     * - `note` (string, optional): Additional notes about the contact (max 1000 characters).
     * - `archived` (boolean, read-only): Indicates if the contact is archived (managed by the API).
     *
     * ### Example of a $contactData array:
     * ```php
     * $contactData = [
     *     'version' => 0,
     *     'roles' => [
     *         'customer' => new \stdClass()
     *     ],
     *     'person' => [
     *         'salutation' => 'Herr',
     *         'firstName' => 'Max',
     *         'lastName' => 'Mustermann'
     *     ],
     *     'addresses' => [
     *         'billing' => [
     *             [
     *                 'street' => 'Example Street 5',
     *                 'zip' => '12345',
     *                 'city' => 'Berlin',
     *                 'countryCode' => 'DE'
     *             ]
     *         ]
     *     ],
     *     'emailAddresses' => [
     *         'business' => ['max@company.com']
     *     ],
     *     'phoneNumbers' => [
     *         'business' => ['+49123456789']
     *     ],
     *     'note' => 'Important customer note.'
     * ];
     * ```
     *
     * ### Response:
     * - On success, returns an array with `success` set to true and the data received from the API.
     * - On failure, returns an array with `success` set to false, the status code, and an error message.
     *
     * @param array $contactData An associative array representing the contact data to be created.
     *                           Example structure provided above.
     * @return array An array containing:
     *               - 'success' (bool): Indicates whether the request was successful.
     *               - 'data' (array|null): Contains the response data from the API on success.
     *               - 'status' (int|null): HTTP status code returned by the API on failure.
     *               - 'error' (string|null): Error message if the request fails.
     */
    public function create(array $contactData)
    {
        try {
            // Send POST request to create a contact using Guzzle client
            $response = $this->client->post('contacts', [
                'json' => $contactData,
            ]);

            // Parse and return response on success
            return [
                'success' => true,
                'data' => json_decode($response->getBody()->getContents(), true),
            ];
        } catch (RequestException $e) {
            // Handle error response
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
     * Update a contact in the LexOffice API using Guzzle.
     *
     * This method updates an existing contact in the LexOffice API using the provided contact ID
     * and contact data. It sends a PUT request to the API endpoint with the updated data in the
     * request payload. This method handles various contact properties, including roles, addresses,
     * email addresses, phone numbers, and more.
     *
     * ### Important Notes:
     * - When updating a contact, any field with more than one entry for the following properties will
     *   result in a validation error:
     *   - `addresses.billing`
     *   - `addresses.shipping`
     *   - `emailAddresses.business`
     *   - `emailAddresses.office`
     *   - `emailAddresses.private`
     *   - `emailAddresses.other`
     *   - `phoneNumbers.business`
     *   - `phoneNumbers.office`
     *   - `phoneNumbers.mobile`
     *   - `phoneNumbers.private`
     *   - `phoneNumbers.fax`
     *   - `phoneNumbers.other`
     *   - `company.contactPersons` (maximum one contact person is allowed for updates)
     *
     * ### Required Fields:
     * - The fields required for updating a contact are generally the same as those used for creating a
     *   contact. Refer to the `create` method documentation for details on required and optional fields.
     *
     * ### Example of an $contactData array:
     * ```php
     * $contactData = [
     *     'version' => 1, // Required: Current version of the contact to handle optimistic locking
     *     'roles' => [
     *         'customer' => new \stdClass()
     *     ],
     *     'person' => [
     *         'salutation' => 'Herr',
     *         'firstName' => 'Maximilian',
     *         'lastName' => 'Mustermann'
     *     ],
     *     'addresses' => [
     *         'billing' => [
     *             [
     *                 'street' => 'Updated Street 7',
     *                 'zip' => '54321',
     *                 'city' => 'Updated City',
     *                 'countryCode' => 'DE'
     *             ]
     *         ]
     *     ],
     *     'emailAddresses' => [
     *         'business' => ['updated.email@example.com']
     *     ],
     *     'phoneNumbers' => [
     *         'business' => ['+49123456789']
     *     ],
     *     'note' => 'Updated customer note.'
     * ];
     * ```
     *
     * ### Response:
     * - On success, this method returns an array with `success` set to true and the data received from the API.
     * - On failure, it returns an array with `success` set to false, the status code, and an error message.
     *
     * ### Example Usage:
     * ```php
     * $contactId = 'be9475f4-ef80-442b-8ab9-3ab8b1a2aeb9'; // Example contact ID
     * $contactData = [
     *     'version' => 1,
     *     'roles' => [
     *         'customer' => new \stdClass()
     *     ],
     *     'person' => [
     *         'salutation' => 'Herr',
     *         'firstName' => 'Maximilian',
     *         'lastName' => 'Mustermann'
     *     ]
     * ];
     * $response = $lexofficeContactManager->update($contactId, $contactData);
     * if ($response['success']) {
     *     // Handle successful update
     *     print_r($response['data']);
     * } else {
     *     // Handle error
     *     echo 'Error: ' . $response['error'];
     * }
     * ```
     *
     * @param string $contactId The unique identifier of the contact to be updated.
     * @param array $contactData An associative array representing the updated contact data.
     *                           Example structure provided above.
     * @return array An array containing:
     *               - 'success' (bool): Indicates whether the request was successful.
     *               - 'data' (array|null): Contains the response data from the API on success.
     *               - 'status' (int|null): HTTP status code returned by the API on failure.
     *               - 'error' (string|null): Error message if the request fails.
     */
    public function update(string $contactId, array $contactData)
    {
        try {
            // Send PUT request to update the contact using Guzzle client
            $response = $this->client->put("contacts/{$contactId}", [
                'json' => $contactData,
            ]);

            // Parse and return response on success
            return [
                'success' => true,
                'data' => json_decode($response->getBody()->getContents(), true),
            ];
        } catch (RequestException $e) {
            // Handle error response
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
     * Retrieve a contact by ID from the LexOffice API using Guzzle.
     *
     * This method fetches the details of a contact identified by the given contact ID
     * from the LexOffice API. It sends a GET request to the API endpoint and retrieves
     * the contact's details, including properties such as roles, personal or company
     * information, addresses, email addresses, phone numbers, and more.
     *
     * ### Expected Response:
     * On success, this method returns the full details of the contact, including:
     * - `id`: Unique ID of the contact.
     * - `organizationId`: Unique ID of the organization the contact belongs to.
     * - `version`: Version number for handling optimistic locking.
     * - `roles`: Contact roles (e.g., customer, vendor).
     * - `person` or `company`: Personal or company information.
     * - `addresses`: Billing and shipping addresses.
     * - Other fields like `emailAddresses`, `phoneNumbers`, `note`, etc.
     *
     * ### Example of a Successful Response:
     * ```json
     * {
     *   "id": "e9066f04-8cc7-4616-93f8-ac9ecc8479c8",
     *   "organizationId": "aa93e8a8-2aa3-470b-b914-caad8a255dd8",
     *   "version": 0,
     *   "roles": {
     *     "customer": {
     *       "number": 10308
     *     }
     *   },
     *   "person": {
     *     "salutation": "Frau",
     *     "firstName": "Inge",
     *     "lastName": "Musterfrau"
     *   },
     *   "note": "Notizen",
     *   "archived": false
     * }
     * ```
     *
     * ### Example Usage:
     * ```php
     * $contactId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8'; // Example contact ID
     * $response = $lexofficeContactManager->find($contactId);
     * if ($response['success']) {
     *     // Handle successful response
     *     print_r($response['data']);
     * } else {
     *     // Handle error
     *     echo 'Error: ' . $response['error'];
     * }
     * ```
     *
     * ### Parameters:
     * - `contactId` (string): The unique identifier of the contact to be retrieved.
     *
     * ### Return Value:
     * This method returns an array containing:
     * - `'success'` (bool): Indicates whether the request was successful.
     * - `'data'` (array|null): Contains the contact data from the API on success.
     * - `'status'` (int|null): HTTP status code returned by the API on failure.
     * - `'error'` (string|null): Error message if the request fails.
     *
     * @param string $contactId The unique identifier of the contact to be retrieved.
     * @return array An array containing:
     *               - 'success' (bool): Indicates whether the request was successful.
     *               - 'data' (array|null): Contains the contact data from the API on success.
     *               - 'status' (int|null): HTTP status code returned by the API on failure.
     *               - 'error' (string|null): Error message if the request fails.
     */
    public function find(string $contactId)
    {
        try {
            // Send GET request to retrieve the contact by ID using Guzzle client
            $response = $this->client->get("contacts/{$contactId}");

            // Parse and return response on success
            return [
                'success' => true,
                'data' => json_decode($response->getBody()->getContents(), true),
            ];
        } catch (RequestException $e) {
            // Handle error response
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
     * Retrieve all contacts or filter contacts from the LexOffice API.
     *
     * This method retrieves all contacts or filters contacts based on the
     * provided criteria. The filtering mechanism supports various query
     * parameters such as `email`, `name`, `number`, `customer`, and `vendor` roles.
     * Multiple filters can be combined using logical AND operations.
     *
     * ### Usage Example:
     * - Retrieve all contacts:
     *   ```php
     *   $response = $lexofficeContactManager->all();
     *   if ($response['success']) {
     *       // Handle successful response
     *       print_r($response['data']);
     *   } else {
     *       // Handle error
     *       echo 'Error: ' . $response['error'];
     *   }
     *   ```
     *
     * - Retrieve filtered contacts:
     *   ```php
     *   $filters = ['email' => 'max@gmx.de', 'name' => 'Mustermann'];
     *   $response = $lexofficeContactManager->all($filters);
     *   if ($response['success']) {
     *       // Handle successful response
     *       print_r($response['data']);
     *   } else {
     *       // Handle error
     *       echo 'Error: ' . $response['error'];
     *   }
     *   ```
     *
     * ### Supported Filters:
     * - `'email'` (string): Filters contacts whose email addresses (in `emailAddresses` or company contactPersons)
     *   match the given email value. At least 3 characters are required.
     * - `'name'` (string): Filters contacts whose name matches the given value. At least 3 characters are required.
     * - `'number'` (integer): Filters contacts by the specified contact number (customer or vendor number).
     * - `'customer'` (boolean): Filters contacts with the customer role (`true` to include, `false` to exclude).
     * - `'vendor'` (boolean): Filters contacts with the vendor role (`true` to include, `false` to exclude).
     *
     * ### Sample Request for Retrieving All Contacts:
     * ```bash
     * curl https://api.lexoffice.io/v1/contacts?page=0 \
     * -X GET \
     * -H "Authorization: Bearer {accessToken}" \
     * -H "Accept: application/json"
     * ```
     *
     * ### Expected Response:
     * On success, the response will include the contacts data along with pagination details.
     * ```json
     * {
     *   "content": [
     *     {
     *       "id": "e9066f04-8cc7-4616-93f8-ac9ecc8479c8",
     *       "organizationId": "aa93e8a8-2aa3-470b-b914-caad8a255dd8",
     *       "version": 0,
     *       "roles": {
     *         "customer": {
     *           "number": 10308
     *         }
     *       },
     *       "person": {
     *         "salutation": "Frau",
     *         "firstName": "Inge",
     *         "lastName": "Musterfrau"
     *       },
     *       "archived": false
     *     }
     *   ],
     *   "totalPages": 1,
     *   "totalElements": 2,
     *   "last": true,
     *   "sort": [
     *     {
     *       "direction": "ASC",
     *       "property": "name",
     *       "ignoreCase": false,
     *       "nullHandling": "NATIVE",
     *       "ascending": true
     *     }
     *   ],
     *   "size": 25,
     *   "number": 0,
     *   "first": true,
     *   "numberOfElements": 2
     * }
     * ```
     *
     * ### Parameters:
     * - `filters` (array, optional): An associative array of filter parameters as key-value pairs.
     *   Supported keys include:
     *   - `'email'`: Filters contacts by their email addresses (minimum 3 characters).
     *   - `'name'`: Filters contacts by their name (minimum 3 characters).
     *   - `'number'`: Filters contacts by their contact number (customer or vendor number).
     *   - `'customer'`: Boolean flag to filter contacts with the customer role.
     *   - `'vendor'`: Boolean flag to filter contacts with the vendor role.
     *
     * ### Return Value:
     * This method returns an array containing:
     * - `'success'` (bool): Indicates whether the request was successful.
     * - `'data'` (array|null): Contains the contacts data from the API on success.
     * - `'status'` (int|null): HTTP status code returned by the API on failure.
     * - `'error'` (string|null): Error message if the request fails.
     *
     * @param array $filters Associative array of filter parameters as key-value pairs.
     * @return array Response array with 'success' status, 'data' containing contacts data,
     *               or 'error' with error details.
     */
    public function all(array $filters = [])
    {
        try {
            $queryParams = http_build_query($filters);
            $response = $this->client->get("contacts?" . $queryParams);

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
     * Delete a contact in the LexOffice API.
     *
     * This method deletes a contact from the LexOffice API using the provided contact ID.
     * It sends a DELETE request to the API endpoint and returns a response indicating
     * the success or failure of the operation. On success, a success message is returned.
     * On failure, an error message and the relevant HTTP status code are provided.
     *
     * ### Example Usage:
     * ```php
     * $contactId = 'be9475f4-ef80-442b-8ab9-3ab8b1a2aeb9';
     * $response = $lexofficeContactManager->delete($contactId);
     * if ($response['success']) {
     *     echo 'Contact deleted successfully.';
     * } else {
     *     echo 'Error: ' . $response['error'];
     * }
     * ```
     *
     * ### Parameters:
     * - `contactId` (string): The unique identifier of the contact to be deleted.
     *
     * ### Return Value:
     * This method returns an array containing:
     * - `'success'` (bool): Indicates whether the request was successful.
     * - `'data'` (array|null): Contains the response data from the API on success.
     * - `'status'` (int|null): HTTP status code returned by the API on failure.
     * - `'error'` (string|null): Error message if the request fails.
     *
     * ### Notes:
     * - The contact will be permanently deleted and cannot be recovered.
     *
     * @param string $contactId The unique identifier of the contact to be deleted.
     * @return array Response array with 'success' status, 'data' on success, or 'error' details.
     */
    public function delete(string $contactId)
    {
        try {
            $response = $this->client->delete("contacts/{$contactId}");

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
