<?php

namespace App\Repository;

use App\Entity\Car;
use App\Entity\Renting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use http\Env\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use function Doctrine\ORM\QueryBuilder;

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

    public function getPaginated(int $page, int $length)
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.id','desc')
            ->setFirstResult(($page - 1) * $length)
            ->setMaxResults($length)
            ;
        return $qb->getQuery()->getResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countCars()
    {
        return $this->createQueryBuilder('c')
            ->select("COUNT(c.id)")
            ->getQuery()
            ->getSingleScalarResult();
    }


//
//    public function filter(array $filters): array
//
////    SELECT c.id FROM car c WHERE c.id NOT IN ( SELECT r.car_id FROM renting r WHERE r.car_id = c.id AND r.start BETWEEN "2022-06-14 00:00:00" AND "2022-06-17 00:00:00" OR r.end BETWEEN "2022-06-14 00:00:00" AND "2022-06-17 00:00:00" );
//    {
////        $qb = $this->_em->createQueryBuilder()
////            ->select('r.car')
////            ->from(Renting::class, 'r')
////            ->innerJoin(Car::class, 'c1')
////            ->where('r.car = c1.id
////            AND r.start BETWEEN :start AND :end
////            OR r.end BETWEEN :start AND :end')
////            ->setParameter('start', $filters['start'])
////            ->setParameter('end', $filters['end'])
////            ->getDQL();
////
////        $query = $this->_em->createQueryBuilder();
////        $query->select('c2.id')
////            ->from(Car::class, 'c2')
////            ->where($query->expr()->notIn('c2.id', $qb));
////
////        foreach ($filters as $key => $filter) {
////            if (!in_array($key, ['start', 'end'])) {
////                $query->andWhere('c.' . $key . ' = ' . "'" . $filter . "'");
////            }
////        }
////
////        return $query->getQuery()->getResult();
////    }
//
//
//
//        $qb = $this->createQueryBuilder('c');
//        if (!empty($filters['start']) && !empty($filters['end'])) {
//
//            $qb->andWhere('c.id NOT IN (
//                SELECT r.car_id FROM App\Entity\Renting r
//                WHERE r.car_id = c.id
//                AND r.start BETWEEN :start AND :end
//                OR r.end BETWEEN :start AND :end
//           )');
//            $qb->setParameter('start', $filters['start']);
//            $qb->setParameter('end', $filters['end']);
//        }
//        //dd($qb->getQuery());
//        foreach ($filters as $key => $filter) {
//            if (!in_array($key, ['start', 'end'])) {
//                $qb->andWhere('c.' . $key . ' = ' . "'" . $filter . "'");
//            }
//        }
//
//        $results = $qb->getQuery()->getResult();
//
//        return $results;
//    }


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
