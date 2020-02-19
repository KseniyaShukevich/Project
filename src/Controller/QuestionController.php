<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/question", name="question")
     */
    public function index()
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);


        return $this->render('question/index.html.twig', [
            'controller_name' => 'QuestionController',
            'form' => $form->createView(),
            'students' => []
        ]);
    }

    /**
     * @Route("/question/index", name="index-question")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function  create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);

        if ($form->isSubmitted() && $form->isValid()){
            $question = $form->getData();

            $entityManager->persist($question);
            $entityManager->flush();

            $this->redirectToRoute('question');
        }


        return  $this->render('question/index.html.twig', [
            'controller_name' => 'QuestionController',
            'form' => $form->createView(),
        ]);
    }
}
