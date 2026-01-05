<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Vehicle;
use App\Entity\VehicleBooking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VehicleBooking>
 */
class VehicleBookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VehicleBooking::class);
    }

    public function save(VehicleBooking $vehicleBooking): void
    {
        $this->getEntityManager()->persist($vehicleBooking);
        $this->getEntityManager()->flush();
    }

    public function isVehicleBooked(
        Vehicle $vehicle,
        \DateTimeImmutable $startAt,
        \DateTimeImmutable $endAt,
    ): bool {
        $result = $this->createQueryBuilder('vb')
            ->andWhere('vb.vehicle = :vehicle')
            ->andWhere('vb.startAt BETWEEN :startAt AND :endAt OR vb.endAt BETWEEN :startAt AND :endAt')
            ->setParameter('vehicle', $vehicle)
            ->setParameter('startAt', $startAt)
            ->setParameter('endAt', $endAt)
            ->getQuery()->getResult();

        return count($result) > 0;
    }

    public function isUserHasAlreadyAReservation(
        User $user,
        \DateTimeImmutable $startAt,
        \DateTimeImmutable $endAt,
    ): bool {
        $result = $this->createQueryBuilder('vb')
            ->andWhere('vb.bookedBy = :user')
            ->andWhere('vb.startAt BETWEEN :startAt AND :endAt OR vb.endAt BETWEEN :startAt AND :endAt')
            ->setParameter('user', $user)
            ->setParameter('startAt', $startAt)
            ->setParameter('endAt', $endAt)
            ->getQuery()->getResult();

        return count($result) > 0;
    }
}
