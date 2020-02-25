<?php

namespace App\Controller;

use App\Entity\Question;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    /**
     * @Route("/answers/{id}", name="answers-to-question",  methods={"GET", "HEAD"})
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function getAnswers($id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $entityManager->getRepository(Question::class);

        $question = $this->getDoctrine()
            ->getRepository(Question::class)
            ->find($id);

        return $this->render('answer/answers.html.twig',
            [
                'controller_name' => 'AnswerController',
                'AnswerCollection' => ArrayCollection::class,
                'question'=>$question,
            ]);
    }
}
