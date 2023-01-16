<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\SeasonEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SeasonEntity>
 *
 * @method SeasonEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method SeasonEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method SeasonEntity[]    findAll()
 * @method SeasonEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SeasonEntity::class);
    }

    public function save(SeasonEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SeasonEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SeasonEntity[] Returns an array of SeasonEntity objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findLast(): SeasonEntity
    {
        $qb = $this->createQueryBuilder('s');
        $qb->orderBy('s.id','desc');
        $qb->setMaxResults(1);

        $query = $qb->getQuery();

        return $query->getSingleResult();
    }
}
