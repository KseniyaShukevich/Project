<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Form\AnswerType;
use App\Form\QuestionType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    /**
     * @Route("/answers", name="answers")
     */
    public function index()
    {
        return $this->render('answer/index.html.twig', [
            'controller_name' => 'AnswerController',
        ]);
    }

    /**
     * @Route("/answers_create/admin", name="create-answers")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function  create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $answer = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($answer);
            $entityManager->flush();

            $this->redirectToRoute('answers');
        }


        return  $this->render('answer/createAnswer.html.twig', [
            'controller_name' => 'AnswerController',
            'form' => $form->createView(),
        ]);
    }

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

    /**
     * @Route("/answers/edit", name="edit-answers",  methods={"GET", "POST","HEAD"})
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function editAnswers($id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $question = new Question();
        $entityManager->getRepository(Question::class);

        $question = $this->getDoctrine()
            ->getRepository(Question::class)
            ->find($id);

        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('edit-quizs');
        }

        return $this->render('answer/edirAnswers.html.twig',
            [
                'controller_name' => 'AnswerController',
                'form' => $form->createView(),
                'AnswerCollection' => ArrayCollection::class,
                'question'=>$question,
            ]);
    }
}
