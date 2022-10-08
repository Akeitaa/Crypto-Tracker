<?php

namespace App\Repository;

use App\Entity\DailyValuation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DailyValuation>
 *
 * @method DailyValuation|null find($id, $lockMode = null, $lockVersion = null)
 * @method DailyValuation|null findOneBy(array $criteria, array $orderBy = null)
 * @method DailyValuation[]    findAll()
 * @method DailyValuation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DailyValuationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DailyValuation::class);
    }

    public function save(DailyValuation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DailyValuation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return DailyValorisation[] Returns an array of DailyValorisation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DailyValorisation
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
