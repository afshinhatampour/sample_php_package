<?php

namespace Afs\Src;

use GuzzleHttp\Client;

class Main {
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client();
    }
    public function sayHello() {
        $data = $this->httpClient->get('https://jsonplaceholder.typicode.com/todos/1');
        return $data->getBody();
    }
}