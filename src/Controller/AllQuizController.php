<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\QuizType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AllQuizController extends AbstractController
{
    /**
     * @Route("/all_quizs/profile", name="all_quizs")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $quizs = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->findAllActive();

        return $this->render(
            'all_quiz/index.html.twig',
            [
                'controller_name' => 'AllQuizController',
                'quizs' => $quizs,
            ]
        );
    }

    /**
     * @Route("/quiz_create/admin", name="create-quiz")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function quizCreate(EntityManagerInterface $entityManager, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $quiz = new Quiz();

        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $quiz = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quiz);
            $entityManager->flush();

            return  $this->redirectToRoute('all_quizs');
        }

        return $this->render(
            'all_quiz/createQuiz.html.twig',
            [
                'controller_name' => 'AllQuizController',
                'form' => $form->createView(),
            ]
        );
    }

  /*  public function getQuestions($idQuiz, EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator)
    {
        $questionRepository = $entityManager
            ->getRepository(Question::class);

        $quizs = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->quizName($idQuiz);

        $questions = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->quizQuestions($idQuiz);

      /*  $allQuestionQuery = $questionRepository->createQueryBuilder('q')
            ->where("q.idQuiz = $idQuiz")
            ->getQuery();

        $questions = $paginator->paginate(
            $allQuestionQuery,
            $request->query->getInt('page', 1),
            1
        );*/

     /*   return $this->render(
            'all_quiz/editQuiz.html.twig',
            [
                'controller_name' => 'AllQuizController',
                'idQuiz'=>$idQuiz,
              //  'questions'=>$questions,
                'quizs'=>$quizs,
                'questions'=>$questions,
            ]);*/
      /*  $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $quizs = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->quizName($idQuiz);

      //  $quizs->createQueryBuilder('name')
      //      ->where("q.idQuiz = $idQuiz");

      //  $quiz = $this->$quizs->getQuery();

        return $this->render(
            'all_quiz/editQuiz.html.twig',
            [
                'controller_name' => 'AllQuizController',
              //  'idQuiz'=>$idQuiz,
                'quizs'=>$quizs,
            ]
        );*/
    //}

    /**
     * @Route("/quiz_delete/{id}", methods={"DELETE"})
     * @param Request $request
     * @param $id
     */
    public function deleteQuiz(Request $request, $id)
    {
        $quiz = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->find($id);

        $questions = $this->getDoctrine()
            ->getRepository(Question::class)
            ->allQuizQuestions($id);

        $entityManager = $this->getDoctrine()->getManager();

        foreach ($questions as $question) {
            $entityManager->remove($question);
            $answers = $question->getAnswers();
            foreach ($answers as $answer){
                $question->removeAnswer($answer);
            }

        }

        $entityManager->remove($quiz);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }

    /**
     * @Route("/quizs_edit/{id}", name="edit-quizs", methods={"GET", "POST", "HEAD"})
     * @param Request $request
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     */
    public function editQuiz(Request $request, $id, EntityManagerInterface $entityManager)
    {
        $quiz = new Quiz();
        $quiz = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->find($id);

        $quizs = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->quizName($id);

        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        $questionRepository = $entityManager
            ->getRepository(Question::class);

        $questions = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->quizQuestions($id);

        if ($form->isSubmitted()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('all_quizs');
        }

        return $this->render('all_quiz/editQuiz.html.twig',
            [
                'controller_name' => 'AllQuizController',
                'quiz'=>$quiz,
                'quizs'=>$quizs,
                'questions'=>$questions,
            ]);
    }

    /**
     * @Route("/quiz_edit_name/{id}", name="edit-quiz-name", methods={"GET", "POST", "HEAD"})
     * @param Request $request
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     */
    public function editQuizName(Request $request, $id, EntityManagerInterface $entityManager)
    {
        $quiz = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->find($id);

        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('all_quizs');
        }

        return $this->render('all_quiz/editQuizName.html.twig',
            [
                'controller_name' => 'AllQuizController',
                'form' => $form->createView(),
                'quiz'=>$quiz,
            ]);
    }
}
