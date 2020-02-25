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
     * @param bool $includeUnavailableProducts
     * @return quiz[]
     */
    public function findAllActive($includeUnavailableProducts = false): array
    {
        $qb = $this->createQueryBuilder('q')
            ->where('q.isActive = 1')
            ->orderBy('q.name', 'ASC');

        $query = $qb->getQuery();

        return $query->execute();
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
}
