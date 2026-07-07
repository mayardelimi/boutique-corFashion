<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<City>
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

//    /**
//     * @return City[] Returns an array of City objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?City
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findDistinctCityNames(): array
    {
        $result = $this->createQueryBuilder('c')
            ->select('DISTINCT c.name')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getScalarResult();

        return array_column($result, 'name');
    }

    public function findAllWithCarriers(): array
    {
        return $this->createQueryBuilder('c')
            ->addSelect('carrier')
            ->join('c.carriercity', 'carrier')
            ->orderBy('c.name', 'ASC')
            ->addOrderBy('carrier.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
