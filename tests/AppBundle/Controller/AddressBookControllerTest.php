<?php

/*
 * This file is part of the www.lillydoo.com test.
 *
 * @author Omar Makled <omar.makled@gmail.com.com>
 */

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddressBookControllerTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testIndex()
    {
        $this->client->request('GET', '/address-book');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $crawler = $this->client->request('GET', '/address-book/create');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Save')->form([
            'address_book[firstname]' => 'firstname',
            'address_book[lastname]' => 'lastname',
            'address_book[address]' => 'address',
            'address_book[country]' => 'country',
            'address_book[city]' => 'city',
            'address_book[zip]' => 'zip',
            'address_book[phone]' => 'phone',
            'address_book[email]' => 'email@mail.com',
            'address_book[birthday]' => date('Y-m-d'),
        ]);
        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $crawler = $this->client->request('GET', '/address-book/1/edit');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Save')->form([
            'address_book[firstname]' => 'newfirstname',
        ]);
        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testRemoveAction()
    {
        $this->client = $this->client;
        $crawler = $this->client->request('GET', '/address-book');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Delete')->form();
        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testRemovePhotoAction()
    {
        $crawler = $this->client->request('GET', '/address-book');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Delete Photo')->form();
        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}
