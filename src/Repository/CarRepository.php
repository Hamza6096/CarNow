<?php

namespace App\Repository;

use App\Entity\Car;
use App\Entity\Renting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 *
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function add(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function filter(array $filters): array

//    SELECT c.id FROM car c WHERE c.id NOT IN ( SELECT r.car_id FROM renting r WHERE r.car_id = c.id AND r.start BETWEEN "2022-06-14 00:00:00" AND "2022-06-17 00:00:00" OR r.end BETWEEN "2022-06-14 00:00:00" AND "2022-06-17 00:00:00" );
    {
        $qb = $this->createQueryBuilder('c');
        if (!empty($filters['start']) && !empty($filters['end'])) {

            $qb->andWhere('c.id NOT IN (
                SELECT r.car_id FROM App\Entity\Renting r
                WHERE r.car_id = c.id
                AND r.start BETWEEN :start AND :end
                OR r.end BETWEEN :start AND :end
           )');
            $qb->setParameter('start', $filters['start']);
            $qb->setParameter('end', $filters['end']);
        }
        //dd($qb->getQuery());
        foreach ($filters as $key => $filter) {
            if (!in_array($key, ['start', 'end'])) {
                $qb->andWhere('c.' . $key . ' = ' . "'" . $filter . "'");
            }
        }

        $results = $qb->getQuery()->getResult();

        return $results;
    }




//    public function findOneBySomeField($value): ?Car
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
