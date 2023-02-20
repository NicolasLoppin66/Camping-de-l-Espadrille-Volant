<?php

namespace App\Repository;

use App\Entity\Products;
use App\Entity\Disponibilites;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Disponibilites>
 *
 * @method Disponibilites|null find($id, $lockMode = null, $lockVersion = null)
 * @method Disponibilites|null findOneBy(array $criteria, array $orderBy = null)
 * @method Disponibilites[]    findAll()
 * @method Disponibilites[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DisponibilitesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Disponibilites::class);
    }

    public function save(Disponibilites $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Disponibilites $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    private function findAllOptimise()
    {
        return $this->createQueryBuilder('d')
            ->join('d.product_id', 'p')
            ->addSelect('p');

        //->orderBy('p.rental_type', 'desc');
        //->getQuery()->getResult();
    }

    public function findByProductId(Products $product): array
    {
        return $this->createQueryBuilder('d')
            ->where('d.product_id = :val')
            ->setParameter('val', $product->getId())
            ->orderBy('d.day', 'ASC')
            //            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function allDispoForOneProduct(int $id, string $checkin, string $checkout): array
    {
        return $this->createQueryBuilder('d')
            ->where('d.product_id = :id')
            ->andWhere('d.is_booked = :bool')
            ->andWhere('d.day >= :checkin')
            ->andWhere('d.day < :checkout')
            ->setParameters(['id' => $id, 'bool' => false, 'checkin' => $checkin, 'checkout' => $checkout])
            ->orderBy('d.day', 'asc')
            ->getQuery()->getResult();
    }

    public function allDatesForOneMonth($id, $begin, $end)
    {
        return $this->createQueryBuilder('d')
            ->where('d.product_id = :id')
            ->andWhere('d.day >= :begin')
            ->andWhere('d.day <= :end')
            ->setParameters(['id' => $id, 'begin' => $begin, 'end' => $end])
            ->orderBy('d.day', 'asc')
            ->getQuery()->getResult();
    }

    public function timestampToDate($date): string
    {
        return date('Y-m-d', $date);
    }
    /**
     * prend deux dates et retourne un tableau de strings des jours dans cet intervalle sous le format yyyy-mm-dd
     */
    public function daysBetween2Dates(string $arrivee, string $depart): array
    {

        $days_list = [];
        $days_count = (strtotime($depart) - strtotime($arrivee)) / (60 * 60 * 24);
        $current_day = strtotime($arrivee);
        for ($i = 0; $i < $days_count; $i++) {
            $days_list[] = intval($current_day);
            $current_day += 86400;
        }

        return array_map('self::timestampToDate', $days_list);
    }

//    /**
//     * @return Disponibilites[] Returns an array of Disponibilites objects
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

//    public function findOneBySomeField($value): ?Disponibilites
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}