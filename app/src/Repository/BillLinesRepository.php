<?php

namespace App\Repository;

use App\Entity\BillLines;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BillLines>
 *
 * @method BillLines|null find($id, $lockMode = null, $lockVersion = null)
 * @method BillLines|null findOneBy(array $criteria, array $orderBy = null)
 * @method BillLines[]    findAll()
 * @method BillLines[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BillLinesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BillLines::class);
    }

    public function save(BillLines $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BillLines $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllLinesForOneBookings($id): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.booking_id = :id')
            ->setParameters('id', $id)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return BillLines[] Returns an array of BillLines objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BillLines
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}