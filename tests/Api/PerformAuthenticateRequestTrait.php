<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;

trait PerformAuthenticateRequestTrait
{
    private string $token;

    private function authenticateRequest(User $user): void
    {
        // retrieve a token
        $response = $this->client->request('POST', '/auth', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
            ],
        ]);

        self::assertSame(Response::HTTP_OK, $response->getStatusCode(), 'authenticate failed');
        $json = $response->toArray();
        self::assertArrayHasKey('token', $json, 'token is missing');
        $this->token = $json['token'];
    }
}
