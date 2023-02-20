<?php

namespace App\Repository;

use App\Entity\Bookings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bookings>
 *
 * @method Bookings|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bookings|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bookings[]    findAll()
 * @method Bookings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bookings::class);
    }

    public static function timeStampToDate($date): string
    {
        return date('Y-m-d', $date);
    }

    /**
     * prend deux dates et retourne un tableau de strings des jours dans cet intervalle sous le format yyyy-mm-dd
     */
    public function daysBetweenTwoDate(string $arrive, string $depart): array
    {
        $days_list = [];
        $days_count = (strtotime($depart) - strtotime($arrive)) / (60 * 60 * 24);
        $current_day = strtotime($arrive);

        for ($dbtd = 0; $dbtd < $days_count; $dbtd++) {
            $days_list[] = intval($current_day);
            $current_day += 86400;
        }

        return array_map('self::timeStampToDate', $days_list);
    }

    public function checkAndGetDate($date): ?array
    {
        $date = explode('-', $date);
        $day = $date[2];
        $month = $date[1];
        $year = $date[0];

        if (!checkdate($month, $day, $year))
            return null;

        return [$day, $month, $year];
    }

    public function save(Bookings $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Bookings $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    private function findAllOptimise()
    {
        return $this->createQueryBuilder('b')
            ->join('b.client_id', 'c')
            ->addSelect('c')
            ->join('b.product_id', 'p')
            ->addSelect('p');
    }

    public function findAllByOwnerId($id): array
    {
        return $this->findAllOptimise()
            ->join('p.owner_id', 'o')
            ->addSelect('o')
            ->where('p.owner_id = :id')
            ->setParameter('id', $id)
            ->orderBy('b.product_id', 'asc')
            ->getQuery()->getResult();
    }

//    /**
//     * @return Bookings[] Returns an array of Bookings objects
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

//    public function findOneBySomeField($value): ?Bookings
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}