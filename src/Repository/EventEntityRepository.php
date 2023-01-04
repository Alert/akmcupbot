<?php

namespace App\Repository;

use App\Entity\EventEntity;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventEntity>
 *
 * @method EventEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventEntity[]    findAll()
 * @method EventEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventEntity::class);
    }

    public function save(EventEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EventEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Find one (next) uncompleted event
     *
     * @param DateTime $date
     * @return EventEntity|null
     * @throws NonUniqueResultException
     */
    public function findOneUncompleted(DateTime $date = new DateTime()): ?EventEntity
    {
        $qb = $this->createQueryBuilder('e');
        $qb->where('e.dateEnd > :date')->setParameter('date', $date->format('Y-m-d'));
        $qb->orderBy('e.id', 'ASC');
        $qb->setMaxResults(1);
        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }
}
