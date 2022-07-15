<?php

namespace App\Repository;

use App\Entity\Shit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Shit>
 *
 * @method Shit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shit[]    findAll()
 * @method Shit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shit::class);
    }

    public function findByUserIdPostId(int $userId, int $postId): ?Shit
    {
        return $this->findOneBy(['user' => $userId, 'post' => $postId]);
    }

    public function getCountByPostId(int $postId): int
    {
        $query = $this->createQueryBuilder('s')
            ->select('COUNT(1) AS count')
            ->where('s.post = :post_id')
            ->setParameter('post_id', $postId);
        $queryResult = $query->getQuery()->getResult();
        return $queryResult[0]['count'];
    }

    public function add(Shit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Shit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
