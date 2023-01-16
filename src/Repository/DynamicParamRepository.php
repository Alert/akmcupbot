<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\DynamicParamEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DynamicParamEntity>
 *
 * @method DynamicParamEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method DynamicParamEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method DynamicParamEntity[]    findAll()
 * @method DynamicParamEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DynamicParamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DynamicParamEntity::class);
    }

    public function save(DynamicParamEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DynamicParamEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
