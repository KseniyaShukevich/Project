<?php

namespace App\Repository;

use App\Entity\Answer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Answer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answer[]    findAll()
 * @method Answer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    public function getAnswer($idQuestion): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a
            FROM App\Entity\answer a
            WHERE a.question = :idQuestion'
        )->setParameter('idQuestion', $idQuestion);
        return $query->getResult();
    }

    /**
     * @param $idQuestion
     * @return answer[]
     */
    public function findAllAnswers($idQuestion): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a
            FROM App\Entity\Answer a
            WHERE a.idQuestion = :idQuestion
            ORDER BY a.text ASC'
        )->setParameter('idQuestion', $idQuestion);

        return $query->getResult();
    }
}
