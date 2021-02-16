<?php

namespace App\Repository;

use App\Entity\RecentActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method RecentActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecentActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecentActivity[]    findAll()
 * @method RecentActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecentActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, RecentActivity::class);
        $this->manager = $manager;
    }

    // /**
    //  * @return RecentActivity[] Returns an array of RecentActivity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RecentActivity
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAll()
    {
        return $this->findBy(array(), array('lastConnection' => 'DESC'), 10);
    }

    public function saveActivity($email, $datetime)
    {
        $newActivity= new RecentActivity();
        $newActivity
            ->setEmail($email)
            ->setLastConnection(new \DateTime($datetime));
        $this->manager->persist($newActivity);
        $this->manager->flush();
    }
}
