<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Products>
 *
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }

    public function save(Products $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Products $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findTentSites()
    {
        $db = $this->findAllOptimise();
        return $db->orderBy('p.rental_type', 'desc')
            ->where('r.label = :type')
            ->setParameter('type', 'emplacement 8m2')
            ->getQuery()->getResult();
    }

    public function findMobileHome()
    {
        $db = $this->findAllOptimise();
        return $db->orderBy('p.rental_type', 'desc')
            ->where('r.label = :type')
            ->setParameter('type', 'mobile-home')
            ->getQuery()->getResult();
    }

    public function findCaravane()
    {
        $db = $this->findAllOptimise();
        return $db->orderBy('p.rental_type', 'desc')
            ->where('r.label = :type')
            ->setParameter('type', 'caravane')
            ->getQuery()->getResult();
    }

    public function findHousing()
    {
        $db = $this->findAllOptimise();
        return $db->orderBy('p.rental_type', 'desc')
            ->where('r.label != :type')
            ->setParameter('type', 'emplacement')
            ->getQuery()->getResult();
    }

    private function findAllOptimise()
    {
        return $this->createQueryBuilder('p')
            ->join('p.rental_type', 'r')
            ->addSelect('r');
        //->orderBy('p.rental_type', 'desc');
        //->getQuery()->getResult();
    }

//    /**
//     * @return Products[] Returns an array of Products objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Products
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}