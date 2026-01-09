<?php

namespace App\Repository;

use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vehicle>
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    public function findAllPaginated(int $page, int $limit, ?string $label = null): Paginator
    {
        $queryBuilder = $this
            ->createQueryBuilder('v')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        if (null !== $label) {
            $queryBuilder->where('v.label = :label')->setParameter('label', $label);
        }

        return new Paginator($queryBuilder);
    }

    public function save(Vehicle $vehicle): Vehicle
    {
        $this->getEntityManager()->persist($vehicle);
        $this->getEntityManager()->flush();

        return $vehicle;
    }
}
