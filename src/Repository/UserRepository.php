<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use function get_class;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Return users that have been created this month
     */
    public function findCreatedThisMonth()
    {
        $date = new \DateTime();
        $dateMonth = $date->format('m');
        $dateYear = $date->format('Y');

        $firstDayOfMonth = mktime(0, 0, 0, $dateMonth, 0, $dateYear);
        $firstDayOfMonthDate = date('Y-m-d', $firstDayOfMonth);

        $lastDayOfMonth = mktime(0, 0, 0, $dateMonth + 1, 0, $dateYear);
        $lastDayOfMonthDate = date('Y-m-d', $lastDayOfMonth);

        return $this->createQueryBuilder('u')
            ->andWhere('u.createdAt BETWEEN :firstDayOfMonth AND :lastDayOfMonth')
            ->setParameter(':firstDayOfMonth', $firstDayOfMonthDate)
            ->setParameter(':lastDayOfMonth', $lastDayOfMonthDate)
            ->getQuery()
            ->getResult();
    }

    /**
     * Return users that have been created the last 7 days
     */
    public function findNewlySubscribedUsersPastSevenDays()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.createdAt > :todayMinusSevenDays')
            ->setParameter(':todayMinusSevenDays', new \DateTimeImmutable('-7 days'))
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
