<?php

namespace Codersgarden\PhpLexofficeApi;

use GuzzleHttp\Client;

class SomeMainClass
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function doSomething()
    {
        echo "Hello World! ";
    }
}
