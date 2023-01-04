<?php

namespace App\Repository;

use App\Entity\WebhookLogEntity;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WebhookLogEntity>
 *
 * @method WebhookLogEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebhookLogEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebhookLogEntity[]    findAll()
 * @method WebhookLogEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebhookLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebhookLogEntity::class);
    }

    public function save(WebhookLogEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WebhookLogEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Save to db without ORM
     *
     * @param DateTime $ts
     * @param string|null $username
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string $raw
     * @return void
     * @throws Exception
     */
    public function savePlainSql(DateTime    $ts,
                                 string|null $username,
                                 string|null $firstName,
                                 string|null $lastName,
                                 string      $raw): void
    {
        $ts = $ts->format('Y-m-d H:i:s.u');

        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare('
            INSERT INTO webhook_log (ts, username, first_name, last_name, raw) VALUES (?, ?, ?, ?, ?)
        ');
        $stmt->executeStatement([$ts, (string)$username, (string)$firstName, (string)$lastName, $raw]);
    }
}
