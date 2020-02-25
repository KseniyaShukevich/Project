<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Result;
use App\Form\QuestionType;
use App\Form\ResultType;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/questions_create/admin", name="create-questions")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function  create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $quizs = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->findAllActive();

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $question = new Question();
        $answer1 = new Answer();
        $answer2 = new Answer();
        $answer3 = new Answer();
        $question->addAnswer($answer1);
        $question->addAnswer($answer2);
        $question->addAnswer($answer3);
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted()){
            $question = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $answer1->setQuestion($question);
            $answer2->setQuestion($question);
            $answer3->setQuestion($question);
            $entityManager->persist($answer1);
            $entityManager->persist($answer2);
            $entityManager->persist($answer3);
            $entityManager->flush();

          return  $this->redirectToRoute('create-questions');
        }


        return  $this->render('question/createQuestion.html.twig', [
            'controller_name' => 'QuestionController',
            'form' => $form->createView(),
            'quizs' => $quizs,
        ]);
    }

    /**
     * @Route("/question_delete/{id}", methods={"DELETE"})
     * @param Request $request
     * @param $id
     */
    public function deleteQuestion(Request $request, $id)
    {
        $question = $this->getDoctrine()
            ->getRepository(Question::class)
            ->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $answers = $question->getAnswers();

            foreach ($answers as $answer){
                $question->removeAnswer($answer);
            }

        $entityManager->remove($question);
        $entityManager->flush();
    }

    /**
     * @Route("/questions/{idQuiz}", name="questions",  methods={"GET","HEAD"})
     * @param $idQuiz
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function getQuestions($idQuiz, Request $request, PaginatorInterface $paginator, EntityManagerInterface $entityManager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $questionRepository = $entityManager->getRepository(Question::class);

        $quiz = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->find($idQuiz);

        $allQuestionQuery = $questionRepository->createQueryBuilder('q')
            ->where("q.idQuiz = $idQuiz")
            ->getQuery();

        $questions = $paginator->paginate(
            $allQuestionQuery,
            $request->query->getInt('page', 1),
            1
        );

        return $this->render('question/index.html.twig', [
            'controller_name' => 'QuestionController',
            'questions' => $questions,
            'AnswerCollection' => ArrayCollection::class,
            'quiz'=> $quiz,
        ]);
    }

    /**
     * @Route("/question_edit/{idQuiz}/{id}", name="edit-question", methods={"GET", "POST"})
     * @param Request $request
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     */
    public function editQuiz(Request $request, $id, EntityManagerInterface $entityManager, $idQuiz)
    {
        $quizs = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->findAllActive();

        $question = $this->getDoctrine()
            ->getRepository(Question::class)
            ->find($id);

        $answers = $question->getAnswers();

        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        $entityManager
            ->getRepository(Question::class);

        if ($form->isSubmitted()){
            foreach ($answers as $answer){
                $answer->setQuestion($question);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('edit-quizs', ['id'=> $idQuiz]);
        }

        return $this->render('question/editQuestion.html.twig',
            [
                'controller_name' => 'AllQuizController',
                'form' => $form->createView(),
                'quizs'=> $quizs,
            ]);
    }
}
