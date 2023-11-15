<?php

namespace App\Query\Postcode\Doctrine\PostgreSQL;

use App\DTO\Model\Postcode\PostcodeDTO;
use App\Query\Postcode\BulkInsertPostcodesInterface;
use App\Query\Postcode\GetPostCodesListByCodesInterface;
use App\Service\Persistence\BulkInsertServiceInterface;
use Doctrine\DBAL\Connection;

final readonly class BulkInsertPostcodesQuery implements BulkInsertPostcodesInterface
{
    private const VALUES_PATTERN = "'%s', %d, %d, '%s', %s, %s";

    private const LATITUDE_VALUE_PATTERN = 'ST_Y(ST_Transform(ST_SetSRID(ST_MakePoint(%s, %s), 27700), 4326))';

    private const LONGITUDE_VALUE_PATTERN = 'ST_X(ST_Transform(ST_SetSRID(ST_MakePoint(%s, %s), 27700), 4326))';

    public function __construct(
        private BulkInsertServiceInterface $bulkInsertService,
        private GetPostCodesListByCodesInterface $getPostCodesListByCodes,
    ) {
    }

    public function execute(array $data): void
    {
        $postcodeList = array_map(fn (PostcodeDTO $DTO) => $DTO->code(), $data);
        $existPostcodes = $this->getPostCodesListByCodes->execute($postcodeList);
        $persistenceData = [];
        foreach ($data as $postcode) {
            assert($postcode instanceof PostcodeDTO);
            if (in_array($postcode->code(), $existPostcodes, true)) {
                continue;
            }
            $persistenceData[] = array_merge(
                [
                    'code' => $postcode->code(),
                    'eastings' => $postcode->eastings(),
                    'northings' => $postcode->northings(),
                    'country_code' => $postcode->countryCode(),
                    'latitude' => sprintf(self::LATITUDE_VALUE_PATTERN, $postcode->eastings(), $postcode->northings()),
                    'longitude' => sprintf(self::LONGITUDE_VALUE_PATTERN, $postcode->eastings(), $postcode->northings()),
                ],
            );
        }
        if ($persistenceData) {
            $this->bulkInsertService->bulkInsert('postcodes', $persistenceData, self::VALUES_PATTERN);
        }
    }
}
