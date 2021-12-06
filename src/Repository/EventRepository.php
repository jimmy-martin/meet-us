<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @return Event[] Returns an array of Event objects according to category
     */
    public function findByCategory(int $categoryId, ?int $limit)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.category = :categoryId')
            ->setParameter(':categoryId', $categoryId)
            ->andWhere('e.isArchived = 0')
            ->orderBy('e.date', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Event[] Returns an array of Event objects according to a keyword
     */
    public function findByKeyword(string $keyword, ?int $limit)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.title LIKE :keyword')
            ->setParameter(':keyword', "%$keyword%")
            ->andWhere('e.isArchived = 0')
            ->orderBy('e.date', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
    
    /**
     * @return Event[] Returns an array of Event objects that are not archived
     * 
     * findByActive(?int $limit) equals to findByActive(int $limit = null)
     */
    public function findByActive(int $limit = null)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.isArchived = 0')
            ->orderBy('e.date', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }



    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
