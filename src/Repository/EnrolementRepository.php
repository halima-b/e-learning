<?php

namespace App\Repository;

use App\Entity\Enrolement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Enrolement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enrolement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enrolement[]    findAll()
 * @method Enrolement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnrolementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enrolement::class);
    }

    // /**
    //  * @return Enrolement[] Returns an array of Enrolement objects
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
    public function findOneBySomeField($value): ?Enrolement
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findOneById($student_id,$course_id): ?Enrolement
    {
        return $this->createQueryBuilder('e')
            ->join('e.student', 's')
            ->join('e.course', 'c')
            ->andWhere('s.id = :val1')
            ->andWhere('c.id = :val2')
            ->setParameter('val1', $student_id)
            ->setParameter('val2', $course_id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    public function countStudents($course_id)
    {

        return $this->createQueryBuilder('e')
            ->join('e.course', 'c')
            ->andWhere('c.id = :val1')
            ->setParameter('val1', $course_id)
            ->select('count(e.id)')
            ->getQuery()
            ->getSingleScalarResult();
    
    }
}
