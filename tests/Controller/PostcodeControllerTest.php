<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Dictionary\Response\ResponseStatus;
use App\Entity\Postcode;
use App\Tests\DataFixtures\PostcodeFixture;
use Symfony\Component\HttpFoundation\Response;

class PostcodeControllerTest extends AbstractFixtureWebTestCase
{
    public function testGetPostCodeByCodeAction(): void
    {
        $this->client->request('GET', '/api/v1/postcodes', ['code' => 'empty']);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals(['status' => ResponseStatus::SUCCESS, 'data' => []], $responseData);

        $this->client->request('GET', '/api/v1/postcodes', ['code' => 'G3']);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertCount(5, $responseData['data']);

        $this->client->request('GET', '/api/v1/postcodes', ['code' => 'EH']);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertCount(7, $responseData['data']);
    }

    public function testGetPostcodesByCoordinatesAction(): void
    {
        $this->client->request('GET', '/api/v1/postcodes/by-coords', ['latitude' => 50, 'longitude' => 0]);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals(['status' => ResponseStatus::SUCCESS, 'data' => []], $responseData);

        $this->client->request('GET', '/api/v1/postcodes/by-coords', ['latitude' => 50.72, 'longitude' => -6.25]);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertCount(1, $responseData['data']);
        $data = ["code" => "DEMO","eastings" => 100000,"northings" => 100000,"countryCode" => "S92000003","latitude" => 50.721974,"longitude" => -6.252021];
        $this->assertEquals($data, $responseData['data'][0]);
    }

    protected function loadFixturesBefore(): array
    {
        return [
            new PostcodeFixture(),
        ];
    }

    protected function truncateEntitiesAfter(): array
    {
        return [Postcode::class];
    }
}
