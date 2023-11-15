<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dictionary\Response\ResponseStatus;
use App\DTO\Request\Postcode\CodeRequestDTO;
use App\DTO\Request\Postcode\CoordsRequestDTO;
use App\Form\Trait\FormErrorsTransformerTrait;
use App\Query\Postcode\GetPostcodesByCodeInterface;
use App\Query\Postcode\GetPostcodesByCoordinatesInterface;
use App\Repository\PostcodeRepository;
use App\Service\Coordinate\CoordinateConvert;
use PHPCoord\Point\GeographicPoint;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1')]
class PostcodeController extends AbstractController
{
    use FormErrorsTransformerTrait;

    #[Route(path: '/postcodes', name: 'api_postcode_by_code', methods: ['GET'], format: 'json')]
    public function getPostcodesByCodeAction(
        #[MapQueryString] CodeRequestDTO $codeRequestDTO,
        PostcodeRepository $postcodeRepository,
        GetPostcodesByCodeInterface $getPostcodesByCode,
    ): Response {
        $data = $postcodeRepository->getPostcodesByCode(
            $codeRequestDTO->code(),
            $codeRequestDTO->page(),
            $codeRequestDTO->perPage()
        );
        $data = $getPostcodesByCode->execute($codeRequestDTO);

        return $this->json(['status' => ResponseStatus::SUCCESS, 'data' => $data]);
    }

    #[Route(path: '/postcodes/by-coords', name: 'api_postcode_by_coords', methods: ['GET'])]
    public function getPostcodesByCoordinatesAction(
        #[MapQueryString] CoordsRequestDTO $coordsRequestDTO,
        GetPostcodesByCoordinatesInterface $getPostcodesByCoordinates,
    ): Response
    {
        $data = $getPostcodesByCoordinates->execute($coordsRequestDTO);

        return $this->json(['status' => ResponseStatus::SUCCESS, 'data' => $data]);
    }
}
