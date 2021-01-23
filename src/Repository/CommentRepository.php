<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    // /**
    //  * @return Comment[] Returns an array of Comment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function countRating($course_id)
    {

        return $this->createQueryBuilder('cm')
        ->join('cm.course', 'c')
        ->andWhere('c.id = :val')
        ->setParameter('val', $course_id)
        ->select('AVG(cm.rating) as averagerating')
        ->getQuery()
        ->getSingleScalarResult();

    
    }
    public function countComments($course_id)
    {

        return $this->createQueryBuilder('cm')
            ->join('cm.course', 'c')
            ->andWhere('c.id = :val')
            ->setParameter('val', $course_id)
            ->select('count(cm.id)')
            ->getQuery()
            ->getSingleScalarResult();
    
    }
    public function findOneById($student_id,$course_id): ?Comment
    {
        return $this->createQueryBuilder('cm')
            ->join('cm.user', 's')
            ->join('cm.course', 'c')
            ->andWhere('s.id = :val1')
            ->andWhere('c.id = :val2')
            ->setParameter('val1', $student_id)
            ->setParameter('val2', $course_id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
