<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\Request\Postcode\CodeRequestDTO;
use App\DTO\Request\Postcode\CoordsRequestDTO;
use App\Form\Trait\FormErrorsTransformerTrait;
use App\Infrastructure\Helper\ApiResponseHelper;
use App\Infrastructure\Resolver\RequestValidateValueResolver;
use App\Query\Postcode\GetPostcodesByCodeInterface;
use App\Query\Postcode\GetPostcodesByCoordinatesInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1')]
class PostcodeController extends AbstractController
{
    use FormErrorsTransformerTrait;

    #[Route(path: '/postcodes/by-code', name: 'api_postcode_by_code', methods: ['GET'], format: 'json')]
    public function getPostcodesByCodeAction(
        #[MapQueryString(resolver: RequestValidateValueResolver::class)] CodeRequestDTO $codeRequestDTO,
        GetPostcodesByCodeInterface $getPostcodesByCode,
    ): Response {
        $data = $getPostcodesByCode->execute($codeRequestDTO);

        return $this->json(['status' => ApiResponseHelper::SUCCESS, 'data' => $data]);
    }

    #[Route(path: '/postcodes/by-coords', name: 'api_postcode_by_coords', methods: ['GET'], format: 'json')]
    public function getPostcodesByCoordinatesAction(
        #[MapQueryString(resolver: RequestValidateValueResolver::class)] CoordsRequestDTO $coordsRequestDTO,
        GetPostcodesByCoordinatesInterface $getPostcodesByCoordinates,
    ): Response
    {
        $data = $getPostcodesByCoordinates->execute($coordsRequestDTO);

        return $this->json(['status' => ApiResponseHelper::SUCCESS, 'data' => $data]);
    }
}
