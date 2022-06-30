<?php

namespace App\Repository;

use App\Entity\Renting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Renting>
 *
 * @method Renting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Renting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Renting[]    findAll()
 * @method Renting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RentingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Renting::class);
    }

    public function add(Renting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Renting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function filter(array $filters): array
    {
        $qb = $this->createQueryBuilder('r');
        if (!empty($filters['start']) && !empty($filters['end'])) {
//            $qb->andWhere(
//                "NOT EXISTS (SELECT r FROM App\Entity\Renting r
//                            WHERE :start BETWEEN r.start AND r.end
//                            OR :end BETWEEN r.start AND r.end
//                            OR r.start BETWEEN :start AND :end
//                            OR r.end BETWEEN :start AND :end
//                            )"
//            )
            $sql = "
                    SELECT * FROM renting r LEFT JOIN car c on r.car_id = c.id 
                            WHERE '" . $filters['start'] . "' BETWEEN r.start AND r.end
                            OR '" . $filters['end'] . "' BETWEEN r.start AND r.end
                            OR r.start BETWEEN '" . $filters['start'] . "' AND '". $filters['end'] ."'
                            OR r.end BETWEEN '" . $filters['start'] . "' AND '" . $filters['end'] . "'
                        
               ";
            $em = $this->getEntityManager();
            $stmt = $em->getConnection()->prepare($sql);
            $resultSet = $stmt->executeQuery();
//            dd($resultSet->fetchAllAssociative());
        }

//        dd($qb->getQuery());
        foreach ($filters as $key => $filter) {
            if ($key != "start" && $key != "end" && !empty($filter)) {
                $qb->andWhere('c.' . $key . ' = ' . "'" . $filter . "'");
            }
        }
        $results = $qb->getQuery()->getResult();
//        dd($results);

        return $results;
    }

//    /**
//     * @return Renting[] Returns an array of Renting objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Renting
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
