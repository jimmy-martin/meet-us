<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryTest extends WebTestCase
{
    /**
     * @dataProvider publicGetUrlList
     */
    public function testWhileNonAuthenticated($publicGetUrlList): void
    {
        $client = static::createClient();
        // GET
        $crawler = $client->request('GET', $publicGetUrlList);
        $this->assertResponseIsSuccessful();

        // POST
        $client->jsonRequest('POST', '/api/v1/categories', [
            'name' => 'sport'
        ]);
        $this->assertResponseStatusCodeSame(401);

        // PUT / PATCH
        $client->jsonRequest('PATCH', '/api/v1/categories/2', [
            'name' => 'randonnée'
        ]);
        $this->assertResponseStatusCodeSame(401);

        $client->jsonRequest('PUT', '/api/v1/categories/2', [
            'name' => 'culture'
        ]);
        $this->assertResponseStatusCodeSame(401);

        // DELETE
        $client->jsonRequest('DELETE', '/api/v1/categories/2');
        $this->assertResponseStatusCodeSame(401);
    }

    public function testWhileAuthenticated(): void
    {
        $client = $this->createAuthenticatedClient('admin@gmail.com', 'test');

        $client->jsonRequest('POST', '/api/v1/categories', [
            'name' => 'sport',
        ]);

        $data = json_decode($client->getResponse()->getContent(), true);
        $newCategoryId = $data['id'];
        $this->assertResponseStatusCodeSame(201);

        // PUT / PATCH
        $client->jsonRequest('PATCH', '/api/v1/categories/' . $newCategoryId, [
            'name' => 'randonnée'
        ]);
        $this->assertResponseStatusCodeSame(200);
        $client->jsonRequest('PUT', '/api/v1/categories/' . $newCategoryId, [
            'name' => 'culture'
        ]);
        $this->assertResponseStatusCodeSame(200);

        // DELETE
        $client->request('DELETE', '/api/v1/categories/' . $newCategoryId);
        $this->assertResponseStatusCodeSame(204);
    }

    /**
     * Create a client with a default Authorization header.
     */
    protected function createAuthenticatedClient(string $username, string $password)
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->jsonRequest(
            'POST',
            '/api/login_check',
            [
                'username' => $username,
                'password' => $password,
            ]
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    /**
     * List of all GET public urls
     */
    public function publicGetUrlList(): array
    {
        return [
            ['/api/v1/categories'],
            ['/api/v1/categories?limit=2'],
        ];
    }
}
