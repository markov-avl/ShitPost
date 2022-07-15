<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Shit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findById(string $id): ?Post
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function getWithShitCount(): array
    {
        $shitClass = Shit::class;
        $query = $this->createQueryBuilder('p')
            ->select('p.id', 'u.username', 'p.date', 'p.message')
            ->addSelect("(SELECT COUNT(1) FROM $shitClass s WHERE s.post = p.id) AS shit_count")
            ->innerJoin('p.user', 'u')
            ->orderBy('p.date', 'DESC');
        return $query->getQuery()->getArrayResult();
    }

    public function getWithShitCountShitted(int $userId): array
    {
        $shitClass = Shit::class;
        $query = $this->createQueryBuilder('p')
            ->select('p.id', 'u.username', 'p.date', 'p.message')
            ->addSelect("(SELECT COUNT(1) FROM $shitClass s0 WHERE s0.post = p.id) AS shit_count")
            ->addSelect("(SELECT COUNT(1) FROM $shitClass s1 WHERE s1.post = p.id AND s1.user = :user_id) AS shitted")
            ->innerJoin('p.user', 'u')
            ->orderBy('p.date', 'DESC')
            ->setParameter('user_id', $userId);
        return $query->getQuery()->getArrayResult();
    }

    public function getUserPostsWithShitCountShitted(int $userId): array
    {
        $shitClass = Shit::class;
        $query = $this->createQueryBuilder('p')
            ->select('p.id', 'u.username', 'p.date', 'p.message')
            ->addSelect("(SELECT COUNT(1) FROM $shitClass s0 WHERE s0.post = p.id) AS shit_count")
            ->addSelect("(SELECT COUNT(1) FROM $shitClass s1 WHERE s1.post = p.id AND s1.user = :user_id) AS shitted")
            ->innerJoin('p.user', 'u')
            ->where('p.user = :user_id')
            ->orderBy('p.date', 'DESC')
            ->setParameter('user_id', $userId);
        return $query->getQuery()->getArrayResult();
    }

    public function getUserShitsWithShitCountShitted(int $userId): array
    {
        $shitClass = Shit::class;
        $query = $this->createQueryBuilder('p')
            ->select('p.id', 'u.username', 'p.date', 'p.message')
            ->addSelect("(SELECT COUNT(1) FROM $shitClass s0 WHERE s0.post = p.id) AS shit_count")
            ->addSelect("1 AS shitted")
            ->innerJoin('p.user', 'u')
            ->where("(SELECT COUNT(1) FROM $shitClass s1 WHERE s1.post = p.id AND s1.user = :user_id) = 1")
            ->orderBy('p.date', 'DESC')
            ->setParameter('user_id', $userId);
        return $query->getQuery()->getArrayResult();
    }

    public function add(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
