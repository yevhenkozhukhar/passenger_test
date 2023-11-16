<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures;

use App\Entity\Postcode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostcodeFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getPostcodeData() as $item) {
            $postcode = new Postcode();
            $postcode
                ->setCode($item['code'])
                ->setEastings($item['eastings'])
                ->setNorthings($item['northings'])
                ->setCountryCode($item['countryCode'])
                ->setLatitude($item['latitude'])
                ->setLongitude($item['longitude']);
            $manager->persist($postcode);
        }

        $manager->flush();
    }

    private function getPostcodeData(): array
    {
        return [
            ["code" => "G3  6AA","eastings" => 257689,"northings" => 666228,"countryCode" => "S92000003","latitude" => 55.86814693059495,"longitude" => -4.275792246922686],
            ["code" => "G3  6AB","eastings" => 257671,"northings" => 666337,"countryCode" => "S92000003","latitude" => 55.869120191200146,"longitude" => -4.276136908278653],
            ["code" => "G3  6AH","eastings" => 257604,"northings" => 666336,"countryCode" => "S92000003","latitude" => 55.86909141626177,"longitude" => -4.2772061613026],
            ["code" => "G3  6AN","eastings" => 257604,"northings" => 666271,"countryCode" => "S92000003","latitude" => 55.86850786134367,"longitude" => -4.277171995955218],
            ["code" => "G3  6AP","eastings" => 257507,"northings" => 666310,"countryCode" => "S92000003","latitude" => 55.86882931652251,"longitude" => -4.278741267030718],
            ["code" => "EH1 1NB","eastings" => 326057,"northings" => 673593,"countryCode" => "S92000003","latitude" => 55.94965784415435,"longitude" => -3.185629671658287],
            ["code" => "EH1 1ND","eastings" => 325689,"northings" => 673650,"countryCode" => "S92000003","latitude" => 55.950113026154746,"longitude" => -3.191536773494104],
            ["code" => "EH1 1NE","eastings" => 326042,"northings" => 673551,"countryCode" => "S92000003","latitude" => 55.94927825424965,"longitude" => -3.1858582749685973],
            ["code" => "EH1 1NL","eastings" => 326090,"northings" => 673592,"countryCode" => "S92000003","latitude" => 55.94965394444958,"longitude" => -3.1851010937145556],
            ["code" => "EH1 1NP","eastings" => 326052,"northings" => 673452,"countryCode" => "S92000003","latitude" => 55.94839049530402,"longitude" => -3.185671000702475],
            ["code" => "EH1 1NQ","eastings" => 325960,"northings" => 673503,"countryCode" => "S92000003","latitude" => 55.948834435855815,"longitude" => -3.1871578187741663],
            ["code" => "EH1 1NS","eastings" => 326008,"northings" => 673456,"countryCode" => "S92000003","latitude" => 55.948419645448226,"longitude" => -3.186376481029632],
            ["code" => "DEMO","eastings" => 100000,"northings" => 100000,"countryCode" => "S92000003","latitude" => 50.721974,"longitude" => -6.252021],
        ];
    }
}