<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;

class BookControllerTest extends AbstractWebTestCase
{
    public function testBooksByCategory(): void
    {
        $client = static::createClient();
        $client->request("GET", "/api/v1/category/1/books");
        $responseContent = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/responses/BookControllerTest_testBooksByCategory.json',
            $responseContent,
        );
    }
}
