<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientErrorResponseException;
use GuzzleHttp\Exception\RequestException;

class ApiControllerTest extends WebTestCase
{
    private $default_url = 'http://localhost:8001';

    public function testStoreOfferShouldPass()
    {
        $client = new Client([
            'base_url' => $this->default_url,
            'defaults' => [
                'exceptions' => false
            ]
        ]);

        $data = array(
            'title' => 'TestCase Title',
            'description' => 'some default test',
            'email' => 'test'.mt_rand(1000000,(1000000*10)-1).'@tradus.com',
            'image' => 'https://apollo-ireland.akamaized.net/v1/files/irvcqv1wko8d1-HVYM/image'
        );

        $response = $client->post($this->default_url.'/offer', [
            'body' => json_encode($data)
        ]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testStoreOfferShouldFail_1()
    {
        $client = new Client([
            'base_url' => $this->default_url,
            'defaults' => [
                'exceptions' => false
            ]
        ]);
        $data = array(
            'title' => '',
            'description' => 'some default test'
        );
        try {
            $response = $client->post($this->default_url . '/offer', [
                'body' => json_encode($data)
            ]);
        } catch (RequestException $e) {
            $this->assertEquals(406, $e->getCode());
        }
    }

    public function testStoreOfferShouldFail_2()
    {
        $client = new Client([
            'base_url' => $this->default_url,
            'defaults' => [
                'exceptions' => false
            ]
        ]);

        $data = array(
            'title' => 'TestCase Title',
            'description' => 'some default test',
            'email' => 'test'.mt_rand(1000000,(1000000*10)-1),
            'image' => 'https://apollo-ireland.akamaized.net/v1/files/irvcqv1wko8d1-HVYM/image'
        );

        try {
            $response = $client->post($this->default_url . '/offer', [
                'body' => json_encode($data)
            ]);
        } catch (RequestException $e) {
            $this->assertEquals(406, $e->getCode());
        }
    }

    public function testStoreOfferShouldFail_3()
    {
        $client = new Client([
            'base_url' => $this->default_url,
            'defaults' => [
                'exceptions' => false
            ]
        ]);

        $data = array(
            'title' => 'TestCase Title',
            'description' => 'some default test',
            'email' => 'test'.mt_rand(1000000,(1000000*10)-1).'@tradus.com',
            'image' => 'image'
        );

        try {
            $response = $client->post($this->default_url . '/offer', [
                'body' => json_encode($data)
            ]);
        } catch (RequestException $e) {
            $this->assertEquals(406, $e->getCode());
        }
    }

    public function testGetOffersURL()
    {
        $client = new Client([
            'base_url' => $this->default_url,
            'defaults' => [
                'exceptions' => false
            ]
        ]);

        $response = $client->get($this->default_url.'/offers',[]);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
