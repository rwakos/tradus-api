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

        $ins_title = 'testUpdateOfferShouldPass';
        $ins_description = 'testUpdateOfferShouldPass';
        $ins_email = 'test_ins'.mt_rand(1000000,(1000000*10)-1).'@tradus.com';
        $ins_image = 'https://apollo-ireland.akamaized.net/v1/files/0nmmvzkt4b1h-HVYM/image';

        $data = array(
            'title' => $ins_title,
            'description' => $ins_description,
            'email' => $ins_email,
            'image' => $ins_image
        );
        $response = $client->post($this->default_url.'/offer', [
            'body' => json_encode($data)
        ]);
        $data = json_decode($response->getBody());
        $last_id = $data->id;

        $this->assertEquals(200, $response->getStatusCode());

        $response = $client->get($this->default_url.'/offers/'.$last_id, [
            'body' => json_encode($data)
        ]);

        $data = json_decode($response->getBody());

        $this->assertEquals($ins_title, $data->title);
        $this->assertEquals($ins_description, $data->description);
        $this->assertEquals($ins_email, $data->email);
        $this->assertEquals($ins_image, $data->image);
    }

    public function testStoreOfferShouldFail_IfEmptyParams()
    {
        $client = new Client([
            'base_url' => $this->default_url,
            'defaults' => [
                'exceptions' => false
            ]
        ]);
        $data = array(
            'title' => '',
            'description' => 'some default test',
            'email' => 'test'.mt_rand(1000000,(1000000*10)-1).'@tradus.com',
            'image' => 'https://apollo-ireland.akamaized.net/v1/files/irvcqv1wko8d1-HVYM/image'
        );
        try {
            $response = $client->post($this->default_url . '/offer', [
                'body' => json_encode($data)
            ]);
        } catch (RequestException $e) {
            $this->assertEquals(406, $e->getCode());
        }

        $data = array(
            'title' => 'With Title',
            'description' => '',
            'email' => 'test'.mt_rand(1000000,(1000000*10)-1).'@tradus.com',
            'image' => 'https://apollo-ireland.akamaized.net/v1/files/irvcqv1wko8d1-HVYM/image'
        );
        try
        {
            $response = $client->post($this->default_url . '/offer', [
                'body' => json_encode($data)
            ]);
        } catch (RequestException $e) {
            $this->assertEquals(406, $e->getCode());
        }

        $data = array(
            'title' => 'With Title',
            'description' => 'Some description',
            'email' => '',
            'image' => 'https://apollo-ireland.akamaized.net/v1/files/irvcqv1wko8d1-HVYM/image'
        );
        try
        {
            $response = $client->post($this->default_url . '/offer', [
                'body' => json_encode($data)
            ]);
        } catch (RequestException $e) {
            $this->assertEquals(406, $e->getCode());
        }

        $data = array(
            'title' => 'Some title',
            'description' => 'some default test',
            'email' => 'test'.mt_rand(1000000,(1000000*10)-1).'@tradus.com',
            'image' => ''
        );

        try
        {
            $response = $client->post($this->default_url . '/offer', [
                'body' => json_encode($data)
            ]);
        } catch (RequestException $e) {
            $this->assertEquals(406, $e->getCode());
        }
    }

    public function testStoreOfferShouldFail_IfFormatIncorrect()
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
            'email' => 'dummyFailemail',
            'image' => 'https://apollo-ireland.akamaized.net/v1/files/irvcqv1wko8d1-HVYM/image'
        );

        try {
            $response = $client->post($this->default_url . '/offer', [
                'body' => json_encode($data)
            ]);
        } catch (RequestException $e) {
            $this->assertEquals(406, $e->getCode());
        }

        $data = array(
            'title' => 'TestCase Title',
            'description' => 'some default test',
            'email' => 'dummy@tradus.com',
            'image' => 'dummyFailemail'
        );

        try {
            $response = $client->post($this->default_url . '/offer', [
                'body' => json_encode($data)
            ]);
        } catch (RequestException $e) {
            $this->assertEquals(406, $e->getCode());
        }
    }

    public function testStoreOfferShouldFail_IfEmailIsNotUnique()
    {
        $client = new Client([
            'base_url' => $this->default_url,
            'defaults' => [
                'exceptions' => false
            ]
        ]);
        $email = 'test'.mt_rand(1000000,(1000000*10)-1).'@tradus.com';
        $data = array(
            'title' => 'TestCase Title',
            'description' => 'some default test',
            'email' => $email,
            'image' => 'https://apollo-ireland.akamaized.net/v1/files/irvcqv1wko8d1-HVYM/image'
        );
        //Repeat the call...
        $client->post($this->default_url . '/offer', [
            'body' => json_encode($data)
        ]);

        try {
            $response = $client->post($this->default_url . '/offer', [
                'body' => json_encode($data)
            ]);
        } catch (RequestException $e) {
            $this->assertEquals(406, $e->getCode());
        }
    }

    public function testUpdateOfferShouldPass(){
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
        $data = json_decode($response->getBody());
        $last_id = $data->id;
        $upd_title = 'testUpdateOfferShouldPass';
        $upd_description = 'testUpdateOfferShouldPass';
        $upd_email = 'test_upd'.mt_rand(1000000,(1000000*10)-1).'@tradus.com';
        $upd_image = 'https://apollo-ireland.akamaized.net/v1/files/0nmmvzkt4b1h-HVYM/image';

        $data = array(
            'title' => $upd_title,
            'description' => $upd_description,
            'email' => $upd_email,
            'image' => $upd_image
        );
        $response = $client->put($this->default_url.'/offers/'.$last_id, [
            'body' => json_encode($data)
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $response = $client->get($this->default_url.'/offers/'.$last_id, [
            'body' => json_encode($data)
        ]);

        $data = json_decode($response->getBody());

        $this->assertEquals($upd_title, $data->title);
        $this->assertEquals($upd_description, $data->description);
        $this->assertEquals($upd_email, $data->email);
        $this->assertEquals($upd_image, $data->image);
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

    public function testGetOffer()
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
