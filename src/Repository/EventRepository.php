<?php

namespace App\Repository;

use App\Entity\Event;
use DateTime;
use DateTimeImmutable;
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
     * Return events that have been created this month
     */
    public function findCreatedThisMonth()
    {
        $date = new DateTime();
        $dateMonth = $date->format('m');
        $dateYear = $date->format('Y');

        $firstDayOfMonth = mktime(0, 0, 0, $dateMonth, 0, $dateYear);
        $firstDayOfMonthDate = date('Y-m-d', $firstDayOfMonth);

        $lastDayOfMonth = mktime(0, 0, 0, $dateMonth + 1, 0, $dateYear);
        $lastDayOfMonthDate = date('Y-m-d', $lastDayOfMonth);

        return $this->createQueryBuilder('e')
            ->andWhere('e.createdAt BETWEEN :firstDayOfMonth AND :lastDayOfMonth')
            ->setParameter(':firstDayOfMonth', $firstDayOfMonthDate)
            ->setParameter(':lastDayOfMonth', $lastDayOfMonthDate)
            ->getQuery()
            ->getResult();
    }

    /**
     * Return events that have been created the last 7 days
     */
    public function findNewlyCreatedEventsPastSevenDays()
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.createdAt > :todayMinusSevenDays')
            ->setParameter(':todayMinusSevenDays', new DateTimeImmutable('-7 days'))
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Event[] Returns an array of Event objects according to category
     */
    public function findByCategory(int $categoryId, ?int $limit)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.category = :categoryId')
            ->setParameter(':categoryId', $categoryId)
            ->andWhere('e.date > :today')
            ->setParameter(':today', new DateTimeImmutable())
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
            ->andWhere('e.date > :today')
            ->setParameter(':today', new DateTimeImmutable())
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
            ->andWhere('e.date > :today')
            ->setParameter(':today', new DateTimeImmutable())
            ->orderBy('e.date', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Event[] Returns an array of user past joined or created Event objects
     */
    public function findPastEvents(int $userId, int $limit = null)
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('e.members', 'm')
            ->andWhere('m.id = :userId')
            ->setParameter(':userId', $userId)
            ->andWhere('e.date < :today')
            ->setParameter(':today', new DateTimeImmutable())
            ->orderBy('e.date', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Event[] Returns an array of user incoming joined or created Event objects
     */
    public function findIncomingEvents(int $userId, int $limit = null)
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('e.members', 'm')
            ->andWhere('m.id = :userId')
            ->setParameter(':userId', $userId)
            ->andWhere('e.date > :today')
            ->setParameter(':today', new DateTimeImmutable())
            ->orderBy('e.date', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Event[] Returns an array of similar Event objects
     */
    public function findRecommendedEvents(Event $event, int $limit = 3)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.category = :eventCategory')
            ->setParameter(':eventCategory', $event->getCategory())
            ->andWhere('e.date > :today')
            ->setParameter(':today', new DateTimeImmutable())
            ->andWhere('e.id != :eventId')
            ->setParameter(':eventId', $event->getId())
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
