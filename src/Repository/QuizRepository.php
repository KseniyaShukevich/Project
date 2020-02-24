<?php

namespace App\Repository;

use App\Entity\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Quiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quiz[]    findAll()
 * @method Quiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    /**
     * @return quiz[]
     */
    public function findAllActive(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT q
            FROM App\Entity\Quiz q
            WHERE q.isActive = 1
            ORDER BY q.name ASC'
        )/*->setParameter('activeState', $active)*/;
        return $query->getResult();
    }

    /**
     * @param $idQuiz
     * @return array
     */
    public function quizName($idQuiz): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT q.name
            FROM App\Entity\Quiz q
            WHERE q.id = :idQuiz'
        )->setParameter('idQuiz', $idQuiz);
        return $query->getResult();
    }

    public function quizQuestions($idQuiz): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT q.text, q.id
            FROM App\Entity\Question q
            WHERE q.idQuiz = :idQuiz'
        )->setParameter('idQuiz', $idQuiz);
        return $query->getResult();
    }

    // /**
    //  * @return Quiz[] Returns an array of Quiz objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Quiz
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
