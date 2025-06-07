<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class UnauthorizedAccess extends TestCase
{

    // by default guzzle throw an exception if the req is not 200. 
    // Although we expect a 401, we get failed test. because that guzzle defalault behaviour. 
    // There are 2 ways to handle that. 
    // one way is to catch the exception and test based on it. the second way is to surpress http errors. 

    
    // all the bellow test will suceed. cuz i expect a 401 res of all
    public function testGetUsersUnauthorized()
    {
        $client = new Client([
            'base_uri' => 'http://localhost:8000',
        ]);

        try {
            $client->get('/users');
            $this->fail('Expected 401 Unauthorized, but request succeeded.');
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $this->assertEquals(401, $response->getStatusCode());

            $body = json_decode((string)$response->getBody(), true);
            $this->assertEquals(401, $body['status']);
            $this->assertEquals('Unauthorized', $body['message']);
        }
    }

    public function testUsers()
    {
        $client = new Client(['base_uri' => 'http://localhost:8000/']);
        $response = $client->get('/users', ['http_errors' => false]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testProducts()
    {
        $client = new Client(['base_uri' => 'http://localhost:8000/']);
        $response = $client->get('/products', ['http_errors' => false]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testProductsGetByOne()
    {
        $client = new Client(['base_uri' => 'http://localhost:8000/']);
        $response = $client->get('/products/1', ['http_errors' => false]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testUsersGetByOne()
    {
        $client = new Client(['base_uri' => 'http://localhost:8000/']);
        $response = $client->get('/users/1', ['http_errors' => false]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testDeleteUser()
    {
        $client = new Client(['base_uri' => 'http://localhost:8000/']);
        $response = $client->delete('/users/1', ['http_errors' => false]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testStoreUser()
    {
        $client = new Client(['base_uri' => 'http://localhost:8000/']);
        $response = $client->post('/users', [
            'http_errors' => false,

            'json' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => 'secret123'
            ]
        ]);


        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testPatchUser()
    {
        $client = new Client(['base_uri' => 'http://localhost:8000/']);
        $response = $client->patch('/users/1', [
            'http_errors' => false,

            'json' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => 'secret123'
            ]
        ]);


        $this->assertEquals(401, $response->getStatusCode());
    }

}
