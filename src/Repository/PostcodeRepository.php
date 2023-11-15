<?php

namespace App\Repository;

use App\Entity\Postcode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Postcode>
 *
 * @method Postcode|null find($id, $lockMode = null, $lockVersion = null)
 * @method Postcode|null findOneBy(array $criteria, array $orderBy = null)
 * @method Postcode[]    findAll()
 * @method Postcode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostcodeRepository extends ServiceEntityRepository
{
    public const PER_PAGE = 100;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Postcode::class);
    }

    public function getPostcodesByCode(string $postcode, int $page = 1, int $perPage = self::PER_PAGE): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.code LIKE :code')
            ->setMaxResults($perPage)
            ->setFirstResult($perPage * ($page - 1))
            ->setParameter('code', '%' . $postcode)
            ->getQuery()
            ->getResult();
    }
}
