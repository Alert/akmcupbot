<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\EventDetailEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventDetailEntity>
 *
 * @method EventDetailEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventDetailEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventDetailEntity[]    findAll()
 * @method EventDetailEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventDetailEntity::class);
    }

    public function save(EventDetailEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EventDetailEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
