<?php

namespace App\Repository;

use App\Entity\ReserveFoodTrucks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReserveFoodTrucks>
 *
 * @method ReserveFoodTrucks|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReserveFoodTrucks|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReserveFoodTrucks[]    findAll()
 * @method ReserveFoodTrucks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReserveFoodTrucksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReserveFoodTrucks::class);
    }

    public function add(ReserveFoodTrucks $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReserveFoodTrucks $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return ReserveFoodTrucks[] Returns an array of ReserveFoodTrucks objects
     */
    public function countFoodTrucksReservationCurrentWeek(?string $foodTruckName, ?string $startDate, ?string $endDate): int
    {
        return $this->createQueryBuilder('b')
            ->select('count(b.id)')
            ->where('b.foodTruckName = :foodTruckName')
            ->andWhere('b.reservationDate BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('foodTruckName', $foodTruckName)
            ->getQuery()
            ->getSingleScalarResult();

    }

    /**
     * @return ReserveFoodTrucks[] Returns an array of ReserveFoodTrucks objects
     */
    public function countReservedSpacesCurrentWeek(?\DateTime $bookingDate): int
    {
        try {
            $countBooks = $this->createQueryBuilder('b')
                ->select('count(b.id)')
                ->andWhere('b.reservationDate = :bookingDate')
                ->setParameter('bookingDate', $bookingDate->format('Y-m-d'))
                ->getQuery()
                ->getSingleScalarResult();

           return $countBooks;
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }

    }




//    /**
//     * @return ReserveFoodTrucks[] Returns an array of ReserveFoodTrucks objects
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

//    public function findOneBySomeField($value): ?ReserveFoodTrucks
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
