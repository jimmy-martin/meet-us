<?php

namespace App\Tests\Api;

use App\Repository\UserRepository;
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
        $client->request('POST', '/api/v1/categories');
        $this->assertResponseStatusCodeSame(401);

        // PUT / PATCH
        $client->request('PATCH', '/api/v1/categories/2');
        $this->assertResponseStatusCodeSame(401);
        $client->request('PUT', '/api/v1/categories/2');
        $this->assertResponseStatusCodeSame(401);

        // DELETE
        $client->request('DELETE', '/api/v1/categories/2');
        $this->assertResponseStatusCodeSame(401);
    }

    /**
     * Create a client with a default Authorization header.
     */
    protected function createAuthenticatedClient()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'admin@gmail.com']);

        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login_check',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode(array(
                'username' => $user->getUserIdentifier(),
                'password' => 'test',
            ))
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }


    public function testWhileAuthenticated(): void
    {
        $client = $this->createAuthenticatedClient();

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

    public function publicGetUrlList(): array
    {
        return [
            ['/api/v1/categories'],
            ['/api/v1/categories?limit=2'],
        ];
    }
}
