<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Postcode;
use App\Infrastructure\Helper\ApiResponseHelper;
use App\Tests\DataFixtures\PostcodeFixture;
use Symfony\Component\HttpFoundation\Response;

class PostcodeControllerTest extends AbstractFixtureWebTestCase
{
    public function testGetPostCodeByCodeAction(): void
    {
        $this->client->request('GET', '/api/v1/postcodes/by-code', ['code' => 'empty']);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals(['status' => ApiResponseHelper::SUCCESS, 'data' => []], $responseData);

        $this->client->request('GET', '/api/v1/postcodes/by-code', ['code' => 'G3']);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertCount(5, $responseData['data']);

        $this->client->request('GET', '/api/v1/postcodes/by-code', ['code' => 'EH']);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertCount(7, $responseData['data']);
    }

    public function testGetPostCodeByCodeActionValidations(): void
    {
        $this->client->request('GET', '/api/v1/postcodes/by-code');
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $expectedData = [
            'status' => ApiResponseHelper::FAILED,
            'message' => ApiResponseHelper::ERROR_PARAMETER_VALIDATION_MESSAGE,
            'errors' => ['code' => 'This value should not be blank.']
        ];
        $this->assertEquals($expectedData, $responseData);

        $this->client->request('GET', '/api/v1/postcodes/by-code', ['code' => 'G', 'perPage' => 0]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $expectedData['errors'] = [
            'code' => 'This value is too short. It should have 2 characters or more.',
            'perPage' => 'This value should be 1 or more.',
        ];
        $this->assertEquals($expectedData, $responseData);
    }

    public function testGetPostcodesByCoordinatesAction(): void
    {
        $this->client->request('GET', '/api/v1/postcodes/by-coords', ['latitude' => 50, 'longitude' => 0]);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals(['status' => ApiResponseHelper::SUCCESS, 'data' => []], $responseData);

        $this->client->request('GET', '/api/v1/postcodes/by-coords', ['latitude' => 50.72, 'longitude' => -6.25]);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertCount(1, $responseData['data']);
        $data = ["code" => "DEMO","eastings" => 100000,"northings" => 100000,"countryCode" => "S92000003","latitude" => 50.721974,"longitude" => -6.252021];
        $this->assertEquals($data, $responseData['data'][0]);
    }

    public function testGetPostcodesByCoordinatesActionValidations(): void
    {
        $this->client->request('GET', '/api/v1/postcodes/by-coords');
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $expectedData = [
            'status' => ApiResponseHelper::FAILED,
            'message' => ApiResponseHelper::ERROR_PARAMETER_VALIDATION_MESSAGE,
            'errors' => [
                'latitude' => 'This value should not be blank.',
                'longitude' => 'This value should not be blank.',
            ]
        ];
        $this->assertEquals($expectedData, $responseData);

        $this->client->request(
            'GET',
            '/api/v1/postcodes/by-coords',
            ['latitude' => 50.72, 'longitude' => -6.25, 'page' => -1, 'perPage' => 0]
        );
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $expectedData['errors'] = [
            'page' => 'This value should be 1 or more.',
            'perPage' => 'This value should be 1 or more.',
        ];
        $this->assertEquals($expectedData, $responseData);
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
